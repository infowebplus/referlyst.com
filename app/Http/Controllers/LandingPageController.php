<?php

namespace App\Http\Controllers;

use App\Http\Requests\LandingReferralSubmitRequest;
use App\Models\BrandReferral;
use App\Models\ReferrerBrand;
use App\Models\Service;
use App\Models\User;
use App\Services\ReferralBrandColors;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function show(): View|RedirectResponse
    {
        /** @var BrandReferral $brand */
        $brand = request()->attributes->get('landing_brand');
        $activePackage = $brand->active_package;
        $services = $brand->services()->orderBy('name')->get();

        $maxReferrers = $brand->maxReferrers();
        $currentCount = ReferrerBrand::query()->where('brand_id', $brand->id)->count();
        $slotsRemaining = max(0, $maxReferrers - $currentCount);

        $isPathRoute = request()->route()->getName() === 'landing.show.path';
        $submitUrl = $isPathRoute
            ? route('landing.submit.path', ['path' => $brand->subdomain_for_landing_pages])
            : route('landing.submit', ['subdomain' => $brand->subdomain_for_landing_pages]);

        $termsUrl = $isPathRoute
            ? route('landing.terms.path', ['path' => $brand->subdomain_for_landing_pages])
            : route('landing.terms', ['subdomain' => $brand->subdomain_for_landing_pages]);

        $landingUrl = $isPathRoute
            ? route('landing.show.path', ['path' => $brand->subdomain_for_landing_pages])
            : route('landing.show', ['subdomain' => $brand->subdomain_for_landing_pages]);

        $referlystBase = rtrim(config('app.url'), '/');

        return view('landing.show', [
            'brand' => $brand,
            'activePackage' => $activePackage,
            'services' => $services,
            'maxReferrers' => $maxReferrers,
            'slotsRemaining' => $slotsRemaining,
            'primaryColor' => ReferralBrandColors::primaryForReferrer($brand),
            'brandColors' => ReferralBrandColors::paletteForReferrer($brand),
            'logoUrl' => $this->brandLogoUrl($brand),
            'landingUrl' => $landingUrl,
            'termsUrl' => $termsUrl,
            'brandTermsDirectUrl' => $brand->terms_and_conditions ? $this->normalizeTermsUrl($brand->terms_and_conditions) : null,
            'submitUrl' => $submitUrl,
            'landingTheme' => $brand->landing_theme ?? 'light',
            'referlystTermsUrl' => $referlystBase.'/terms',
            'referlystPrivacyUrl' => $referlystBase.'/privacy',
        ]);
    }

    public function terms(): View
    {
        /** @var BrandReferral $brand */
        $brand = request()->attributes->get('landing_brand');

        $isPathRoute = request()->route()->getName() === 'landing.terms.path';
        $landingUrl = $isPathRoute
            ? route('landing.show.path', ['path' => $brand->subdomain_for_landing_pages])
            : route('landing.show', ['subdomain' => $brand->subdomain_for_landing_pages]);

        $termsAndConditionsUrl = $brand->terms_and_conditions
            ? $this->normalizeTermsUrl($brand->terms_and_conditions)
            : null;

        $referlystBase = rtrim(config('app.url'), '/');

        return view('landing.terms', [
            'brand' => $brand,
            'brandColors' => ReferralBrandColors::paletteForReferrer($brand),
            'logoUrl' => $this->brandLogoUrl($brand),
            'landingUrl' => $landingUrl,
            'termsUrl' => $isPathRoute
                ? route('landing.terms.path', ['path' => $brand->subdomain_for_landing_pages])
                : route('landing.terms', ['subdomain' => $brand->subdomain_for_landing_pages]),
            'brandTermsDirectUrl' => $termsAndConditionsUrl,
            'termsAndConditionsUrl' => $termsAndConditionsUrl,
            'showBackLink' => true,
            'landingTheme' => $brand->landing_theme ?? 'light',
            'referlystTermsUrl' => $referlystBase.'/terms',
            'referlystPrivacyUrl' => $referlystBase.'/privacy',
        ]);
    }

    public function submit(LandingReferralSubmitRequest $request): RedirectResponse
    {
        /** @var BrandReferral $brand */
        $brand = $request->attributes->get('landing_brand');
        $activePackage = $brand->active_package;

        if (! $activePackage) {
            return back()->with('error', 'There is no package allocated to this brand.');
        }

        $userId = (int) session('landing_user_id', 0);
        if (! $userId) {
            $userId = (int) User::query()
                ->where('company_id', $brand->company_id)
                ->value('id');
        }
        $companyId = (int) $brand->company_id;
        $brandId = $brand->id;
        $serviceId = (int) $request->validated('service_id');

        $service = Service::query()->where('brand_id', $brandId)->where('id', $serviceId)->firstOrFail();

        $currentCount = ReferrerBrand::query()->where('brand_id', $brandId)->count();
        $maxReferrers = $brand->maxReferrers();
        $canAdd = max(0, $maxReferrers - $currentCount);

        $recommendTo = $request->validated('recommend_to', []);
        $toCreate = array_slice($recommendTo, 0, $canAdd);

        $today = now()->toDateString();
        $referrerFirstName = $request->validated('referrer_first_name');
        $referrerSurname = $request->validated('referrer_surname');
        $referrerEmail = $request->validated('referrer_email');
        $referrerPhone = $request->validated('referrer_phone');

        foreach ($toCreate as $entry) {
            ReferrerBrand::query()->create([
                'user_id' => $userId,
                'company_id' => $companyId,
                'brand_id' => $brandId,
                'service_id' => $serviceId,
                'comission_type' => $service->commission_type->value,
                'comission' => $service->commission,
                'date' => $today,
                'referrer_first_name' => $referrerFirstName,
                'referrer_surname' => $referrerSurname,
                'referrer_email' => $referrerEmail,
                'referrer_phone' => $referrerPhone,
                'recommended_to_name' => $entry['name'],
                'recommended_to_surname' => $entry['surname'],
                'recommended_to_phone' => $entry['phone'],
                'recommended_to_email' => $entry['email'],
            ]);
        }

        $created = count($toCreate);
        if ($created > 0) {
            return back()->with('success', $created === 1
                ? 'Thank you — your referral has been sent.'
                : "Thank you — {$created} referrals have been sent.");
        }

        return back()->with('error', 'No referral slots available for this brand.');
    }

    private function brandLogoUrl(BrandReferral $brand): ?string
    {
        if (! $brand->logo) {
            return null;
        }

        $filename = basename($brand->logo);

        return asset('storage/brand-logos/'.$filename);
    }

    private function normalizeTermsUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return $url;
        }
        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            return 'https://'.$url;
        }

        return $url;
    }
}
