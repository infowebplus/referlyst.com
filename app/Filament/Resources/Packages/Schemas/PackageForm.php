<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->helperText('Used in URLs (e.g. free, starter, growth). Leave empty to auto-generate from name.'),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->minValue(0),
                        RichEditor::make('description')
                            ->default('')
                            ->columnSpanFull(),
                        TextInput::make('no_of_referrers')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Use 0 for unlimited.'),
                        TextInput::make('no_of_referrers_brands')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Use 0 for unlimited.'),
                        Toggle::make('default_package')
                            ->helperText('Default package for new signups.'),
                        Toggle::make('email_notifications'),
                        Toggle::make('commission_tracking'),
                        Toggle::make('export_csv'),
                        Toggle::make('custom_branding'),
                        Toggle::make('custom_landing_page')
                            ->helperText('API Access (e.g. Growth plan).'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Pricing page (marketing)')
                    ->description('Text and display options for the public pricing page.')
                    ->schema([
                        TextInput::make('short_description')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Short tagline shown under the price on the pricing card.'),
                        TextInput::make('domain_structure')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('E.g. referlyst.com/brand, company.referlyst.com, or Custom domain.'),
                        TextInput::make('badge')
                            ->maxLength(255)
                            ->helperText('Optional badge above the card (e.g. Most Popular). Leave empty for no badge.'),
                        TextInput::make('cta_label')
                            ->maxLength(255)
                            ->default('Choose plan')
                            ->required()
                            ->helperText('Button label on the pricing card (e.g. Choose plan, Start for free).'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Order on pricing page (lower first).'),
                        Textarea::make('limitations')
                            ->columnSpanFull()
                            ->rows(4)
                            ->helperText('Optional limitations shown on the pricing card. One per line. Leave empty for no limitations section.')
                            ->formatStateUsing(fn ($state): string => is_array($state) ? implode("\n", $state) : (string) $state)
                            ->dehydrateStateUsing(function ($state): array {
                                if (is_array($state)) {
                                    return array_values(array_filter(array_map('trim', $state)));
                                }

                                return array_values(array_filter(array_map('trim', explode("\n", (string) $state))));
                            }),
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
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
