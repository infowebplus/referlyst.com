<?php

namespace App\Filament\Resources\BrandPackages\Pages;

use App\Filament\Resources\BrandPackages\BrandPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBrandPackage extends EditRecord
{
    protected static string $resource = BrandPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
