<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Launch and manage referral programs in minutes. No complex setup. Just growth.')">

    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="{{ asset('images/referlyst-icon.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('images/referlyst-icon.png') }}" sizes="16x16" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/referlyst-icon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) && !file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('mobileNav', { open: false });
        });
    </script>
</head>
<body class="bg-gray-950 text-white font-sans antialiased min-h-screen flex flex-col" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">
    {{-- Header --}}
    <header class="sticky top-0 z-50 border-b border-gray-800 bg-gray-950/90 backdrop-blur-xl">
        <x-marketing.container>
            <div class="flex h-16 items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0" aria-label="{{ config('app.name') }} home">
                    <img src="{{ asset('images/referlyst-logo.svg') }}" alt="{{ config('app.name') }}" class="h-8 w-auto" width="160" height="52" fetchpriority="high">
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden md:flex items-center gap-8" aria-label="Main">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Home</a>
                    <a href="{{ route('features') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Features</a>
                    <a href="{{ route('pricing') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Pricing</a>
                    <a href="{{ route('faq') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">FAQ</a>
                </nav>

                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @php
                            $dashboardUrl = auth()->user()->type === 'admin' ? url('/sadm') : url('/referral');
                        @endphp
                        <x-marketing.button :href="$dashboardUrl" variant="primary">Dashboard</x-marketing.button>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Log in</a>
                        <x-marketing.button href="{{ route('register') }}" variant="primary">Get Started</x-marketing.button>
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <div class="flex md:hidden items-center gap-2">
                    @auth
                        @php
                            $dashboardUrlMobile = auth()->user()->type === 'admin' ? url('/sadm') : url('/referral');
                        @endphp
                        <a href="{{ $dashboardUrlMobile }}" class="text-sm font-medium text-gray-400">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400">Log in</a>
                    @endauth
                    <button type="button" @click="$store.mobileNav.open = ! $store.mobileNav.open" class="inline-flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors" aria-label="Toggle menu" aria-expanded="false" x-data aria-expanded="false" :aria-expanded="$store.mobileNav.open">
                        <svg x-show="!$store.mobileNav.open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="$store.mobileNav.open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </x-marketing.container>

        {{-- Mobile nav (Alpine) --}}
        <div x-data x-show="$store.mobileNav.open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak class="md:hidden border-t border-gray-800 bg-gray-950/95 backdrop-blur-xl" @click.outside="$store.mobileNav.open = false">
            <nav class="px-6 py-4 space-y-1" aria-label="Mobile">
                <a href="{{ route('home') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-gray-300 hover:bg-gray-800/80 hover:text-white">Home</a>
                <a href="{{ route('features') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-gray-300 hover:bg-gray-800/80 hover:text-white">Features</a>
                <a href="{{ route('pricing') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-gray-300 hover:bg-gray-800/80 hover:text-white">Pricing</a>
                <a href="{{ route('faq') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-gray-300 hover:bg-gray-800/80 hover:text-white">FAQ</a>
                @auth
                    <a href="{{ auth()->user()->type === 'admin' ? url('/sadm') : url('/referral') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-400 hover:bg-gray-800/80">Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="block rounded-lg px-4 py-3 text-sm font-medium text-emerald-400 hover:bg-gray-800/80">Get Started</a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-800 bg-gray-950">
        <x-marketing.container class="py-6 md:py-6">
            <div class="flex flex-col gap-10 md:flex-row md:items-start md:justify-between md:gap-20">
                <div class="shrink-0 flex gap-4 items-center">
                    <a href="{{ route('home') }}" class="shrink-0 rounded-lg outline-none ring-gray-700 transition focus-visible:ring-2" aria-label="{{ config('app.name') }} home">
                        <img src="{{ asset('images/referlyst-icon.png') }}" alt="" class="h-10 w-10" width="40" height="40">
                    </a>
                    <div class="text-sm leading-relaxed text-gray-400  pt-1 space-y-1">
                        <p>Turn your customers into your best marketers.</p>
                        <p>Launch referral programs in minutes.</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-x-12 gap-y-10 sm:grid-cols-3 sm:gap-x-20 sm:gap-y-0">
                    <nav aria-label="Product" class="min-w-0">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Product</h3>
                        <ul class="mt-5 space-y-2">
                            <li><a href="{{ route('features') }}" class="inline-block text-sm text-gray-400 hover:text-white transition-colors py-0.5">Features</a></li>
                            <li><a href="{{ route('pricing') }}" class="inline-block text-sm text-gray-400 hover:text-white transition-colors py-0.5">Pricing</a></li>
                        </ul>
                    </nav>
                    <nav aria-label="Company" class="min-w-0">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Company</h3>
                        <ul class="mt-5 space-y-2">
                            <li><a href="{{ route('about') }}" class="inline-block text-sm text-gray-400 hover:text-white transition-colors py-0.5">About</a></li>
                            <li><a href="{{ route('contact') }}" class="inline-block text-sm text-gray-400 hover:text-white transition-colors py-0.5">Contact</a></li>
                        </ul>
                    </nav>
                    <nav aria-label="Legal" class="min-w-0">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Legal</h3>
                        <ul class="mt-5 space-y-2">
                            <li><a href="{{ route('privacy') }}" class="inline-block text-sm text-gray-400 hover:text-white transition-colors py-0.5">Privacy Policy</a></li>
                            <li><a href="{{ route('terms') }}" class="inline-block text-sm text-gray-400 hover:text-white transition-colors py-0.5">Terms of Service</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="mt-6 flex flex-col gap-4 border-t border-gray-800/60 pt-6 md:flex-row md:items-center md:justify-between ">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Referlyst. All rights reserved.</p>
                <p class="text-sm text-gray-500">
                    Part of <a href="https://uphold.ro/en/brands" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors rounded">UPHOLD Group</a>.
                </p>
            </div>
        </x-marketing.container>
    </footer>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
