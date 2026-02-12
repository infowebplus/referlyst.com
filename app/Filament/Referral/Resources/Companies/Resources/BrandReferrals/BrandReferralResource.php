<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals;

use App\Filament\Referral\Resources\Companies\CompanyResource;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Pages\CreateBrandReferral;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Pages\EditBrandReferral;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Pages\ListBrandReferrals;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\RelationManagers\ServicesRelationManager;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Schemas\BrandReferralForm;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Tables\BrandReferralsTable;
use App\Models\BrandReferral;
use BackedEnum;
use Filament\Resources\ParentResourceRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BrandReferralResource extends Resource
{
    protected static ?string $model = BrandReferral::class;

    public static function getParentResourceRegistration(): ?ParentResourceRegistration
    {
        return CompanyResource::asParent(static::class)
            ->relationship('brandReferrals')
            ->inverseRelationship('company');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Brand';

    protected static ?string $pluralModelLabel = 'Brands';

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
            'services' => ServicesRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $companyId = auth()->user()?->company_id;

        return parent::getEloquentQuery()
            ->when($companyId, fn (Builder $q) => $q->where('company_id', $companyId))
            ->when(! $companyId, fn (Builder $q) => $q->whereRaw('1 = 0'));
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
