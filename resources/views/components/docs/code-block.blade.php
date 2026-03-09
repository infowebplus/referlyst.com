@props(['label' => null, 'lang' => 'bash'])

<div
    class="group relative mt-3 overflow-hidden rounded-lg border border-gray-800 bg-[#0d1117]"
    x-data="{ copied: false }"
>
    {{-- ─── Header bar ─── --}}
    @if ($label)
        <div class="flex items-center justify-between border-b border-gray-800 px-4 py-2">
            <span class="text-xs font-medium text-gray-500">{{ $label }}</span>

            <button
                type="button"
                @click="
                    copyToClipboard($refs.code.innerText);
                    copied = true;
                    setTimeout(() => copied = false, 2000)
                "
                class="flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium text-gray-500 transition hover:bg-gray-800 hover:text-gray-300"
                :class="copied ? 'text-emerald-400' : ''"
            >
                <span x-show="!copied" style="display:inline-flex;align-items:center;gap:5px">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                    Copy
                </span>
                <span x-show="copied" style="display:none;align-items:center;gap:5px">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Copied
                </span>
            </button>
        </div>
    @else
        {{-- Floating copy button when no header --}}
        <button
            type="button"
            @click="
                copyToClipboard($refs.code.innerText);
                copied = true;
                setTimeout(() => copied = false, 2000)
            "
            class="absolute right-3 top-3 flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium text-gray-600 opacity-0 transition group-hover:opacity-100 hover:bg-gray-800 hover:text-gray-300"
            :class="copied ? '!opacity-100 text-emerald-400' : ''"
        >
            <span x-show="!copied" style="display:inline-flex;align-items:center;gap:5px">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                Copy
            </span>
            <span x-show="copied" style="display:none;align-items:center;gap:5px">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                Copied
            </span>
        </button>
    @endif

    {{-- ─── Code ─── --}}
    <pre
        x-ref="code"
        class="overflow-x-auto p-4 text-sm leading-relaxed text-gray-300"
        style="font-family:ui-monospace,'Cascadia Code','Fira Code',Consolas,'Liberation Mono',Menlo,monospace"
    ><code>{{ $slot }}</code></pre>
</div>
