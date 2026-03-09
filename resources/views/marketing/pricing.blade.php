@extends('layouts.marketing')

@section('title', 'Pricing – ' . config('app.name'))
@section('meta_description', 'Choose the right plan for your business. Start free and upgrade as your referral program grows.')

@section('content')
    <section class="bg-gray-950">
        <x-marketing.container>
            {{-- Hero --}}
            <div class="mx-auto max-w-3xl pt-20 pb-6 text-center sm:pt-28 sm:pb-8">
                <p class="text-sm font-semibold uppercase tracking-widest text-gray-500">Pricing</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl sm:tracking-[-0.02em]">
                    Simple pricing that grows with your referral program
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-gray-400 sm:text-xl">
                    Start free and upgrade when your referral program grows.
                </p>
            </div>

            {{-- Pricing cards --}}
            @if ($packages->isEmpty())
                <div class="mx-auto mt-24 max-w-lg rounded-2xl border border-gray-800 bg-gray-900 p-14 text-center">
                    <p class="text-gray-400">No packages are available at the moment. Check back soon or contact us.</p>
                    <x-marketing.button href="{{ route('home') }}" variant="secondary" class="mt-10 px-6 py-3">Back to home</x-marketing.button>
                </div>
            @else
                <div class="mt-16 grid gap-6 lg:mt-24 lg:grid-cols-3 lg:gap-8">
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
            @endif

            @if (!$packages->isEmpty())
                <p class="mt-10 text-center text-[15px] text-gray-400">
                    Start free and upgrade when your referral program grows.
                </p>
                <p class="mt-6 text-center">
                    <a href="{{ route('compare-plans') }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">Compare plans →</a>
                </p>
            @endif

            {{-- Add-ons --}}
            @if ($addons->isNotEmpty())
                <div class="pt-28 sm:pt-36">
                    <p class="text-center text-sm font-semibold uppercase tracking-widest text-gray-500">Add-ons</p>
                    <h2 class="mt-3 text-center text-2xl font-semibold tracking-tight text-white sm:text-3xl">Optional add-ons</h2>
                    <p class="mt-3 text-center text-base text-gray-400 max-w-2xl mx-auto">
                        Add-ons are available to existing customers only. You can attach them to any plan, per brand. Each add-on is billed annually per brand.
                    </p>
                    <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($addons as $addon)
                            <div class="rounded-2xl border border-gray-800 bg-gray-900 p-8 transition-all duration-200 hover:border-gray-700">
                                <h3 class="text-lg font-semibold tracking-tight text-white">{{ $addon->name }}</h3>
                                @if ($addon->description)
                                    <p class="mt-3 text-[15px] leading-relaxed text-gray-400">{{ str($addon->description)->limit(120) }}</p>
                                @endif
                                <div class="mt-6 flex items-baseline gap-2">
                                    <span class="text-2xl font-bold tracking-tight tabular-nums text-white">€{{ number_format($addon->price, 0) }}</span>
                                    <span class="flex items-baseline gap-1">
                                        <span class="text-sm text-gray-500">/ year</span>
                                        <span
                                            class="relative inline-flex flex-shrink-0 cursor-help text-gray-500 hover:text-gray-400"
                                            x-data="{ tooltip: false }"
                                            @mouseenter="tooltip = true"
                                            @mouseleave="tooltip = false"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span
                                                x-show="tooltip"
                                                x-cloak
                                                x-transition:enter="transition ease-out duration-150"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-100"
                                                x-transition:leave-end="opacity-0"
                                                class="absolute left-full bottom-full z-20 mb-2 ml-1 w-max max-w-[14rem] rounded-lg border border-gray-600 bg-gray-800 px-3 py-2.5 text-xs font-normal leading-tight text-white shadow-2xl ring-1 ring-black/20 whitespace-nowrap"
                                            >
                                                Add-ons are purchased per brand.
                                                <span class="absolute -left-1.5 top-1/2 h-0 w-0 -translate-y-1/2 border-y-[4px] border-y-transparent border-r-[5px] border-r-gray-800" aria-hidden="true"></span>
                                            </span>
                                        </span>
                                    </span>
                                </div>
                                <div class="mt-8">
                                    @auth
                                        @if (auth()->user()->type !== 'admin')
                                            <x-marketing.button href="{{ url('/referral/purchase-packages') }}" variant="primary" class="w-full justify-center px-6 py-3 text-[15px] font-medium">
                                                Add add-on in dashboard
                                            </x-marketing.button>
                                        @else
                                            <x-marketing.button href="{{ url('/sadm/addons') }}" variant="primary" class="w-full justify-center px-6 py-3 text-[15px] font-medium">
                                                Add add-on in dashboard
                                            </x-marketing.button>
                                        @endif
                                    @else
                                        <p class="text-xs text-gray-500 mb-4">Valid with any plan. Sign in or create an account to purchase this add-on.</p>
                                        <x-marketing.button href="{{ url('/referral/purchase-packages') }}" variant="primary" class="w-full justify-center px-6 py-3 text-[15px] font-medium">
                                            Buy now
                                        </x-marketing.button>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="pt-28 sm:pt-36">
                <p class="text-center text-sm font-semibold uppercase tracking-widest text-gray-500">FAQ</p>
                <h2 class="mt-3 text-center text-2xl font-semibold tracking-tight text-white sm:text-3xl">Pricing FAQ</h2>
                <div class="mx-auto mt-14 max-w-2xl rounded-2xl border border-gray-800 bg-gray-900/50 px-8 py-6 sm:px-12 sm:py-8" x-data="{ openIndex: null }">
                    <dl class="divide-y divide-gray-800">
                        <div class="py-4 first:pt-0">
                            <button type="button" @click="openIndex = openIndex === 0 ? null : 0" class="flex w-full items-center justify-between gap-4 text-left" :aria-expanded="openIndex === 0">
                                <dt class="text-lg font-semibold tracking-tight text-white">What is Referlyst?</dt>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-[transform] duration-200" :class="{ 'rotate-180': openIndex === 0 }">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <dd x-show="openIndex === 0" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0" class="mt-3 text-[15px] leading-relaxed text-gray-400" x-cloak>Referlyst is a platform that helps businesses create and manage referral programs.</dd>
                        </div>
                        <div class="py-4">
                            <button type="button" @click="openIndex = openIndex === 1 ? null : 1" class="flex w-full items-center justify-between gap-4 text-left" :aria-expanded="openIndex === 1">
                                <dt class="text-lg font-semibold tracking-tight text-white">Can I start with the free plan?</dt>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-[transform] duration-200" :class="{ 'rotate-180': openIndex === 1 }">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <dd x-show="openIndex === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0" class="mt-3 text-[15px] leading-relaxed text-gray-400" x-cloak>Yes. You can start with the free plan and upgrade at any time as your referral program grows.</dd>
                        </div>
                        <div class="py-4">
                            <button type="button" @click="openIndex = openIndex === 2 ? null : 2" class="flex w-full items-center justify-between gap-4 text-left" :aria-expanded="openIndex === 2">
                                <dt class="text-lg font-semibold tracking-tight text-white">Can I upgrade my plan later?</dt>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-[transform] duration-200" :class="{ 'rotate-180': openIndex === 2 }">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <dd x-show="openIndex === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0" class="mt-3 text-[15px] leading-relaxed text-gray-400" x-cloak>Yes. You can upgrade your plan at any time.</dd>
                        </div>
                        <div class="py-4">
                            <button type="button" @click="openIndex = openIndex === 3 ? null : 3" class="flex w-full items-center justify-between gap-4 text-left" :aria-expanded="openIndex === 3">
                                <dt class="text-lg font-semibold tracking-tight text-white">What happens if I exceed my limits?</dt>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-[transform] duration-200" :class="{ 'rotate-180': openIndex === 3 }">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <dd x-show="openIndex === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0" class="mt-3 text-[15px] leading-relaxed text-gray-400" x-cloak>You will be able to upgrade to a higher plan to unlock additional capacity.</dd>
                        </div>
                        <div class="py-4">
                            <button type="button" @click="openIndex = openIndex === 4 ? null : 4" class="flex w-full items-center justify-between gap-4 text-left" :aria-expanded="openIndex === 4">
                                <dt class="text-lg font-semibold tracking-tight text-white">Do you offer custom domains?</dt>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-[transform] duration-200" :class="{ 'rotate-180': openIndex === 4 }">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <dd x-show="openIndex === 4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0" class="mt-3 text-[15px] leading-relaxed text-gray-400" x-cloak>Yes. Custom domains are included in the Growth plan.</dd>
                        </div>
                        <div class="py-4 last:pb-0">
                            <button type="button" @click="openIndex = openIndex === 5 ? null : 5" class="flex w-full items-center justify-between gap-4 text-left" :aria-expanded="openIndex === 5">
                                <dt class="text-lg font-semibold tracking-tight text-white">Can I add add-ons to my plan?</dt>
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-[transform] duration-200" :class="{ 'rotate-180': openIndex === 5 }">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </span>
                            </button>
                            <dd x-show="openIndex === 5" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-end="opacity-0" class="mt-3 text-[15px] leading-relaxed text-gray-400" x-cloak>Yes. Add-ons (e.g. custom domain, API access, extra referrers) are available to existing customers. You purchase and attach add-ons per brand from your dashboard; each add-on is billed annually per brand.</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Final CTA --}}
            <div class="py-28 text-center sm:py-36">
                <div class="mx-auto max-w-2xl rounded-2xl border border-gray-800 bg-gray-900/30 px-8 py-16 sm:px-14 sm:py-20">
                    <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl sm:tracking-[-0.02em]">
                        Start growing through referrals today.
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-gray-400">
                        Launch your referral program in minutes and let your customers help you grow.
                    </p>
                    <div class="mt-10">
                        <x-marketing.button href="{{ route('register') }}" variant="primary" class="px-8 py-4 text-base font-medium">
                            Create your account
                        </x-marketing.button>
                    </div>
                </div>
            </div>
        </x-marketing.container>
    </section>
@endsection
