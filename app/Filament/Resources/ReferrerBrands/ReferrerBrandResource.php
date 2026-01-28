<?php

namespace App\Filament\Resources\ReferrerBrands;

use App\Filament\Resources\ReferrerBrands\Pages\CreateReferrerBrand;
use App\Filament\Resources\ReferrerBrands\Pages\EditReferrerBrand;
use App\Filament\Resources\ReferrerBrands\Pages\ListReferrerBrands;
use App\Filament\Resources\ReferrerBrands\Schemas\ReferrerBrandForm;
use App\Filament\Resources\ReferrerBrands\Tables\ReferrerBrandsTable;
use App\Models\ReferrerBrand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ReferrerBrandResource extends Resource
{
    protected static ?string $model = ReferrerBrand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static UnitEnum|string|null $navigationGroup = 'Client data';

    protected static ?string $recordTitleAttribute = 'RecommendedToName';

    public static function form(Schema $schema): Schema
    {
        return ReferrerBrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReferrerBrandsTable::configure($table);
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
            'index' => ListReferrerBrands::route('/'),
            'create' => CreateReferrerBrand::route('/create'),
            'edit' => EditReferrerBrand::route('/{record}/edit'),
        ];
    }
}
