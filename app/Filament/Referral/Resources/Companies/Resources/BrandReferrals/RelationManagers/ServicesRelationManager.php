<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\RelationManagers;

use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Schemas\ServiceForm;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\ServiceResource;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Tables\ServicesTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';

    protected static ?string $relatedResource = ServiceResource::class;

    public function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema)
            ->mutateFormDataUsing(function (array $data): array {
                $data['brand_id'] = $this->getOwnerRecord()->getKey();

                return $data;
            });
    }

    public function table(Table $table): Table
    {
        return ServicesTable::configure($table)
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
