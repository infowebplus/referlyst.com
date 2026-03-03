<?php

namespace App\Http\Controllers;

use App\Http\Requests\LandingReferralSubmitRequest;
use App\Models\BrandReferral;
use App\Models\ReferrerBrand;
use App\Models\Service;
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

        $maxReferrers = $activePackage?->no_of_referrers ?? 0;
        $currentCount = ReferrerBrand::query()->where('brand_id', $brand->id)->count();
        $slotsRemaining = max(0, $maxReferrers - $currentCount);

        return view('landing.show', [
            'brand' => $brand,
            'activePackage' => $activePackage,
            'services' => $services,
            'maxReferrers' => $maxReferrers,
            'slotsRemaining' => $slotsRemaining,
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

        $userId = (int) session('landing_user_id', auth()->id());
        $companyId = (int) $brand->company_id;
        $brandId = $brand->id;
        $serviceId = (int) $request->validated('service_id');

        $service = Service::query()->where('brand_id', $brandId)->where('id', $serviceId)->firstOrFail();

        $currentCount = ReferrerBrand::query()->where('brand_id', $brandId)->count();
        $maxReferrers = $activePackage->no_of_referrers;
        $canAdd = max(0, $maxReferrers - $currentCount);

        $recommendTo = $request->validated('recommend_to', []);
        $toCreate = array_slice($recommendTo, 0, $canAdd);

        $today = now()->toDateString();

        foreach ($toCreate as $entry) {
            ReferrerBrand::query()->create([
                'user_id' => $userId,
                'company_id' => $companyId,
                'brand_id' => $brandId,
                'service_id' => $serviceId,
                'comission_type' => $service->commission_type->value,
                'comission' => $service->commission,
                'date' => $today,
                'recommended_to_name' => $entry['name'],
                'recommended_to_surname' => $entry['surname'],
                'recommended_to_phone' => $entry['phone'],
                'recommended_to_email' => $entry['email'],
            ]);
        }

        $created = count($toCreate);
        if ($created > 0) {
            return back()->with('success', $created === 1
                ? 'One referral has been submitted successfully.'
                : "{$created} referrals have been submitted successfully.");
        }

        return back()->with('error', 'No referral slots available for this brand.');
    }
}
