@props([
    'title',
    'description' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-800 bg-gray-900 p-6 transition-all duration-200 hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/5']) }}>
    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-500/10 text-blue-400 ring-1 ring-blue-500/20">
        @if ($icon)
            {{ $icon }}
        @else
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        @endif
    </div>
    <h3 class="mt-5 text-lg font-semibold text-white">{{ $title }}</h3>
    @if ($description)
        <p class="mt-2 text-gray-400 leading-relaxed">{{ $description }}</p>
    @endif
    {{ $slot }}
</div>
