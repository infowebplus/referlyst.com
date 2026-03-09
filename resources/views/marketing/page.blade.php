@extends('layouts.marketing')

@section('title', ($title ?? 'Page') . ' – ' . config('app.name'))

@section('content')
    <section class="bg-gray-950 py-24">
        <x-marketing.container class="max-w-3xl">
            <h1 class="text-5xl font-bold tracking-tight text-white">{{ $heading ?? $title ?? 'Page' }}</h1>
            <div class="mt-8">
                <p class="text-lg text-gray-300">{{ $content ?? 'Content coming soon.' }}</p>
            </div>
        </x-marketing.container>
    </section>
@endsection
