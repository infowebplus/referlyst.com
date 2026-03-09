@props([
    'name',
    'price',
    'shortDescription' => null,
    'features' => [],
    'limitations' => [],
    'ctaUrl',
    'ctaLabel' => 'Get started',
    'highlighted' => false,
    'badge' => null,
])

@php
    $showBadge = $highlighted || $badge;
    $badgeText = $badge ?? 'Most Popular';
    $cardClass = $highlighted
        ? 'relative rounded-2xl border-2 border-blue-500 bg-gray-900 p-10 shadow-lg shadow-blue-500/10'
        : 'rounded-2xl border border-gray-800 bg-gray-900 p-10 transition-all duration-200 hover:border-gray-700';
@endphp

<div {{ $attributes->merge(['class' => $cardClass]) }}>
    @if ($showBadge)
        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 rounded-full bg-blue-600 px-4 py-1.5 text-xs font-semibold tracking-wide text-white shadow-lg">
            {{ $badgeText }}
        </div>
    @endif
    <h3 class="text-xl font-semibold tracking-tight text-white">{{ $name }}</h3>
    <div class="mt-8 flex items-baseline gap-1.5">
        <span class="text-4xl font-bold tracking-tight tabular-nums text-white">{{ $price }}</span>
        <span class="text-base font-medium text-gray-500">/ month</span>
    </div>
    @if ($shortDescription)
        <p class="mt-5 text-[15px] leading-relaxed text-gray-400">{{ $shortDescription }}</p>
    @endif
    @if (!empty($features))
        <ul class="mt-10 space-y-4" role="list">
            @foreach ($features as $feature)
                <li class="flex items-start gap-3 text-[15px] leading-snug text-gray-300">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06 1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    @endif
    @if (!empty($limitations))
        <ul class="mt-8 space-y-3 border-t border-gray-800 pt-8" role="list">
            @foreach ($limitations as $limitation)
                <li class="flex items-start gap-3 text-sm leading-relaxed text-gray-500">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ $limitation }}</span>
                </li>
            @endforeach
        </ul>
    @endif
    <div class="mt-10">
        <x-marketing.button :href="$ctaUrl" variant="primary" class="w-full justify-center px-6 py-3.5 text-[15px] font-medium">
            {{ $ctaLabel }}
        </x-marketing.button>
    </div>
</div>
