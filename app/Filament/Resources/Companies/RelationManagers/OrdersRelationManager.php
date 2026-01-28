<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use App\Filament\Resources\Orders\Schemas\OrdersForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Schema $schema): Schema
    {
        return OrdersForm::configure($schema)
            ->modifyComponent('company_id', fn ($component) => $component->hidden())
            ->mutateFormDataUsing(function (array $data): array {
                $data['company_id'] = $this->getOwnerRecord()->id;

                return $data;
            });
    }

    public function table(Table $table): Table
    {
        return OrdersTable::configure($table)
            ->recordTitleAttribute('id')
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
