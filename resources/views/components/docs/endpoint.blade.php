@props(['method', 'path', 'summary' => ''])

@php
    $palette = [
        'GET'    => 'bg-emerald-500/15 text-emerald-400 ring-emerald-500/20',
        'POST'   => 'bg-amber-500/15   text-amber-400   ring-amber-500/20',
        'PUT'    => 'bg-blue-500/15    text-blue-400    ring-blue-500/20',
        'PATCH'  => 'bg-violet-500/15  text-violet-400  ring-violet-500/20',
        'DELETE' => 'bg-red-500/15     text-red-400     ring-red-500/20',
    ];
    $badge = $palette[strtoupper($method)] ?? 'bg-gray-500/15 text-gray-400 ring-gray-500/20';
@endphp

<div class="flex flex-wrap items-center gap-3">
    <span class="shrink-0 rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wider ring-1 ring-inset {{ $badge }}">
        {{ strtoupper($method) }}
    </span>
    <code class="font-mono text-xl font-semibold text-white">{{ $path }}</code>
</div>

@if ($summary)
    <p class="mt-3 text-gray-400">{{ $summary }}</p>
@endif
