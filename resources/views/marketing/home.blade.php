@extends('layouts.marketing')

@section('title', 'Turn your customers into your best marketers – ' . config('app.name'))
@section('meta_description', 'Referlyst helps you launch and manage referral programs in minutes. Reward customers for sharing your product and grow through word-of-mouth.')

@section('content')
    {{-- 1. Hero --}}
    <section class="relative overflow-hidden bg-gray-950 pt-32 pb-24">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-500/5 via-transparent to-transparent" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(59,130,246,0.12),transparent)]" aria-hidden="true"></div>
        <x-marketing.container class="relative">
            <div class="mx-auto max-w-3xl text-center">
                <h1 class="text-5xl font-bold tracking-tight text-white sm:text-6xl">
                    Turn your customers into your best marketers.
                </h1>
                <p class="mt-6 text-lg text-gray-300 leading-relaxed">
                    Launch referral programs in minutes and grow through word-of-mouth.
                </p>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                    <x-marketing.button href="{{ route('register') }}" variant="primary" class="px-6 py-3">
                        Get Started
                    </x-marketing.button>
                    <x-marketing.button href="{{ route('pricing') }}" variant="secondary" class="px-6 py-3">
                        View Pricing
                    </x-marketing.button>
                </div>
                <p class="mt-8 text-sm text-gray-500">
                    No complicated setup. Launch your referral program today.
                </p>
            </div>
        </x-marketing.container>
    </section>

    {{-- 2. Value proposition --}}
    <section class="bg-gray-900 py-24" aria-labelledby="value-heading">
        <x-marketing.container>
            <h2 id="value-heading" class="text-center text-3xl font-semibold text-white">
                Why referral marketing works
            </h2>
            <p class="mx-auto mt-6 max-w-2xl text-center text-lg text-gray-300">
                People trust recommendations from friends more than ads. Referral programs reward your customers for sharing your product with others.
            </p>
            <ul class="mx-auto mt-20 grid max-w-4xl grid-cols-1 gap-6 sm:grid-cols-3">
                <li class="flex flex-col gap-4 rounded-xl border border-gray-800 bg-gray-900 p-6 transition-all hover:border-blue-500/50 hover:shadow-lg">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </span>
                    <div>
                        <span class="font-semibold text-white">Higher conversion rates</span>
                        <p class="mt-2 text-gray-400">Referred customers convert at 3–5x the rate of other channels.</p>
                    </div>
                </li>
                <li class="flex flex-col gap-4 rounded-xl border border-gray-800 bg-gray-900 p-6 transition-all hover:border-blue-500/50 hover:shadow-lg">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <div>
                        <span class="font-semibold text-white">Lower acquisition cost</span>
                        <p class="mt-2 text-gray-400">Pay for results instead of clicks. Reward only when a referral converts.</p>
                    </div>
                </li>
                <li class="flex flex-col gap-4 rounded-xl border border-gray-800 bg-gray-900 p-6 transition-all hover:border-blue-500/50 hover:shadow-lg">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </span>
                    <div>
                        <span class="font-semibold text-white">Organic growth from real users</span>
                        <p class="mt-2 text-gray-400">Your best customers bring others who already trust your brand.</p>
                    </div>
                </li>
            </ul>
        </x-marketing.container>
    </section>

    {{-- 3. Features --}}
    <section class="bg-gray-950 py-24" aria-labelledby="features-heading">
        <x-marketing.container>
            <h2 id="features-heading" class="text-center text-3xl font-semibold text-white">
                Everything you need to run a referral program
            </h2>
            <p class="mx-auto mt-6 max-w-2xl text-center text-lg text-gray-300">
                From unique links to fraud protection—all in one place.
            </p>
            <div class="mt-20 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <x-marketing.feature-card title="Referral Links" description="Give every customer a unique referral link they can share with friends." />
                <x-marketing.feature-card title="Automated Rewards" description="Automatically reward users when a referral converts." />
                <x-marketing.feature-card title="Analytics Dashboard" description="Track referral performance and understand how your program grows." />
                <x-marketing.feature-card title="Fraud Detection" description="Identify suspicious activity and protect your program from abuse." />
                <x-marketing.feature-card title="Custom Branding" description="Match your referral experience with your brand." />
                <x-marketing.feature-card title="Easy Integration" description="Get started quickly without complex technical setup." />
            </div>
        </x-marketing.container>
    </section>

    {{-- 4. How it works --}}
    <section class="bg-gray-900 py-24" aria-labelledby="how-heading">
        <x-marketing.container>
            <h2 id="how-heading" class="text-center text-3xl font-semibold text-white">
                How Referlyst works
            </h2>
            <p class="mx-auto mt-6 max-w-xl text-center text-lg text-gray-300">
                Three steps from signup to a live referral program.
            </p>
            <div class="mt-20 flex flex-col gap-0 sm:flex-row sm:items-stretch sm:justify-between sm:gap-8">
                <div class="relative flex flex-1 flex-col items-center border-gray-800 py-8 text-center sm:border-r sm:border-t-0 sm:py-0 sm:pr-8 last:border-r-0">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-blue-500/30 bg-blue-500/10 text-lg font-bold text-blue-400">1</div>
                    <h3 class="mt-6 text-lg font-semibold text-white">Create your referral program.</h3>
                    <p class="mt-2 text-gray-400">Set up rewards and rules in minutes. No code required.</p>
                </div>
                <div class="relative flex flex-1 flex-col items-center border-t border-gray-800 py-8 text-center sm:border-t-0 sm:border-r sm:py-0 sm:px-8 sm:pr-8 last:border-r-0">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-blue-500/30 bg-blue-500/10 text-lg font-bold text-blue-400">2</div>
                    <h3 class="mt-6 text-lg font-semibold text-white">Customers share their referral link.</h3>
                    <p class="mt-2 text-gray-400">Each customer gets a unique link to share with friends and colleagues.</p>
                </div>
                <div class="relative flex flex-1 flex-col items-center border-t border-gray-800 py-8 text-center sm:border-t-0 sm:py-0 sm:pl-8">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-blue-500/30 bg-blue-500/10 text-lg font-bold text-blue-400">3</div>
                    <h3 class="mt-6 text-lg font-semibold text-white">Track referrals and reward successful promoters.</h3>
                    <p class="mt-2 text-gray-400">See conversions in real time and automate rewards.</p>
                </div>
            </div>
        </x-marketing.container>
    </section>

    {{-- 5. Social proof --}}
    <section class="relative bg-gray-950 py-28" aria-labelledby="social-heading">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_70%_50%_at_50%_80%,rgba(34,197,94,0.04),transparent)]" aria-hidden="true"></div>
        <x-marketing.container class="relative">
            <div class="text-center">
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-400/90">Trusted by teams</p>
                <h2 id="social-heading" class="mt-3 text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                    Built for modern businesses
                </h2>
                <p class="mx-auto mt-5 max-w-xl text-center text-base leading-relaxed text-gray-400">
                    Referlyst helps companies grow faster by turning satisfied customers into brand advocates.
                </p>
            </div>

            {{-- Stats --}}
            <div class="mt-20 grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="group relative rounded-2xl border border-gray-800/80 bg-gray-900/40 px-8 py-10 text-center backdrop-blur-sm transition-all duration-300 hover:border-emerald-500/30 hover:bg-gray-900/60">
                    <p class="text-4xl font-bold tabular-nums text-white">3–5×</p>
                    <p class="mt-2 text-sm font-medium text-gray-500">Higher conversion from referrals</p>
                </div>
                <div class="group relative rounded-2xl border border-gray-800/80 bg-gray-900/40 px-8 py-10 text-center backdrop-blur-sm transition-all duration-300 hover:border-emerald-500/30 hover:bg-gray-900/60">
                    <p class="text-4xl font-bold text-white">Lower CAC</p>
                    <p class="mt-2 text-sm font-medium text-gray-500">Pay only when referrals convert</p>
                </div>
                <div class="group relative rounded-2xl border border-gray-800/80 bg-gray-900/40 px-8 py-10 text-center backdrop-blur-sm transition-all duration-300 hover:border-emerald-500/30 hover:bg-gray-900/60">
                    <p class="text-4xl font-bold text-white">Trust-first</p>
                    <p class="mt-2 text-sm font-medium text-gray-500">Growth from real recommendations</p>
                </div>
            </div>

        </x-marketing.container>
    </section>

    {{-- 6. Pricing preview --}}
    @if ($packages->isNotEmpty())
        <section class="bg-gray-900 py-24" aria-labelledby="pricing-heading">
            <x-marketing.container>
                <h2 id="pricing-heading" class="text-center text-3xl font-semibold text-white">
                    Pricing
                </h2>
                <p class="mx-auto mt-6 max-w-2xl text-center text-lg text-gray-300">
                    Choose a plan that fits. Upgrade as you grow.
                </p>
                <div class="mt-20 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($packages as $package)
                        @php
                            $ctaUrl = $package->slug === 'free' ? route('register') : route('buy-package', ['package' => $package->slug]);
                        @endphp
                        <x-marketing.pricing-card
                            :name="$package->name"
                            :price="'€' . number_format($package->price, 0)"
                            :short-description="$package->short_description"
                            :features="$package->getFeaturesList()"
                            :limitations="$package->limitations ?? []"
                            :cta-url="$ctaUrl"
                            :cta-label="$package->cta_label"
                            :highlighted="$package->slug === 'starter'"
                            :badge="$package->badge"
                        />
                    @endforeach
                </div>
                <p class="mt-12 text-center">
                    <a href="{{ route('pricing') }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">View all plans →</a>
                </p>
            </x-marketing.container>
        </section>
    @endif

    {{-- 7. Final CTA --}}
    <section class="bg-gray-950 py-24">
        <x-marketing.container>
            <div class="relative overflow-hidden rounded-2xl border border-gray-800 bg-gradient-to-br from-blue-500/10 via-gray-900 to-gray-900 px-8 py-20 text-center sm:px-14 sm:py-24">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_60%_50%_at_50%_100%,rgba(59,130,246,0.15),transparent)]" aria-hidden="true"></div>
                <div class="relative">
                    <h2 class="text-3xl font-semibold text-white sm:text-4xl">
                        Start growing through referrals today
                    </h2>
                    <p class="mx-auto mt-6 max-w-xl text-lg text-gray-300">
                        Launch your referral program and let your customers help you grow.
                    </p>
                    <x-marketing.button href="{{ route('register') }}" variant="primary" class="mt-10 px-6 py-3">
                        Create your account
                    </x-marketing.button>
                </div>
            </div>
        </x-marketing.container>
    </section>
@endsection
