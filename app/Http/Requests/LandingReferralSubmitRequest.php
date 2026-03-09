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
        return (bool) session('landing_brand_id');
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $brandId = (int) session('landing_brand_id');
        $brand = BrandReferral::query()->find($brandId);
        $maxReferrers = $brand?->maxReferrers() ?? 0;
        $currentCount = ReferrerBrand::query()->where('brand_id', $brandId)->count();
        $slotsRemaining = max(0, $maxReferrers - $currentCount);

        $serviceIds = $brand
            ? $brand->services()->pluck('id')->map(fn ($id) => (string) $id)->all()
            : [];

        $hasTerms = $brand && $brand->terms_and_conditions;

        return [
            'service_id' => [
                'required',
                Rule::in($serviceIds),
            ],
            'referrer_first_name' => ['required', 'string', 'max:255'],
            'referrer_surname' => ['required', 'string', 'max:255'],
            'referrer_email' => ['required', 'email', 'max:255'],
            'referrer_phone' => ['nullable', 'string', 'max:255'],
            'terms_accepted' => $hasTerms ? ['accepted'] : ['nullable'],
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
            'referrer_first_name.required' => 'Your first name is required.',
            'referrer_surname.required' => 'Your last name is required.',
            'referrer_email.required' => 'Your email is required.',
            'referrer_email.email' => 'Please enter a valid email address for your contact details.',
            'terms_accepted.accepted' => 'You must accept the terms & conditions to continue.',
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
