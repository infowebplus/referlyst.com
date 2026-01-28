<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('no_of_referrers')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('no_of_referrers_brands')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('email_notifications'),
                Toggle::make('commission_tracking'),
                Toggle::make('export_csv'),
                Toggle::make('custom_branding'),
                Select::make('addons')
                    ->relationship(
                        name: 'addons',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->orderBy('name')
                    )
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }
}
