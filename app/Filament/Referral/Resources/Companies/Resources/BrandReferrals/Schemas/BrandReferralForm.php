<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;

class BrandReferralForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => auth()->user()?->company_id)
                    ->dehydrated(),
                Section::make('Brand details')
                    ->schema([
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
                            ->prefix('https://')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                $normalizedUrl = self::normalizeUrl($state);

                                if (! filled($normalizedUrl)) {
                                    return;
                                }

                                $set('website', $normalizedUrl);

                                if (! self::urlReturnsHttp200($normalizedUrl)) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Website link is not reachable (HTTP 200 not returned).')
                                        ->send();
                                }
                            })
                            ->required(),
                        TextInput::make('terms_and_conditions')
                            ->url()
                            ->label('T&C')
                            ->prefix('https://')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                $normalizedUrl = self::normalizeUrl($state);

                                if (! filled($normalizedUrl)) {
                                    return;
                                }

                                $set('terms_and_conditions', $normalizedUrl);

                                if (! self::urlReturnsHttp200($normalizedUrl)) {
                                    Notification::make()
                                        ->danger()
                                        ->title('T&C link is not reachable (HTTP 200 not returned).')
                                        ->send();
                                }
                            })
                            ->placeholder('Terms & Conditions link'),
                        TextInput::make('ca')
                            ->url()
                            ->label('CA')
                            ->prefix('https://')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                $normalizedUrl = self::normalizeUrl($state);

                                if (! filled($normalizedUrl)) {
                                    return;
                                }

                                $set('ca', $normalizedUrl);

                                if (! self::urlReturnsHttp200($normalizedUrl)) {
                                    Notification::make()
                                        ->danger()
                                        ->title('CA link is not reachable (HTTP 200 not returned).')
                                        ->send();
                                }
                            })
                            ->placeholder('CA link'),
                        TextInput::make('subdomain_for_landing_pages')
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('SMTP settings')
                    ->schema([
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
                        Select::make('smtp_security')
                            ->options([
                                'TSL' => 'TSL',
                                'SSL' => 'SSL',
                            ])
                            ->default('SSL'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Textarea::make('colors')
                    ->hidden()
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }

    private static function normalizeUrl(?string $url): ?string
    {
        if (! filled($url)) {
            return null;
        }

        $trimmedUrl = trim($url);

        if (! str_starts_with($trimmedUrl, 'http://') && ! str_starts_with($trimmedUrl, 'https://')) {
            return 'https://'.$trimmedUrl;
        }

        return $trimmedUrl;
    }

    private static function urlReturnsHttp200(string $url): bool
    {
        try {
            $headResponse = Http::timeout(5)->head($url);

            if ($headResponse->status() === 200) {
                return true;
            }

            $getResponse = Http::timeout(5)->get($url);

            return $getResponse->status() === 200;
        } catch (\Throwable) {
            return false;
        }
    }
}
