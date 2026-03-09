@php
    $default = \App\Services\ReferralBrandColors::REFERLYST_PRIMARY;
    $shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
    $primaryNormalized = isset($primary) ? strtolower(trim((string) $primary)) : '';
    $defaultNormalized = strtolower(trim($default));
    $useCustom = $primaryNormalized !== '' && $primaryNormalized !== $defaultNormalized;
    $menuBg = $menuBg ?? \App\Services\ReferralBrandColors::DEFAULT_MENU_BG;
@endphp
@if ($useCustom)
<style>
    :root, .dark, [data-panel="referral"], .fi-panel {
        @foreach ($shades as $shade)
        --primary-{{ $shade }}: {{ $primary }};
        @endforeach
    }
</style>
@endif
<style>
    [data-panel="referral"] .fi-sidebar,
    [data-panel="referral"] .fi-sidebar-header {
        background: {{ $menuBg }} !important;
    }
</style>
