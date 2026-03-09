@props([
    'title',
    'subtitle' => '',
    'version' => null,
    'nav' => [],
    // nav shape: [['group' => 'Overview', 'links' => [['id' => 'auth', 'label' => 'Authentication']]]]
])

@php
    $firstId = $nav[0]['links'][0]['id'] ?? '';
@endphp

<div
    x-data="{
        active: '{{ $firstId }}',
        init() {
            const sections = document.querySelectorAll('section[id]');
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) this.active = e.target.id; });
            }, { rootMargin: '-10% 0px -75% 0px' });
            sections.forEach(s => obs.observe(s));
        }
    }"
    x-init="init()"
    class="flex gap-12"
>
    {{-- ─── Sticky sidebar ─── --}}
    <aside class="hidden lg:block w-52 shrink-0">
        <div class="sticky top-24 py-2">
            @if ($version)
                <span class="mb-6 inline-flex items-center rounded-full border border-gray-700 bg-gray-800/60 px-2.5 py-0.5 text-xs font-medium text-gray-400">
                    v{{ $version }}
                </span>
            @endif

            <nav class="mt-4 space-y-7" aria-label="Docs navigation">
                @foreach ($nav as $group)
                    <div>
                        <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-gray-600">
                            {{ $group['group'] }}
                        </p>
                        <ul class="space-y-0.5">
                            @foreach ($group['links'] as $link)
                                <li>
                                    <a
                                        href="#{{ $link['id'] }}"
                                        :class="active === '{{ $link['id'] }}'
                                            ? 'text-white font-medium border-l-2 border-white pl-3 -ml-px'
                                            : 'text-gray-500 hover:text-gray-300 pl-3 -ml-px border-l-2 border-transparent'"
                                        class="block text-sm py-1 transition-colors duration-150"
                                    >{{ $link['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>
        </div>
    </aside>

    {{-- ─── Content ─── --}}
    <div class="min-w-0 flex-1 space-y-16 pb-20">
        {{ $slot }}
    </div>
</div>
