<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BrandReferral;
use App\Models\ReferrerBrand;
use App\Models\Service;
use App\Models\User;
use App\Services\ReferralBrandColors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferralFormApiController extends Controller
{
    /**
     * Return all landing page data for the authenticated brand (Bearer token).
     */
    public function show(Request $request): JsonResponse
    {
        /** @var BrandReferral $brand */
        $brand = $request->attributes->get('api_brand');

        $activePackage = $brand->active_package;
        $services = $brand->services()->orderBy('name')->get();
        $maxReferrers = $brand->maxReferrers();
        $currentCount = ReferrerBrand::query()->where('brand_id', $brand->id)->count();
        $slotsRemaining = max(0, $maxReferrers - $currentCount);

        $baseUrl = rtrim(config('app.url'), '/');
        $host = parse_url($baseUrl, PHP_URL_HOST);
        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?? 'https';
        $pathMode = ! ($activePackage?->custom_branding ?? false);
        $slug = $brand->subdomain_for_landing_pages ?? '';
        $landingPageUrl = $pathMode && $slug
            ? $baseUrl.'/'.ltrim($slug, '/')
            : ($slug ? $scheme.'://'.$slug.'.'.$host : '');

        return response()->json([
            'brand' => [
                'id' => $brand->id,
                'name' => $brand->name,
                'website' => $brand->website,
                'terms_and_conditions' => $brand->terms_and_conditions,
                'ca' => $brand->ca,
                'landing_page_url' => $landingPageUrl,
                'primary_color' => ReferralBrandColors::primaryForReferrer($brand),
            ],
            'services' => $services->map(fn (Service $s) => [
                'id' => $s->id,
                'name' => $s->name,
                'commission_type' => $s->commission_type?->value,
                'commission' => $s->commission !== null ? (float) $s->commission : null,
                'texts' => $s->texts,
            ])->values()->all(),
            'max_referrers' => $maxReferrers,
            'slots_remaining' => $slotsRemaining,
            'has_package' => $activePackage !== null,
            'submit_url' => url('/api/v1/referrals'),
        ]);
    }

    /**
     * Submit referrals for the authenticated brand (Bearer token).
     */
    public function submit(Request $request): JsonResponse
    {
        /** @var BrandReferral $brand */
        $brand = $request->attributes->get('api_brand');

        $activePackage = $brand->active_package;
        if (! $activePackage) {
            return response()->json(['message' => 'There is no package allocated to this brand.'], 422);
        }

        $maxReferrers = $brand->maxReferrers();
        $currentCount = ReferrerBrand::query()->where('brand_id', $brand->id)->count();
        $slotsRemaining = max(0, $maxReferrers - $currentCount);
        $serviceIds = $brand->services()->pluck('id')->map(fn ($id) => (string) $id)->all();

        $validator = Validator::make($request->all(), [
            'service_id' => ['required', 'integer', 'in:'.implode(',', array_map('intval', $serviceIds))],
            'recommend_to' => ['required', 'array', 'min:1', 'max:'.$slotsRemaining],
            'recommend_to.*.name' => ['required', 'string', 'max:255'],
            'recommend_to.*.surname' => ['required', 'string', 'max:255'],
            'recommend_to.*.phone' => ['required', 'string', 'max:255'],
            'recommend_to.*.email' => ['required', 'email'],
        ], [
            'service_id.in' => 'The selected service is not valid for this brand.',
            'recommend_to.max' => 'You cannot add more referrals than your package allows.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $serviceId = (int) $data['service_id'];
        $service = Service::query()->where('brand_id', $brand->id)->where('id', $serviceId)->firstOrFail();
        $recommendTo = $data['recommend_to'];
        $canAdd = max(0, $maxReferrers - $currentCount);
        $toCreate = array_slice($recommendTo, 0, (int) $canAdd);

        $userId = User::query()->where('company_id', $brand->company_id)->value('id');
        if (! $userId) {
            return response()->json(['message' => 'No user found for this brand\'s company.'], 422);
        }

        $today = now()->toDateString();
        foreach ($toCreate as $entry) {
            ReferrerBrand::query()->create([
                'user_id' => $userId,
                'company_id' => $brand->company_id,
                'brand_id' => $brand->id,
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

        return response()->json([
            'message' => $created === 1
                ? 'One referral has been submitted successfully.'
                : "{$created} referrals have been submitted successfully.",
            'created' => $created,
        ], 201);
    }
}
