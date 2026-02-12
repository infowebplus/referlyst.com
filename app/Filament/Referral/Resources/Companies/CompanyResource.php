<?php

namespace App\Filament\Referral\Resources\Companies;

use App\Filament\Referral\Resources\Companies\Pages\CreateCompany;
use App\Filament\Referral\Resources\Companies\Pages\EditCompany;
use App\Filament\Referral\Resources\Companies\Pages\ListCompanies;
use App\Filament\Referral\Resources\Companies\RelationManagers\BrandReferralsRelationManager;
use App\Filament\Referral\Resources\Companies\Schemas\CompanyForm;
use App\Filament\Referral\Resources\Companies\Tables\CompaniesTable;
use App\Models\Company;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'My Company';

    protected static ?string $modelLabel = 'Company';

    public static function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompaniesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'brandReferrals' => BrandReferralsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $companyId = auth()->user()?->company_id;

        return parent::getEloquentQuery()
            ->when($companyId, fn (Builder $q) => $q->where('id', $companyId))
            ->when(! $companyId, fn (Builder $q) => $q->whereRaw('1 = 0'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return ! auth()->user()?->company_id;
    }
}
