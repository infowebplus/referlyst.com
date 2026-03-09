@extends('layouts.marketing')

@section('title', 'About – ' . config('app.name'))
@section('meta_description', 'Referlyst helps businesses grow through referral marketing. Simple tools to turn customers into promoters.')

@section('content')
    <section class="bg-gray-950 py-24">
        <x-marketing.container class="max-w-3xl">
            <h1 class="text-5xl font-bold tracking-tight text-white">About Referlyst</h1>
            <p class="mt-6 text-lg text-gray-300 leading-relaxed">
                Referlyst is a platform that helps businesses grow through referral marketing. Referlyst is part of <a href="https://uphold.ro/en/brands" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-300 transition-colors">UPHOLD Group</a>.
            </p>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Our goal is simple: make it easy for companies to turn satisfied customers into active promoters. Instead of relying only on ads, businesses can grow through the most trusted form of marketing — recommendations from real people.
            </p>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Referlyst provides the tools needed to create, manage, and scale referral programs without complex setup or technical work.
            </p>

            <h2 class="mt-14 text-2xl font-semibold text-white">The problem</h2>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Most companies know that referrals are powerful, but running a referral program is often complicated. Businesses usually struggle with:
            </p>
            <ul class="mt-4 list-disc list-inside space-y-2 text-lg text-gray-300">
                <li>tracking referrals correctly</li>
                <li>managing rewards</li>
                <li>preventing fraud or abuse</li>
                <li>creating a smooth referral experience for users</li>
            </ul>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Many existing tools are either too complex, too expensive, or built for large enterprises. Smaller teams and growing companies need a solution that is simple, affordable, and easy to launch.
            </p>

            <h2 class="mt-14 text-2xl font-semibold text-white">Our solution</h2>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Referlyst was created to simplify referral marketing. With Referlyst, businesses can launch a referral program in minutes and start rewarding users for sharing their product or service.
            </p>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                The platform handles the technical side of referral tracking while businesses focus on growth. Our goal is to provide a clean, modern platform that helps teams grow organically through word-of-mouth.
            </p>

            <h2 class="mt-14 text-2xl font-semibold text-white">What Referlyst offers</h2>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Referlyst provides a complete referral management platform including:
            </p>
            <ul class="mt-6 space-y-4 text-lg text-gray-300">
                <li><strong class="text-white">Referral tracking</strong> — Track every referral with unique links.</li>
                <li><strong class="text-white">Commission management</strong> — Automatically calculate rewards and commissions.</li>
                <li><strong class="text-white">Brand customization</strong> — Customize the referral experience with logos, colors, and branding.</li>
                <li><strong class="text-white">Flexible domains</strong> — Use Referlyst hosted links, subdomains, or your own custom domain.</li>
                <li><strong class="text-white">Referral landing pages</strong> — Create a dedicated landing page where customers can join and share referral links.</li>
                <li><strong class="text-white">Analytics and insights</strong> — Understand how your referral program performs and where growth comes from.</li>
            </ul>

            <h2 class="mt-14 text-2xl font-semibold text-white">Who Referlyst is for</h2>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Referlyst is built for startups, SaaS companies, online businesses, and brands that want to grow through community and referrals.
            </p>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Whether you're launching your first referral program or scaling an existing one, Referlyst helps simplify the process.
            </p>

            <h2 class="mt-14 text-2xl font-semibold text-white">Our philosophy</h2>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                We believe growth should be simple, transparent, and driven by real people. Referral marketing works because people trust recommendations from friends and colleagues far more than advertisements. Referlyst exists to make this kind of growth accessible to businesses of all sizes.
            </p>

            <h2 class="mt-14 text-2xl font-semibold text-white">Looking ahead</h2>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Referlyst is continuously evolving. Our mission is to build the most accessible and developer-friendly referral platform available. We aim to help businesses grow sustainably through authentic recommendations and long-term customer relationships.
            </p>

            <h2 class="mt-14 text-2xl font-semibold text-white">Summary</h2>
            <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                Referlyst helps businesses turn customers into promoters. By simplifying referral program management, companies can unlock one of the most powerful and trusted growth channels available.
            </p>

            <div class="mt-14">
                <x-marketing.button href="{{ route('register') }}" variant="primary" class="px-6 py-3">Get Started</x-marketing.button>
            </div>
        </x-marketing.container>
    </section>
@endsection
