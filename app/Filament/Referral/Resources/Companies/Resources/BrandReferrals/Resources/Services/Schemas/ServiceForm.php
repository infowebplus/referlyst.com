<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Resources\Services\Schemas;

use App\CommissionType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service details')
                    ->schema([
                        Hidden::make('brand_id')->dehydrated(),
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
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
