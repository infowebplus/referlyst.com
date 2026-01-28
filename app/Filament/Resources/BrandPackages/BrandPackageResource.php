<?php

namespace App\Filament\Resources\BrandPackages;

use App\Filament\Resources\BrandPackages\Pages\CreateBrandPackage;
use App\Filament\Resources\BrandPackages\Pages\EditBrandPackage;
use App\Filament\Resources\BrandPackages\Pages\ListBrandPackages;
use App\Filament\Resources\BrandPackages\Schemas\BrandPackageForm;
use App\Filament\Resources\BrandPackages\Tables\BrandPackagesTable;
use App\Models\BrandPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BrandPackageResource extends Resource
{
    protected static ?string $model = BrandPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static UnitEnum|string|null $navigationGroup = 'Client data';

    public static function form(Schema $schema): Schema
    {
        return BrandPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandPackagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrandPackages::route('/'),
            'create' => CreateBrandPackage::route('/create'),
            'edit' => EditBrandPackage::route('/{record}/edit'),
        ];
    }
}
