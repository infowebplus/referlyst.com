@props(['id', 'title'])

<section id="{{ $id }}" class="scroll-mt-24">
    <h2 class="text-xl font-semibold tracking-tight text-white">{{ $title }}</h2>
    <div class="mt-4 space-y-4">
        {{ $slot }}
    </div>
</section>
