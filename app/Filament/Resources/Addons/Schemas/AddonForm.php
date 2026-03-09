<?php

namespace App\Filament\Resources\Addons\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AddonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('€'),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('validity_period')
                    ->options([
                        'annual' => 'Annual',
                    ])
                    ->default('annual')
                    ->required()
                    ->helperText('Addons are sold with annual validity.'),
            ]);
    }
}
