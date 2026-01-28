<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('surname')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->helperText(fn (string $operation): ?string => $operation === 'edit' ? 'Leave blank to keep the current password.' : null),
                Select::make('type')
                    ->options([
                        'admin' => 'Admin',
                        'referral' => 'Referral',
                        'referrer' => 'Referrer',
                    ])
                    ->required()
                    ->live(),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(false)
                    ->required(fn ($get): bool => $get('type') !== 'admin')
                    ->visible(fn ($get): bool => $get('type') !== 'admin')
                    ->dehydrateStateUsing(fn ($state, $get) => $get('type') === 'admin' ? null : $state),
            ]);
    }
}
