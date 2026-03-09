@extends('layouts.marketing')

@section('title', 'Terms of Service – ' . config('app.name'))

@section('content')
    <section class="bg-gray-950 py-24">
        <x-marketing.container class="max-w-3xl">
            <h1 class="text-5xl font-bold tracking-tight text-white">Terms of Service</h1>
            <p class="mt-4 text-sm text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>

            <div class="mt-14 space-y-12 text-gray-400">
                <div>
                    <h2 class="text-xl font-semibold text-white">1. Agreement to terms</h2>
                    <p class="mt-3 text-lg text-gray-300 leading-relaxed">
                        By accessing or using Referlyst's platform and services, you agree to be bound by these Terms of Service. If you do not agree, do not use our services.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-white">2. Use of the service</h2>
                    <p class="mt-3 text-lg text-gray-300 leading-relaxed">
                        You may use Referlyst only for lawful purposes and in accordance with these terms. You are responsible for maintaining the security of your account and for all activity under your account. You must not use the service to violate any laws, infringe others' rights, or send spam or fraudulent referrals.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-white">3. Payment and plans</h2>
                    <p class="mt-3 text-lg text-gray-300 leading-relaxed">
                        Paid plans are billed according to the pricing in effect at the time of purchase. You may upgrade or downgrade your plan; changes apply at the next billing cycle. Refunds are handled in accordance with our refund policy.
                    </p>
                    <p class="mt-4 text-lg text-gray-300 leading-relaxed">
                        Add-ons are sold as annual, one-time payments per brand. <strong class="text-white">We do not offer refunds for add-ons</strong>, as they are non-recurring purchases valid for the paid period once the order is completed.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-white">4. Intellectual property</h2>
                    <p class="mt-3 text-lg text-gray-300 leading-relaxed">
                        Referlyst and its content, features, and functionality are owned by us and are protected by copyright, trademark, and other laws. You may not copy, modify, or create derivative works of our service without our permission.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-white">5. Limitation of liability</h2>
                    <p class="mt-3 text-lg text-gray-300 leading-relaxed">
                        To the maximum extent permitted by law, Referlyst shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the service. Our total liability shall not exceed the amount you paid us in the twelve months preceding the claim.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-white">6. Contact</h2>
                    <p class="mt-3 text-lg text-gray-300 leading-relaxed">
                        For questions about these Terms of Service, contact us via our <a href="{{ route('contact') }}" class="text-blue-400 hover:text-blue-300 transition-colors">Contact</a> page.
                    </p>
                </div>
            </div>
        </x-marketing.container>
    </section>
@endsection
