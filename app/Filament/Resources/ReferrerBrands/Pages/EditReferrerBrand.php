<?php

namespace App\Filament\Resources\ReferrerBrands\Pages;

use App\Filament\Resources\ReferrerBrands\ReferrerBrandResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditReferrerBrand extends EditRecord
{
    protected static string $resource = ReferrerBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
