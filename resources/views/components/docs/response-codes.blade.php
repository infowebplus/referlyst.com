@props([
    'codes' => [],
    // shape: [['code' => 401, 'color' => 'red'|'amber'|'green', 'description' => '...']]
])

@php
    $palette = [
        'green' => 'text-emerald-400',
        'amber' => 'text-amber-400',
        'red'   => 'text-rose-400',
        'blue'  => 'text-blue-400',
    ];
@endphp

<div class="overflow-x-auto rounded-lg border border-gray-800">
    <table class="min-w-full text-sm">
        <thead>
            <tr class="border-b border-gray-800 bg-gray-900/60">
                <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Code</th>
                <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Meaning</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800/50">
            @foreach ($codes as $row)
                @php $color = $palette[$row['color'] ?? 'amber'] ?? 'text-gray-400'; @endphp
                <tr>
                    <td class="px-4 py-2.5 font-mono font-semibold {{ $color }}">{{ $row['code'] }}</td>
                    <td class="px-4 py-2.5 text-gray-400">{!! $row['description'] !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
