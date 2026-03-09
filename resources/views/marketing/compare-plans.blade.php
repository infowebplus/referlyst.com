@extends('layouts.marketing')

@section('title', 'Compare plans – ' . config('app.name'))
@section('meta_description', 'Compare Referlyst plans. See what’s included in Free, Starter, and Growth.')

@section('content')
    <section class="bg-gray-950">
        <x-marketing.container>
            <div class="mx-auto max-w-4xl pt-20 pb-6 text-center sm:pt-28 sm:pb-8">
                <p class="text-sm font-semibold uppercase tracking-widest text-gray-500">Compare</p>
                <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl sm:tracking-[-0.02em]">
                    Compare plans
                </h1>
                <p class="mt-6 text-lg leading-relaxed text-gray-400 sm:text-xl">
                    See what’s included in each plan.
                </p>
                <p class="mt-6">
                    <a href="{{ route('pricing') }}" class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors">← Back to pricing</a>
                </p>
            </div>

            @if ($packages->isEmpty())
                <div class="mx-auto mt-16 max-w-lg rounded-2xl border border-gray-800 bg-gray-900 p-14 text-center">
                    <p class="text-gray-400">No packages are available. Check back soon or <a href="{{ route('pricing') }}" class="text-blue-400 hover:underline">view pricing</a>.</p>
                </div>
            @else
                <div class="mt-12 overflow-hidden rounded-2xl border border-gray-800 bg-gray-900/50 sm:mt-16">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[640px] border-collapse text-left">
                            <thead>
                                <tr class="border-b border-gray-800 bg-gray-900/80">
                                    <th scope="col" class="py-5 pl-8 pr-6 text-sm font-medium text-gray-400">Feature</th>
                                    @foreach ($packages as $p)
                                        <th scope="col" class="py-5 px-6 text-base font-semibold tracking-tight text-white">{{ $p->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/80">
                                <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                                    <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">Domain</td>
                                    @foreach ($packages as $p)
                                        <td class="py-5 px-6 text-[15px] text-gray-400">{{ $p->domain_structure }}</td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                                    <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">Branding</td>
                                    @foreach ($packages as $p)
                                        <td class="py-5 px-6">
                                            @if ($p->custom_branding)
                                                <span class="text-emerald-500">✓</span>
                                            @else
                                                <span class="text-gray-600">❌</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                                    <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">Landing page</td>
                                    @foreach ($packages as $p)
                                        <td class="py-5 px-6">
                                            @if ($p->custom_landing_page)
                                                <span class="text-emerald-500">✓</span>
                                            @else
                                                <span class="text-gray-600">❌</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                                    <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">Referlyst branding</td>
                                    @foreach ($packages as $p)
                                        <td class="py-5 px-6">
                                            @if ($p->slug === 'free')
                                                <span class="text-emerald-500">✓</span>
                                            @else
                                                <span class="text-gray-600">❌</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                                    <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">Brands</td>
                                    @foreach ($packages as $p)
                                        <td class="py-5 px-6 text-[15px] text-gray-400">{{ $p->no_of_referrers_brands > 0 ? $p->no_of_referrers_brands : 'Unlimited' }}</td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                                    <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">Referrers</td>
                                    @foreach ($packages as $p)
                                        <td class="py-5 px-6 text-[15px] text-gray-400">{{ $p->no_of_referrers > 0 ? $p->no_of_referrers : 'Unlimited' }}</td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                                    <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">CSV export</td>
                                    @foreach ($packages as $p)
                                        <td class="py-5 px-6">
                                            @if ($p->export_csv)
                                                <span class="text-emerald-500">✓</span>
                                            @else
                                                <span class="text-gray-600">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                                    <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">Email notifications</td>
                                    @foreach ($packages as $p)
                                        <td class="py-5 px-6">
                                            @if ($p->email_notifications)
                                                <span class="text-emerald-500">✓</span>
                                            @else
                                                <span class="text-gray-600">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <p class="mt-12 text-center">
                    <x-marketing.button href="{{ route('pricing') }}" variant="primary" class="px-6 py-3">View pricing</x-marketing.button>
                </p>
            @endif
        </x-marketing.container>
    </section>
@endsection
