<?php

namespace App\Filament\Resources\BrandReferrals;

use App\Filament\Resources\BrandReferrals\Pages\CreateBrandReferral;
use App\Filament\Resources\BrandReferrals\Pages\EditBrandReferral;
use App\Filament\Resources\BrandReferrals\Pages\ListBrandReferrals;
use App\Filament\Resources\BrandReferrals\Schemas\BrandReferralForm;
use App\Filament\Resources\BrandReferrals\Tables\BrandReferralsTable;
use App\Models\BrandReferral;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BrandReferralResource extends Resource
{
    protected static ?string $model = BrandReferral::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static UnitEnum|string|null $navigationGroup = 'Client data';

    public static function form(Schema $schema): Schema
    {
        return BrandReferralForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandReferralsTable::configure($table);
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
            'index' => ListBrandReferrals::route('/'),
            'create' => CreateBrandReferral::route('/create'),
            'edit' => EditBrandReferral::route('/{record}/edit'),
        ];
    }
}
