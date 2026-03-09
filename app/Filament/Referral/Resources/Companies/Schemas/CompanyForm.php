<?php

namespace App\Filament\Referral\Resources\Companies\Schemas;

use App\Services\ReferralBrandColors;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company Details')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Company name')
                            ->required(),
                        Select::make('default_brand_id')
                            ->label('Default brand')
                            ->helperText('Panel colors are taken from this brand’s Brand colors. First brand is the default.')
                            ->options(fn (): array => auth()->user()?->company?->brandReferrals()->orderBy('id')->pluck('name', 'id')->all() ?? [])
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ]),
                Section::make('Payment / Billing details')
                    ->description('Legal and billing data used for invoices and payments.')
                    ->schema([
                        TextInput::make('legal_name')
                            ->label('Legal name')
                            ->placeholder('Denumire legală / Company legal name'),
                        TextInput::make('registration_number')
                            ->label('Registration number')
                            ->placeholder('CUI / Company reg. no.'),
                        TextInput::make('vat_number')
                            ->label('VAT number')
                            ->placeholder('CIF / VAT ID'),
                        TextInput::make('billing_email')
                            ->label('Billing email')
                            ->email()
                            ->placeholder('billing@company.com'),
                        TextInput::make('billing_address')
                            ->label('Billing address')
                            ->placeholder('Street, number'),
                        TextInput::make('billing_city')
                            ->label('City'),
                        TextInput::make('billing_postal_code')
                            ->label('Postal code'),
                        TextInput::make('billing_country')
                            ->label('Country'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Company colors (admin panel)')
                    ->description('Main and secondary colours. The referral panel theme (primary and sidebar) is based on these. No contrast or brand rules apply.')
                    ->schema([
                        TextInput::make('admin_menu_colors.main')
                            ->label('Main colour')
                            ->placeholder(ReferralBrandColors::REFERLYST_PRIMARY)
                            ->default(ReferralBrandColors::REFERLYST_PRIMARY)
                            ->rules(['nullable', 'string', 'regex:/^#?[a-fA-F0-9]{6}$/'])
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => self::normalizeHexAndSet($state, $set, 'admin_menu_colors.main')),
                        TextInput::make('admin_menu_colors.main')
                            ->label('Picker')
                            ->type('color')
                            ->default(ReferralBrandColors::REFERLYST_PRIMARY)
                            ->live(onBlur: true),
                        TextInput::make('admin_menu_colors.secondary')
                            ->label('Secondary colour')
                            ->placeholder(ReferralBrandColors::DEFAULT_MENU_BG)
                            ->default(ReferralBrandColors::DEFAULT_MENU_BG)
                            ->rules(['nullable', 'string', 'regex:/^#?[a-fA-F0-9]{6}$/'])
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => self::normalizeHexAndSet($state, $set, 'admin_menu_colors.secondary')),
                        TextInput::make('admin_menu_colors.secondary')
                            ->label('Picker')
                            ->type('color')
                            ->default(ReferralBrandColors::DEFAULT_MENU_BG)
                            ->live(onBlur: true),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    private static function normalizeHexAndSet(?string $state, callable $set, string $path): void
    {
        if (! filled($state) || ! preg_match('/^#?[a-fA-F0-9]{6}$/', trim($state))) {
            return;
        }
        $v = strtolower(trim($state));
        $set($path, str_starts_with($v, '#') ? $v : '#'.$v);
    }
}
