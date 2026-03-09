<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Support;

use App\Models\Addon;
use App\Models\BrandApiToken;
use App\Models\BrandPackage;
use App\Models\BrandReferral;
use App\Models\Package;
use Illuminate\Support\Facades\Schema;

/**
 * Consolidates all form-state data for the BrandReferral edit form.
 *
 * Loading every flag (has_custom_branding, has_api_access, expiry, token list) used to
 * fire 4–5 separate queries because each model accessor issues its own brandPackages()
 * query. This class loads all active BrandPackages in a single query, then derives
 * everything from the in-memory collection.
 */
class BrandReferralFormData
{
    /**
     * Build the full set of form-state fields injected during mutateFormDataBeforeFill.
     *
     * @return array<string, mixed>
     */
    public static function forRecord(BrandReferral $record): array
    {
        // Single query: all active BrandPackages with their polymorphic models.
        $activeBrandPackages = $record->brandPackages()
            ->where(function ($q): void {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>', now());
            })
            ->with('model')
            ->get();

        // Derive active Package from the loaded collection (no additional query).
        $activePackageBp = $activeBrandPackages
            ->first(fn (BrandPackage $bp) => $bp->model_type === Package::class);

        $activePackage = $activePackageBp?->model;

        if (! $activePackage instanceof Package) {
            $activePackage = Package::query()->where('default_package', true)->first();
        }

        // Derive active Addons from the same collection.
        $activeAddonBps = $activeBrandPackages->filter(
            fn (BrandPackage $bp) => $bp->model_type === Addon::class
        );

        $hasApiAccess = $activeAddonBps->contains(
            fn (BrandPackage $bp) => $bp->model instanceof Addon && $bp->model->name === 'API Access'
        );

        $apiAccessBp = $activeAddonBps->first(
            fn (BrandPackage $bp) => $bp->model instanceof Addon && $bp->model->name === 'API Access'
        );

        return [
            'has_custom_branding' => $activePackage?->custom_branding ?? false,
            'has_api_access' => $hasApiAccess,
            'api_token_set' => $record->api_token !== null && $record->api_token !== '',
            'api_access_expiry' => $apiAccessBp?->expiry_date instanceof \Carbon\Carbon
                ? $apiAccessBp->expiry_date->format('d/m/Y')
                : null,
            'api_tokens_list' => self::apiTokensList($record),
        ];
    }

    /**
     * Serialise the brand's API tokens for storage in form state.
     * Uses fresh() to guarantee up-to-date data after create/revoke operations.
     *
     * @return list<array{id: int, name: string, scopes: list<string>, created_at: string, last_used_at: string|null}>
     */
    public static function apiTokensList(BrandReferral $record): array
    {
        if (! Schema::hasTable('brand_api_tokens')) {
            return [];
        }

        return $record->fresh()?->brandApiTokens->map(fn (BrandApiToken $t) => [
            'id' => $t->id,
            'name' => $t->name,
            'scopes' => $t->scopes ?? ['form', 'referrals'],
            'created_at' => $t->created_at->format('d M Y'),
            'last_used_at' => $t->last_used_at?->format('d M Y'),
        ])->toArray() ?? [];
    }
}
