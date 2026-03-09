<?php

namespace App\Filament\Referral\Resources\Companies\RelationManagers;

use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\BrandReferralResource;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Schemas\BrandReferralForm;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Tables\BrandReferralsTable;
use App\Models\BrandReferral;
use App\Models\Package;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class BrandReferralsRelationManager extends RelationManager
{
    protected static string $relationship = 'brandReferrals';

    protected static ?string $relatedResource = BrandReferralResource::class;

    public function form(Schema $schema): Schema
    {
        return BrandReferralForm::configure($schema)
            ->modifyComponent('company_id', fn ($component) => $component->hidden())
            ->mutateFormDataUsing(function (array $data): array {
                $data['company_id'] = $this->getOwnerRecord()->id;

                return $data;
            });
    }

    public function table(Table $table): Table
    {
        return BrandReferralsTable::configure($table)
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, BrandReferral $record): array {
                        $package = $record->active_package ?? Package::query()->where('default_package', true)->first();
                        $data['has_custom_branding'] = $package?->custom_branding ?? false;

                        return $data;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
