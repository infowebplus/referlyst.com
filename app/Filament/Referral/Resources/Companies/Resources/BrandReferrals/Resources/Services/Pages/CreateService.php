<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Pages;

use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $parent = $this->getParentRecord();
        if ($parent) {
            $data['brand_id'] = $parent->getKey();
        }

        return $data;
    }
}
