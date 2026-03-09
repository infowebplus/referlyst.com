@props([
    'href' => null,
    'variant' => 'secondary', // 'primary' | 'secondary' | 'ghost'
])

@php
    $base = 'inline-flex items-center justify-center rounded-lg font-medium text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-950 disabled:opacity-50';
    $variants = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-500 focus:ring-blue-500 px-6 py-3 shadow-lg shadow-blue-500/20',
        'secondary' => 'border border-gray-700 text-gray-300 hover:border-gray-600 hover:bg-gray-800/50 focus:ring-gray-600 px-6 py-3',
        'ghost' => 'text-gray-400 hover:text-white hover:bg-gray-800/80 px-4 py-3 focus:ring-gray-600',
    ];
    $class = $base . ' ' . ($variants[$variant] ?? $variants['secondary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>
        {{ $slot }}
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => $class]) }}>
        {{ $slot }}
    </button>
@endif
