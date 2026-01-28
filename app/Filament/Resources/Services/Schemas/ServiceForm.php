<?php

namespace App\Filament\Resources\Services\Schemas;

use App\CommissionType;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->columnSpan(2),
                Select::make('commission_type')
                    ->options(CommissionType::class)
                    ->required(),
                TextInput::make('commission')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                RichEditor::make('texts')
                    ->columnSpanFull(),
                Textarea::make('pictures')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
