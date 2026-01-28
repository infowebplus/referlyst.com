<?php

namespace App\Filament\Resources\ReferrerBrands\Pages;

use App\Filament\Resources\ReferrerBrands\ReferrerBrandResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReferrerBrands extends ListRecords
{
    protected static string $resource = ReferrerBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
