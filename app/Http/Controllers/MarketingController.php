<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Addon;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function home(): View
    {
        $packages = Package::query()
            ->whereIn('slug', ['free', 'starter', 'growth'])
            ->orderBy('sort_order')
            ->get();

        return view('marketing.home', [
            'packages' => $packages,
        ]);
    }

    public function features(): View
    {
        return view('marketing.features');
    }

    public function pricing(): View
    {
        $packages = Package::query()
            ->whereIn('slug', ['free', 'starter', 'growth'])
            ->orderBy('sort_order')
            ->get();

        $addons = Addon::query()->orderBy('name')->get();

        return view('marketing.pricing', [
            'packages' => $packages,
            'addons' => $addons,
        ]);
    }

    public function comparePlans(): View
    {
        $packages = Package::query()
            ->whereIn('slug', ['free', 'starter', 'growth'])
            ->orderBy('sort_order')
            ->get();

        return view('marketing.compare-plans', [
            'packages' => $packages,
        ]);
    }

    public function faq(): View
    {
        return view('marketing.faq');
    }

    public function about(): View
    {
        return view('marketing.about');
    }

    public function contact(): View
    {
        return view('marketing.contact');
    }

    public function submitContact(StoreContactRequest $request): RedirectResponse
    {
        $request->validated();

        // TODO: send email to hello@referlyst.com or store in database
        return redirect()->route('contact')->with('contact_success', true);
    }

    public function privacy(): View
    {
        return view('marketing.privacy');
    }

    public function terms(): View
    {
        return view('marketing.terms');
    }

    public function login(): RedirectResponse
    {
        return redirect('/referral/login');
    }

    public function register(): RedirectResponse
    {
        return redirect('/referral/register');
    }

    public function buyPackage(): View|RedirectResponse
    {
        $slug = request()->query('package');

        if (! $slug) {
            return redirect()->route('pricing');
        }

        $package = Package::query()->where('slug', $slug)->first();

        if (! $package) {
            return redirect()->route('pricing');
        }

        $features = array_filter([
            $package->email_notifications ? 'Email notifications' : null,
            $package->commission_tracking ? 'Commission tracking' : null,
            $package->export_csv ? 'Export to CSV' : null,
            $package->custom_branding ? 'Custom branding' : null,
            $package->no_of_referrers > 0 ? $package->no_of_referrers.' referrers' : null,
            $package->no_of_referrers_brands > 0 ? $package->no_of_referrers_brands.' brands' : null,
        ]);

        return view('marketing.buy-package', [
            'package' => $package,
            'features' => $features,
        ]);
    }
}
