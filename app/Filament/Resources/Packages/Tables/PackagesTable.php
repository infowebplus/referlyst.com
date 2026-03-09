<?php

namespace App\Filament\Resources\Packages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('no_of_referrers_brands')
                    ->label('No of referrers brands')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state > 0 ? (string) $state : '∞'),
                TextColumn::make('no_of_referrers')
                    ->label('No of referrers')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state > 0 ? (string) $state : '∞'),
                IconColumn::make('custom_branding')
                    ->boolean()
                    ->sortable()
                    ->label('Custom branding'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
