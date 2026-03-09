@extends('layouts.marketing')

@section('title', 'Features – ' . config('app.name'))
@section('meta_description', 'Powerful tools for referral growth: tracking, rewards, analytics, fraud prevention, and customization.')

@section('content')
    <section class="bg-gray-950">
        {{-- Hero --}}
        <div class="border-b border-gray-800">
            <x-marketing.container class="py-20 md:py-28">
                <div class="max-w-2xl">
                    <h1 class="text-4xl font-bold tracking-tight text-white md:text-5xl">
                        Built for growth
                    </h1>
                    <p class="mt-4 text-lg text-gray-400">
                        Everything you need to launch, run, and scale your referral program.
                    </p>
                </div>
            </x-marketing.container>
        </div>

        {{-- Features grid --}}
        <x-marketing.container class="py-16 md:py-24">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Referral tracking --}}
                <article class="group rounded-lg border border-gray-800 bg-gray-950 p-8 transition-colors hover:border-gray-700 hover:bg-gray-900/50">
                    <div class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-800 bg-gray-900 text-gray-400 transition-colors group-hover:border-gray-700 group-hover:text-blue-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                    </div>
                    <h2 class="mt-6 text-lg font-semibold text-white">Referral tracking</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-400">
                        Unique links per customer. Track clicks, signups, and conversions—and see who referred whom.
                    </p>
                </article>

                {{-- Reward management --}}
                <article class="group rounded-lg border border-gray-800 bg-gray-950 p-8 transition-colors hover:border-gray-700 hover:bg-gray-900/50">
                    <div class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-800 bg-gray-900 text-gray-400 transition-colors group-hover:border-gray-700 group-hover:text-emerald-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>
                    </div>
                    <h2 class="mt-6 text-lg font-semibold text-white">Reward management</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-400">
                        Set rewards once—credits, discounts, or cash. They trigger automatically when a referral converts.
                    </p>
                </article>

                {{-- Analytics and reporting --}}
                <article class="group rounded-lg border border-gray-800 bg-gray-950 p-8 transition-colors hover:border-gray-700 hover:bg-gray-900/50">
                    <div class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-800 bg-gray-900 text-gray-400 transition-colors group-hover:border-gray-700 group-hover:text-blue-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <h2 class="mt-6 text-lg font-semibold text-white">Analytics and reporting</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-400">
                        Top referrers, conversion rates, and revenue attributed to referrals. Export for deeper analysis.
                    </p>
                </article>

                {{-- Fraud prevention --}}
                <article class="group rounded-lg border border-gray-800 bg-gray-950 p-8 transition-colors hover:border-gray-700 hover:bg-gray-900/50">
                    <div class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-800 bg-gray-900 text-gray-400 transition-colors group-hover:border-gray-700 group-hover:text-amber-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h2 class="mt-6 text-lg font-semibold text-white">Fraud prevention</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-400">
                        Duplicate referrals, velocity limits, invalid signups. Protect your budget and keep the program fair.
                    </p>
                </article>

                {{-- Customization --}}
                <article class="group rounded-lg border border-gray-800 bg-gray-950 p-8 transition-colors hover:border-gray-700 hover:bg-gray-900/50">
                    <div class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-800 bg-gray-900 text-gray-400 transition-colors group-hover:border-gray-700 group-hover:text-violet-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                    </div>
                    <h2 class="mt-6 text-lg font-semibold text-white">Customization</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-400">
                        Your logo, colors, and messaging on referral pages and emails. Feels like a natural part of your product.
                    </p>
                </article>

                {{-- Easy integration --}}
                <article class="group rounded-lg border border-gray-800 bg-gray-950 p-8 transition-colors hover:border-gray-700 hover:bg-gray-900/50">
                    <div class="flex h-9 w-9 items-center justify-center rounded-md border border-gray-800 bg-gray-900 text-gray-400 transition-colors group-hover:border-gray-700 group-hover:text-blue-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    </div>
                    <h2 class="mt-6 text-lg font-semibold text-white">Easy integration</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-400">
                        Get started quickly. Use the dashboard or API for deeper integration with your stack.
                    </p>
                </article>
            </div>

            {{-- CTA --}}
            <div class="mt-20 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-marketing.button href="{{ route('register') }}" variant="primary" class="w-full sm:w-auto px-6 py-3">
                    Get started
                </x-marketing.button>
                <a href="{{ route('pricing') }}" class="text-sm text-gray-400 hover:text-white transition-colors">View pricing →</a>
            </div>
        </x-marketing.container>
    </section>
@endsection
