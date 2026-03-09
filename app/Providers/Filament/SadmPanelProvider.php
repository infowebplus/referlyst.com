<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Addons\AddonResource;
use App\Filament\Resources\BrandPackages\BrandPackageResource;
use App\Filament\Resources\BrandReferrals\BrandReferralResource;
use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\Orders\OrdersResource;
use App\Filament\Resources\Packages\PackageResource;
use App\Filament\Resources\ReferrerBrands\ReferrerBrandResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\OrdersCountChart;
use App\Filament\Widgets\OrdersRevenueChart;
use App\Filament\Widgets\RecentOrdersWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SadmPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('sadm')
            ->path('sadm')
            ->login()
            ->passwordReset()
            ->favicon(asset('images/referlyst-icon.png'))
            ->colors([
                'primary' => '#0acd5a',
            ])
            ->resources([
                AddonResource::class,
                BrandPackageResource::class,
                BrandReferralResource::class,
                CompanyResource::class,
                OrdersResource::class,
                PackageResource::class,
                ReferrerBrandResource::class,
                UserResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                RecentOrdersWidget::class,
                OrdersCountChart::class,
                OrdersRevenueChart::class,
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
