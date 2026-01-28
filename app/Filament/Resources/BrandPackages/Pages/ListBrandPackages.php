<?php

namespace App\Filament\Resources\BrandPackages\Pages;

use App\Filament\Resources\BrandPackages\BrandPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBrandPackages extends ListRecords
{
    protected static string $resource = BrandPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
