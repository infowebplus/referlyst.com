<?php

namespace App\Filament\Resources\BrandReferrals\Pages;

use App\Filament\Resources\BrandReferrals\BrandReferralResource;
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
