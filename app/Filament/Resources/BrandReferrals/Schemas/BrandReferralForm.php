<?php

namespace App\Filament\Resources\BrandReferrals\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandReferralForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                FileUpload::make('logo')
                    ->directory('brand-logos')
                    ->image()
                    ->maxSize(1024)
                    ->rule('max:1024')
                    ->helperText('Image only, max 1MB.')
                    ->formatStateUsing(fn (?string $state) => $state && ! str_contains($state, '/') ? 'brand-logos/'.$state : $state),
                TextInput::make('website')
                    ->url()
                    ->required(),
                TextInput::make('terms_and_conditions')
                    ->url()
                    ->label('T&C')
                    ->placeholder('Terms & Conditions link'),
                TextInput::make('ca')
                    ->url()
                    ->label('CA')
                    ->placeholder('CA link'),
                TextInput::make('subdomain_for_landing_pages')
                    ->unique(ignoreRecord: true),
                TextInput::make('smtp_host')
                    ->default(null),
                TextInput::make('smtp_port')
                    ->numeric()
                    ->default(null),
                TextInput::make('smtp_username')
                    ->default(null),
                TextInput::make('smtp_password')
                    ->password()
                    ->default(null),
                TextInput::make('smtp_security')
                    ->default(null),
                Textarea::make('colors')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
