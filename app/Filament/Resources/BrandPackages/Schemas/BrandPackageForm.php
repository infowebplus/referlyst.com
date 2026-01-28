<?php

namespace App\Filament\Resources\BrandPackages\Schemas;

use App\Models\Addon;
use App\Models\Package;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('brand_id')
                    ->relationship('brandReferral', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Brand'),
                Select::make('model_type')
                    ->options([
                        Package::class => 'Package',
                        Addon::class => 'Addon',
                    ])
                    ->required()
                    ->label('Type')
                    ->live()
                    ->afterStateUpdated(fn ($state, $set) => $set('model_id', null)),
                Select::make('model_id')
                    ->label('Package/Addon')
                    ->options(function ($get) {
                        $modelType = $get('model_type');
                        if (! $modelType) {
                            return [];
                        }

                        return $modelType::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->required()
                    ->visible(fn ($get) => filled($get('model_type'))),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('EUR')
                    ->step(0.01),
                DatePicker::make('purchase_date')
                    ->required()
                    ->native(false),
                DatePicker::make('expiry_date')
                    ->native(false),
                Textarea::make('details')
                    ->default(null)
                    ->helperText('Enter JSON data or leave blank')
                    ->columnSpanFull(),
            ]);
    }
}
