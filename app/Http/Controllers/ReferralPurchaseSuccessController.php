<?php

namespace App\Http\Controllers;

use App\Services\StripeCheckoutService;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReferralPurchaseSuccessController extends Controller
{
    public function __invoke(Request $request, StripeCheckoutService $stripeCheckout): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId) {
            return redirect()->to(Filament::getPanel('referral')->getUrl())
                ->with('error', 'Missing session.');
        }

        $fulfilled = $stripeCheckout->fulfillPendingFromSessionId($sessionId);

        $url = Filament::getPanel('referral')->getUrl().'/purchase-packages';

        if ($fulfilled) {
            return redirect()->to($url)->with('success', 'Payment successful. Your package and addons have been activated.');
        }

        return redirect()->to($url)->with('error', 'Payment could not be confirmed. Please contact support.');
    }
}
