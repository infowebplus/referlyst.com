<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Schemas;

use App\Models\BrandReferral;
use App\Services\ColorContrast;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

class BrandReferralForm
{
    /** Subdomain slug only (e.g. my-brand), becomes subdomain.referlyst.test */
    private const SUBDOMAIN_PATTERN = '^[a-z0-9]+(?:-[a-z0-9]+)*$';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => auth()->user()?->company_id)
                    ->dehydrated(),
                Hidden::make('has_custom_branding')
                    ->default(false)
                    ->dehydrated(false),
                Hidden::make('has_api_access')
                    ->default(false)
                    ->dehydrated(false),
                Hidden::make('api_token_set')
                    ->default(false)
                    ->dehydrated(false),
                Hidden::make('api_access_expiry')
                    ->default(null)
                    ->dehydrated(false),
                Section::make('Brand details')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        FileUpload::make('logo')
                            ->disk('public')
                            ->directory('brand-logos')
                            ->image()
                            ->maxSize(1024)
                            ->rule('max:1024')
                            ->helperText('Image only, max 1MB.')
                            ->formatStateUsing(fn (?string $state) => $state && ! str_contains($state, '/') ? 'brand-logos/'.$state : $state)
                            ->visible(fn (Get $get): bool => (bool) $get('has_custom_branding')),
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
                        TextInput::make('correspondence_email')
                            ->label('Email for correspondence')
                            ->email()
                            ->placeholder('contact@brand.com')
                            ->helperText('Email used for correspondence related to this brand.'),
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
                            ->label(fn (Get $get): string => self::isPathMode($get) ? 'Path for landing pages' : 'Subdomain for landing pages')
                            ->placeholder('e.g. my-brand')
                            ->unique(
                                table: 'brands_referral',
                                column: 'subdomain_for_landing_pages',
                                ignoreRecord: true,
                            )
                            ->validationMessages([
                                'unique' => 'This subdomain is already taken by another brand.',
                            ])
                            ->hint(fn (?string $state, ?Model $record): ?string => self::subdomainAvailabilityHint($state, $record))
                            ->hintColor('success')
                            ->hintIcon(fn (?string $state, ?Model $record): ?string => self::subdomainAvailabilityHint($state, $record) !== null ? 'heroicon-m-check-circle' : null)
                            ->rules([
                                'nullable',
                                'string',
                                'max:255',
                                'regex:/'.self::SUBDOMAIN_PATTERN.'/',
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('subdomain_for_landing_pages', $state ? strtolower(preg_replace('/[^a-z0-9-]/', '-', $state)) : null))
                            ->suffix(fn (Get $get): HtmlString => self::landingPageSuffix($get)),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Brand colors')
                    ->description('Primary and secondary colors for your brand (Starter and Growth plans). Panel colors use the company\'s default brand. We check contrast on light and dark backgrounds (WCAG AA).')
                    ->schema(array_merge(
                        self::colorPickerAndCode('colors.primary', 'Primary colour', '#1858cb'),
                        self::colorPickerAndCode('colors.secondary', 'Secondary colour', '#0e3a8a'),
                        [
                            Select::make('landing_theme')
                                ->label('Landing page theme')
                                ->options([
                                    'light' => 'Light',
                                    'dark' => 'Dark',
                                ])
                                ->default('light')
                                ->helperText('Default theme for the public referral landing page.')
                                ->columnSpanFull(),
                        ]
                    ))
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => (bool) $get('has_custom_branding')),
                Section::make('API Access')
                    ->description('Authenticate requests with a Bearer token in the Authorization header.')
                    ->schema([
                        Placeholder::make('api_access_content')
                            ->hiddenLabel()
                            ->content(fn (Get $get): HtmlString => self::apiAccessView($get))
                            ->visible(fn (Get $get): bool => (bool) $get('has_api_access')),
                    ])
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => (bool) $get('has_api_access')),
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
            ]);
    }

    // ─── API Access Blade view ────────────────────────────────────────────────────

    /**
     * Render the API Access widget as a Blade component, passing all required
     * view data from the current form state.
     */
    private static function apiAccessView(Get $get): HtmlString
    {
        $base = rtrim(config('app.url'), '/');

        return new HtmlString(
            view('components.filament.api-access', [
                'tokens' => is_array($get('api_tokens_list')) ? $get('api_tokens_list') : [],
                'landingUrl' => self::apiAccessLandingUrlRaw($get),
                'landingUrlDisplay' => self::apiAccessLandingUrlPlaceholder($get),
                'expiry' => $get('api_access_expiry'),
                'docsUrl' => $base.'/docs/v1',
                'getUrl' => $base.'/api/v1/referral-form',
                'postUrl' => $base.'/api/v1/referrals',
            ])->render()
        );
    }

    // ─── Subdomain / landing-page helpers ────────────────────────────────────────

    private static function subdomainAvailabilityHint(?string $state, ?Model $record): ?string
    {
        if (! filled($state)) {
            return null;
        }

        $taken = BrandReferral::query()
            ->where('subdomain_for_landing_pages', $state)
            ->when($record?->exists, fn ($q) => $q->where('id', '!=', $record->id))
            ->exists();

        return $taken ? null : 'Available';
    }

    private static function isPathMode(?Get $get): bool
    {
        if ($get === null) {
            return true;
        }

        return ! (bool) $get('has_custom_branding');
    }

    private static function landingPageSuffix(Get $get): HtmlString
    {
        $slug = $get('subdomain_for_landing_pages');
        if (! is_string($slug) || trim($slug) === '') {
            return new HtmlString('');
        }
        $url = self::landingPageUrlForSlug(trim($slug), self::isPathMode($get));

        return new HtmlString(
            '<a href="'.e($url).'" target="_blank" rel="noopener noreferrer" class="fi-input-suffix-item flex items-center gap-x-1 rounded-md text-primary-600 outline-none transition duration-75 hover:text-primary-500 focus-visible:ring-2 focus-visible:ring-primary-500/50 dark:text-primary-400 dark:hover:text-primary-300 dark:focus-visible:ring-primary-400/50 cursor-pointer shrink-0" style="display:inline-flex;align-items:center;justify-content:center;min-width:1.5rem;min-height:1.5rem" title="View landing page" aria-label="View landing page" x-on:click.stop.prevent="window.open($event.currentTarget.href, \'_blank\')"><svg class="fi-input-suffix-icon h-4 w-4 shrink-0 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg></a>'
        );
    }

    private static function landingPageUrlForSlug(string $slug, bool $pathMode): string
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $host = parse_url($baseUrl, PHP_URL_HOST);
        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?? 'https';

        if ($pathMode) {
            return $baseUrl.'/'.ltrim($slug, '/');
        }

        return $scheme.'://'.$slug.'.'.$host;
    }

    private static function apiAccessLandingUrlPlaceholder(Get $get): string
    {
        $slug = $get('subdomain_for_landing_pages');
        if (! is_string($slug) || trim($slug) === '') {
            return '— Set subdomain/path above first.';
        }

        return self::landingPageUrlForSlug(trim($slug), self::isPathMode($get));
    }

    private static function apiAccessLandingUrlRaw(Get $get): ?string
    {
        $slug = $get('subdomain_for_landing_pages');
        if (! is_string($slug) || trim($slug) === '') {
            return null;
        }

        return self::landingPageUrlForSlug(trim($slug), self::isPathMode($get));
    }

    // ─── Reusable form components ─────────────────────────────────────────────────

    /**
     * Returns a [hex TextInput, color-picker TextInput] pair bound to the same state path.
     * Used for the brand colour section.
     *
     * @return list<TextInput>
     */
    private static function colorPickerAndCode(string $statePath, string $label, string $placeholder): array
    {
        $normalizeHex = function (?string $value): ?string {
            if (! filled($value) || ! preg_match('/^#?[a-fA-F0-9]{6}$/', trim($value))) {
                return $value;
            }
            $v = strtolower(trim($value));

            return str_starts_with($v, '#') ? $v : '#'.$v;
        };

        return [
            TextInput::make($statePath)
                ->label($label)
                ->placeholder($placeholder)
                ->rules(['nullable', 'string', 'regex:/^#?[a-fA-F0-9]{6}$/'])
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, callable $set) use ($statePath, $label, $normalizeHex): void {
                    $normalized = $normalizeHex($state);
                    $set($statePath, $normalized);
                    if ($normalized !== null && $normalized !== '') {
                        $message = ColorContrast::contrastMessage($normalized);
                        if ($message !== '') {
                            Notification::make()
                                ->warning()
                                ->title("{$label} may be hard to see")
                                ->body($message)
                                ->send();
                        }
                    }
                }),
            TextInput::make($statePath)
                ->label('Picker')
                ->type('color')
                ->live(onBlur: true),
        ];
    }

    // ─── URL utilities ────────────────────────────────────────────────────────────

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
