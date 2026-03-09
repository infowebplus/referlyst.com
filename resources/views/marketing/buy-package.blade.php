@extends('layouts.marketing')

@section('title', 'Choose plan – ' . $package->name . ' – ' . config('app.name'))

@section('content')
    <section class="bg-gray-950 py-24">
        <x-marketing.container class="max-w-2xl">
            <h1 class="text-5xl font-bold tracking-tight text-white">Choose your plan</h1>
            <p class="mt-6 text-lg text-gray-300">Review your selection and proceed to checkout.</p>

            {{-- Package summary --}}
            <div class="mt-14 rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-lg">
                <h2 class="text-xl font-semibold text-white">{{ $package->name }}</h2>
                <div class="mt-6 flex items-baseline gap-1">
                    <span class="text-4xl font-bold text-white">€{{ number_format($package->price, 0) }}</span>
                    <span class="text-gray-500">/ month</span>
                </div>
                @if (!empty($features))
                    <ul class="mt-8 space-y-4" role="list">
                        @foreach ($features as $feature)
                            <li class="flex items-start gap-3 text-gray-300">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06 1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            @auth
                <div class="mt-10">
                    <x-marketing.button href="{{ url('/referral/purchase-packages?package=' . $package->slug) }}" variant="primary" class="w-full justify-center px-6 py-3">
                        Proceed to checkout
                    </x-marketing.button>
                </div>
            @else
                <p class="mt-10 text-center text-sm text-gray-500">Sign in or create an account to complete your purchase.</p>
                <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center">
                    <x-marketing.button href="{{ route('login') }}" variant="secondary" class="px-6 py-3">
                        Log in
                    </x-marketing.button>
                    <x-marketing.button href="{{ route('register') }}?package={{ $package->slug }}" variant="primary" class="px-6 py-3">
                        Create your account
                    </x-marketing.button>
                </div>
            @endauth

            <p class="mt-10 text-center">
                <a href="{{ route('pricing') }}" class="text-sm text-gray-400 hover:text-white transition-colors">← Back to pricing</a>
            </p>
        </x-marketing.container>
    </section>
@endsection
