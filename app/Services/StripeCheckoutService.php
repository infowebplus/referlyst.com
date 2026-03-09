<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Package;
use App\Models\PendingPackagePurchase;
use Illuminate\Support\Facades\URL;
use Stripe\StripeClient;

class StripeCheckoutService
{
    public function createCheckoutSession(PendingPackagePurchase $pending): ?string
    {
        $secret = config('services.stripe.secret');
        if (! $secret) {
            return null;
        }

        $stripe = new StripeClient($secret);
        $currency = config('services.stripe.currency', 'eur');

        $lineItems = [];

        if ($pending->package_id) {
            $package = Package::find($pending->package_id);
            if ($package) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $package->name,
                            'description' => $package->description,
                        ],
                        'unit_amount' => (int) round($package->price * 100),
                    ],
                    'quantity' => 1,
                ];
            }
        }

        foreach ($pending->addon_ids ?? [] as $addonId) {
            $addon = Addon::find($addonId);
            if ($addon) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $addon->name,
                            'description' => $addon->description,
                        ],
                        'unit_amount' => (int) round($addon->price * 100),
                    ],
                    'quantity' => 1,
                ];
            }
        }

        if (empty($lineItems)) {
            return null;
        }

        $successUrl = URL::route('referral.purchase.success', [], true).'?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = \Filament\Facades\Filament::getPanel('referral')->getUrl().'/purchase-packages';

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => (string) $pending->id,
            'metadata' => [
                'pending_id' => (string) $pending->id,
            ],
        ]);

        $pending->update(['stripe_checkout_session_id' => $session->id]);

        return $session->url;
    }

    public function fulfillPendingFromSessionId(string $sessionId): bool
    {
        $secret = config('services.stripe.secret');
        if (! $secret) {
            return false;
        }

        $stripe = new StripeClient($secret);
        $session = $stripe->checkout->sessions->retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return false;
        }

        $pendingId = $session->metadata->pending_id ?? $session->client_reference_id;
        if (! $pendingId) {
            return false;
        }

        $pending = PendingPackagePurchase::find($pendingId);
        if (! $pending || $pending->completed_at) {
            return false;
        }

        $user = $pending->user;
        $brandId = $pending->brand_id;
        $packageId = $pending->package_id;
        $addonIds = $pending->addon_ids ?? [];
        $now = now();

        \DB::transaction(function () use ($pending, $user, $brandId, $packageId, $addonIds, $now): void {
            if ($packageId) {
                $package = Package::find($packageId);
                if ($package) {
                    // Expire current active package(s) for this brand so the new one becomes the only active (upgrade).
                    \App\Models\BrandPackage::query()
                        ->where('brand_id', $brandId)
                        ->where('model_type', Package::class)
                        ->where(function ($q) use ($now) {
                            $q->whereNull('expiry_date')->orWhere('expiry_date', '>', $now);
                        })
                        ->update(['expiry_date' => $now]);

                    \App\Models\Order::create([
                        'user_id' => $user->id,
                        'company_id' => $user->company_id,
                        'brand_id' => $brandId,
                        'date' => $now,
                        'model_id' => $package->id,
                        'model_type' => Package::class,
                        'price' => $package->price,
                        'paid' => true,
                        'paid_date' => $now,
                        'paid_response' => null,
                    ]);
                    \App\Models\BrandPackage::create([
                        'brand_id' => $brandId,
                        'model_id' => $package->id,
                        'model_type' => Package::class,
                        'price' => $package->price,
                        'purchase_date' => $now,
                        'expiry_date' => $now->copy()->addYear(),
                        'details' => null,
                    ]);
                }
            }

            foreach ($addonIds as $addonId) {
                $addon = Addon::find($addonId);
                if ($addon) {
                    \App\Models\Order::create([
                        'user_id' => $user->id,
                        'company_id' => $user->company_id,
                        'brand_id' => $brandId,
                        'date' => $now,
                        'model_id' => $addon->id,
                        'model_type' => Addon::class,
                        'price' => $addon->price,
                        'paid' => true,
                        'paid_date' => $now,
                        'paid_response' => null,
                    ]);
                    $addonMonths = isset($addon->validity_period) && (int) $addon->validity_period > 0
                        ? (int) $addon->validity_period
                        : 12;
                    \App\Models\BrandPackage::create([
                        'brand_id' => $brandId,
                        'model_id' => $addon->id,
                        'model_type' => Addon::class,
                        'price' => $addon->price,
                        'purchase_date' => $now,
                        'expiry_date' => $now->copy()->addMonths($addonMonths),
                        'details' => null,
                    ]);
                }
            }

            $pending->update(['completed_at' => $now]);
        });

        return true;
    }
}
