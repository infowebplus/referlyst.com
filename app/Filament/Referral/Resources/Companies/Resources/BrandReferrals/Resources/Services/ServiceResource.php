<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services;

use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\BrandReferralResource;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Pages\CreateService;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Pages\EditService;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Pages\ListServices;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Schemas\ServiceForm;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\ParentResourceRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    public static function getParentResourceRegistration(): ?ParentResourceRegistration
    {
        return BrandReferralResource::asParent(static::class)
            ->relationship('services')
            ->inverseRelationship('brandReferral');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Service';

    protected static ?string $pluralModelLabel = 'Services';

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
