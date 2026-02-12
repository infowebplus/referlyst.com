<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Pages;

use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\ServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
