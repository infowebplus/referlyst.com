@props([
    'field',
    'type'        => 'string',
    'required'    => false,
    'description' => '',
    'nested'      => false,
])

<tr class="align-top">
    <td class="px-4 py-2.5 font-mono text-sm {{ $nested ? 'text-gray-400 pl-8' : 'text-gray-200' }}">
        {{ $field }}
    </td>
    <td class="px-4 py-2.5 text-gray-500">{{ $type }}</td>
    <td class="px-4 py-2.5">
        @if ($required)
            <span class="inline-flex items-center rounded-full bg-rose-500/10 px-2 py-0.5 text-xs font-medium text-rose-400">Yes</span>
        @else
            <span class="text-gray-600 text-xs">No</span>
        @endif
    </td>
    <td class="px-4 py-2.5 text-gray-400">{{ $description }}</td>
</tr>
