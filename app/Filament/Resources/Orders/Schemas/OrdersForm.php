<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Addon;
use App\Models\Package;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrdersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} {$record->surname} ({$record->email})")
                    ->searchable(['name', 'surname', 'email'])
                    ->preload()
                    ->required()
                    ->label('User'),
                Select::make('company_id')
                    ->relationship('company', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Company'),
                Select::make('brand_id')
                    ->relationship('brand', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Brand'),
                DatePicker::make('date')
                    ->required()
                    ->native(false)
                    ->default(now()),
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
                Toggle::make('paid')
                    ->default(false)
                    ->label('Paid'),
                DatePicker::make('paid_date')
                    ->native(false)
                    ->visible(fn ($get) => $get('paid')),
                Textarea::make('paid_response')
                    ->label('Paid Response')
                    ->rows(3)
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('paid')),
            ]);
    }
}
