<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use App\Filament\Resources\ReferrerBrands\Schemas\ReferrerBrandForm;
use App\Filament\Resources\ReferrerBrands\Tables\ReferrerBrandsTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ReferrerBrandsRelationManager extends RelationManager
{
    protected static string $relationship = 'referrerBrands';

    public function form(Schema $schema): Schema
    {
        return ReferrerBrandForm::configure($schema)
            ->modifyComponent('company_id', fn ($component) => $component->hidden())
            ->mutateFormDataUsing(function (array $data): array {
                $data['company_id'] = $this->getOwnerRecord()->id;

                return $data;
            });
    }

    public function table(Table $table): Table
    {
        return ReferrerBrandsTable::configure($table)
            ->recordTitleAttribute('recommended_to_name')
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
