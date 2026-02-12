<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Pages;

use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\BrandReferralResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBrandReferrals extends ListRecords
{
    protected static string $resource = BrandReferralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
