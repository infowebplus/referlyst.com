<?php

namespace App\Filament\Resources\ReferrerBrands\Schemas;

use App\CommissionType;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReferrerBrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->name} {$record->surname}")
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('brand_id')
                    ->relationship('brandReferral', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('service_id')
                    ->relationship('service', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('comission_type')
                    ->options(CommissionType::class)
                    ->required(),
                TextInput::make('comission')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                DatePicker::make('date')
                    ->required()
                    ->native(false),
                TextInput::make('recommended_to_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('recommended_to_surname')
                    ->required()
                    ->maxLength(255),
                TextInput::make('recommended_to_phone')
                    ->required()
                    ->maxLength(255),
                TextInput::make('recommended_to_email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Toggle::make('paid')
                    ->default(false),
                TextInput::make('paid_sum')
                    ->numeric()
                    ->minValue(0)
                    ->nullable(),
            ]);
    }
}
