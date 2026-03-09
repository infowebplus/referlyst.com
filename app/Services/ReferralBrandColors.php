<?php

namespace App\Services;

use App\Models\BrandReferral;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;

class ReferralBrandColors
{
    /**
     * Referlyst default primary color (referral panel and referrer UI).
     */
    public const REFERLYST_PRIMARY = '#1858cb';

    /**
     * Default admin menu background (referral panel sidebar).
     */
    public const DEFAULT_MENU_BG = '#161617';

    /**
     * Primary color for referrer-facing UI (e.g. landing form).
     */
    public const REFERRER_PRIMARY = '#1858cb';

    /**
     * Package slugs that allow custom branding (custom palette).
     */
    private const CUSTOM_BRANDING_SLUGS = ['starter', 'growth'];

    /**
     * Get the primary hex to use for the referral panel for the current user.
     * Admin colours are taken from the company only (Company colors main); no brand fallback.
     */
    public static function primaryForReferralPanel(): string
    {
        $companyPrimary = self::companyMainForPanel();
        if ($companyPrimary !== null) {
            return $companyPrimary;
        }

        return self::REFERLYST_PRIMARY;
    }

    /**
     * Company main colour for the panel (admin is based on company colors). Null if not set.
     */
    private static function companyMainForPanel(): ?string
    {
        $user = Auth::user();
        if ($user === null || $user->company_id === null) {
            return null;
        }

        $company = $user->company;
        if (! $company || ! is_array($company->admin_menu_colors ?? null)) {
            return null;
        }

        $main = $company->admin_menu_colors['main'] ?? null;
        if (! is_string($main) || $main === '') {
            return null;
        }

        return self::normalizeHex($main);
    }

    /**
     * Admin menu background for the referral panel. Based on company secondary colour;
     * falls back to legacy menu_bg, then default.
     */
    public static function menuBackgroundForPanel(): string
    {
        $user = Auth::user();
        if ($user === null || $user->company_id === null) {
            return self::DEFAULT_MENU_BG;
        }

        $company = $user->company;
        if (! $company || ! is_array($company->admin_menu_colors ?? null)) {
            return self::DEFAULT_MENU_BG;
        }

        $secondary = $company->admin_menu_colors['secondary'] ?? null;
        if (is_string($secondary) && $secondary !== '') {
            return self::normalizeHex($secondary);
        }

        $legacyBg = $company->admin_menu_colors['menu_bg'] ?? null;
        if (is_string($legacyBg) && $legacyBg !== '') {
            return self::normalizeHex($legacyBg);
        }

        return self::DEFAULT_MENU_BG;
    }

    /**
     * Get the primary hex to use for referrer-facing UI (e.g. landing page).
     * Returns custom brand primary if the brand has Starter/Growth and custom colors;
     * otherwise REFERRER_PRIMARY (#1858cb).
     */
    public static function primaryForReferrer(BrandReferral $brand): string
    {
        $package = $brand->active_package;

        if ($package instanceof Package
            && in_array($package->slug, self::CUSTOM_BRANDING_SLUGS, true)
            && $package->custom_branding
            && is_array($brand->colors)
            && ! empty($brand->colors['primary'] ?? null)) {
            return (string) $brand->colors['primary'];
        }

        return self::REFERRER_PRIMARY;
    }

    /**
     * Get primary and secondary hex for referrer-facing UI (e.g. landing page).
     * Returns custom brand colors when available; otherwise defaults.
     *
     * @return array{primary: string, secondary: string}
     */
    public static function paletteForReferrer(BrandReferral $brand): array
    {
        $primary = self::primaryForReferrer($brand);
        $package = $brand->active_package;
        $secondary = self::REFERRER_PRIMARY;

        if ($package instanceof Package
            && in_array($package->slug, self::CUSTOM_BRANDING_SLUGS, true)
            && $package->custom_branding
            && is_array($brand->colors)
            && ! empty($brand->colors['secondary'] ?? null)) {
            $secondary = (string) $brand->colors['secondary'];
        }

        return [
            'primary' => self::normalizeHex($primary),
            'secondary' => self::normalizeHex($secondary),
        ];
    }

    /**
     * Brand used for referral panel colors: company’s default brand if it has
     * custom colors, otherwise first brand with Starter/Growth and custom palette.
     */
    private static function brandForPanelColors(): ?BrandReferral
    {
        $user = Auth::user();
        if ($user === null || $user->company_id === null) {
            return null;
        }

        $company = $user->company;
        if (! $company) {
            return null;
        }

        $defaultBrand = $company->defaultBrand;
        if ($defaultBrand instanceof BrandReferral
            && is_array($defaultBrand->colors)
            && ! empty($defaultBrand->colors['primary'] ?? null)) {
            return $defaultBrand;
        }

        return self::brandWithCustomColorsForCurrentUser();
    }

    /**
     * First brand of the current user's company that has Starter/Growth and custom colors.
     */
    private static function brandWithCustomColorsForCurrentUser(): ?BrandReferral
    {
        $user = Auth::user();
        if ($user === null || $user->company_id === null) {
            return null;
        }

        return BrandReferral::query()
            ->where('company_id', $user->company_id)
            ->get()
            ->first(fn (BrandReferral $b): bool => self::brandHasCustomPalette($b));
    }

    private static function brandHasCustomPalette(BrandReferral $brand): bool
    {
        $package = $brand->active_package;
        if (! $package instanceof Package) {
            return false;
        }
        if (! $package->custom_branding) {
            return false;
        }
        $slug = $package->slug ?? '';
        if ($slug === '' && $package->name !== null) {
            $slug = strtolower($package->name);
        }
        if (! in_array($slug, self::CUSTOM_BRANDING_SLUGS, true)) {
            return false;
        }
        if (! is_array($brand->colors) || empty($brand->colors['primary'] ?? null)) {
            return false;
        }

        return true;
    }

    private static function normalizeHex(string $hex): string
    {
        $hex = trim(strtolower($hex));
        if (str_starts_with($hex, '#')) {
            return $hex;
        }

        return '#'.$hex;
    }
}
