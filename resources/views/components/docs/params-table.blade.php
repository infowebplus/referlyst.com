@props([
    'headers' => ['Field', 'Type', 'Required', 'Description'],
])

<div class="overflow-x-auto rounded-lg border border-gray-800">
    <table class="min-w-full text-sm">
        <thead>
            <tr class="border-b border-gray-800 bg-gray-900/60">
                @foreach ($headers as $header)
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800/50">
            {{ $slot }}
        </tbody>
    </table>
</div>
