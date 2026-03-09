<?php

namespace App\Services;

class ColorContrast
{
    /**
     * WCAG 2.1 minimum contrast ratio for normal text (AA).
     */
    public const MIN_RATIO_AA = 4.5;

    /**
     * WCAG 2.1 minimum contrast ratio for large text (AA).
     */
    public const MIN_RATIO_AA_LARGE = 3.0;

    /**
     * Typical light background (e.g. panel, cards).
     */
    public const BACKGROUND_LIGHT = '#ffffff';

    /**
     * Typical dark background (e.g. dark mode panel).
     */
    public const BACKGROUND_DARK = '#18181b';

    /**
     * Compute relative luminance (WCAG 2.1). Returns 0–1.
     */
    public static function relativeLuminance(string $hex): float
    {
        $hex = self::normalizeHex($hex);
        $r = hexdec(substr($hex, 1, 2)) / 255;
        $g = hexdec(substr($hex, 3, 2)) / 255;
        $b = hexdec(substr($hex, 5, 2)) / 255;

        $r = $r <= 0.03928 ? $r / 12.92 : (($r + 0.055) / 1.055) ** 2.4;
        $g = $g <= 0.03928 ? $g / 12.92 : (($g + 0.055) / 1.055) ** 2.4;
        $b = $b <= 0.03928 ? $b / 12.92 : (($b + 0.055) / 1.055) ** 2.4;

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    /**
     * Contrast ratio between two colors (1–21). WCAG formula.
     */
    public static function contrastRatio(string $foregroundHex, string $backgroundHex): float
    {
        $l1 = self::relativeLuminance($foregroundHex);
        $l2 = self::relativeLuminance($backgroundHex);
        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    /**
     * Check if foreground has at least AA contrast on both light and dark backgrounds.
     */
    public static function passesAaOnBothBackgrounds(string $foregroundHex): bool
    {
        $onLight = self::contrastRatio($foregroundHex, self::BACKGROUND_LIGHT);
        $onDark = self::contrastRatio($foregroundHex, self::BACKGROUND_DARK);

        return $onLight >= self::MIN_RATIO_AA || $onDark >= self::MIN_RATIO_AA;
    }

    /**
     * Get a short message describing contrast on light and dark backgrounds (for UI).
     * Returns non-empty only when the color fails AA on at least one background.
     */
    public static function contrastMessage(string $foregroundHex): string
    {
        $onLight = round(self::contrastRatio($foregroundHex, self::BACKGROUND_LIGHT), 1);
        $onDark = round(self::contrastRatio($foregroundHex, self::BACKGROUND_DARK), 1);
        $min = self::MIN_RATIO_AA;

        $parts = [];
        if ($onLight < $min) {
            $parts[] = "on light background (ratio {$onLight}:1, need {$min}:1)";
        }
        if ($onDark < $min) {
            $parts[] = "on dark background (ratio {$onDark}:1, need {$min}:1)";
        }
        if ($parts === []) {
            return '';
        }

        return 'Low contrast '.implode(' and ', $parts).'. Consider a darker or lighter shade for better readability.';
    }

    private static function normalizeHex(string $hex): string
    {
        $hex = trim(strtolower($hex));
        if (! str_starts_with($hex, '#')) {
            $hex = '#'.$hex;
        }
        if (strlen($hex) !== 7) {
            return '#000000';
        }

        return $hex;
    }
}
