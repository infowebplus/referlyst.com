<?php

namespace App\Http\Requests;

use App\Models\BrandReferral;
use App\Models\ReferrerBrand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LandingReferralSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        $brandId = session('landing_brand_id');

        return $this->user() && $brandId;
    }

    protected function failedAuthorization(): void
    {
        throw new \Illuminate\Auth\AuthenticationException(
            'Please log in to submit referrals.',
            [],
            $this->redirectToLogin()
        );
    }

    protected function redirectToLogin(): string
    {
        return url('/referral/login');
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $brandId = (int) session('landing_brand_id');
        $brand = BrandReferral::query()->find($brandId);
        $activePackage = $brand?->active_package;
        $maxReferrers = $activePackage?->no_of_referrers ?? 0;
        $currentCount = ReferrerBrand::query()->where('brand_id', $brandId)->count();
        $slotsRemaining = max(0, $maxReferrers - $currentCount);

        $serviceIds = $brand
            ? $brand->services()->pluck('id')->map(fn ($id) => (string) $id)->all()
            : [];

        return [
            'service_id' => [
                'required',
                Rule::in($serviceIds),
            ],
            'recommend_to' => ['required', 'array', 'min:1', 'max:'.$slotsRemaining],
            'recommend_to.*.name' => ['required', 'string', 'max:255'],
            'recommend_to.*.surname' => ['required', 'string', 'max:255'],
            'recommend_to.*.phone' => ['required', 'string', 'max:255'],
            'recommend_to.*.email' => ['required', 'email'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service_id.required' => 'Please select a service.',
            'service_id.in' => 'The selected service is not valid for this brand.',
            'recommend_to.required' => 'Please add at least one person to recommend.',
            'recommend_to.max' => 'You cannot add more referrals than your package allows.',
            'recommend_to.*.name.required' => 'Name is required.',
            'recommend_to.*.surname.required' => 'Surname is required.',
            'recommend_to.*.phone.required' => 'Phone is required.',
            'recommend_to.*.email.required' => 'Email is required.',
            'recommend_to.*.email.email' => 'Please enter a valid email address.',
        ];
    }
}
