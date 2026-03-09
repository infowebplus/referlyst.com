<?php

namespace App\Providers\Filament;

use App\Services\ReferralBrandColors;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ReferralPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('referral')
            ->path('referral')
            ->login()
            ->passwordReset()
            ->registration(\App\Filament\Referral\Auth\Register::class)
            ->emailVerification()
            ->favicon(asset('images/referlyst-icon.png'))
            ->colors([
                'primary' => ReferralBrandColors::REFERLYST_PRIMARY,
            ])
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => view('filament.referral.styles-brand-colors', [
                    'primary' => ReferralBrandColors::primaryForReferralPanel(),
                    'menuBg' => ReferralBrandColors::menuBackgroundForPanel(),
                ])->render()
            )
            ->discoverResources(in: app_path('Filament/Referral/Resources'), for: 'App\\Filament\\Referral\\Resources')
            ->discoverPages(in: app_path('Filament/Referral/Pages'), for: 'App\\Filament\\Referral\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Referral/Widgets'), for: 'App\\Filament\\Referral\\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
