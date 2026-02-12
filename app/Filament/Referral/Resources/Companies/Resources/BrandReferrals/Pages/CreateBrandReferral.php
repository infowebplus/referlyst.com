<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Pages;

use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\BrandReferralResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBrandReferral extends CreateRecord
{
    protected static string $resource = BrandReferralResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $parent = $this->getParentRecord();
        if ($parent) {
            $data['company_id'] = $parent->getKey();
        }

        return $data;
    }
}
