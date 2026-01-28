<?php

namespace App\Filament\Resources\BrandReferrals\Pages;

use App\Filament\Resources\BrandReferrals\BrandReferralResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBrandReferral extends EditRecord
{
    protected static string $resource = BrandReferralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
