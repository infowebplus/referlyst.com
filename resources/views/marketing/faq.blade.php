@extends('layouts.marketing')

@section('title', 'FAQ – ' . config('app.name'))
@section('meta_description', 'Frequently asked questions about Referlyst and referral programs.')

@section('content')
    <section class="bg-gray-950 py-24">
        <x-marketing.container class="max-w-3xl">
            <div class="mx-auto text-center">
                <h1 class="text-5xl font-bold tracking-tight text-white sm:text-5xl">
                    Frequently Asked Questions
                </h1>
                <p class="mt-6 text-lg text-gray-300">Quick answers about Referlyst and referral marketing.</p>
            </div>

            <div class="mt-20 space-y-2" x-data="{ openIndex: 0 }">
                @foreach ([
                    [
                        'question' => 'What is Referlyst?',
                        'answer' => 'Referlyst is a platform that helps businesses create and manage referral programs. You set the rewards and rules; we handle tracking, links, and payouts.',
                    ],
                    [
                        'question' => 'How do referral programs work?',
                        'answer' => 'Customers receive a unique referral link they can share with friends. When someone signs up or purchases through that link, the referral is tracked and rewards can be issued automatically.',
                    ],
                    [
                        'question' => 'Do I need technical knowledge to use Referlyst?',
                        'answer' => 'No. Referlyst is designed to be simple and easy to set up. You can launch a program from the dashboard without writing code. Developers can use our API for deeper integration.',
                    ],
                    [
                        'question' => 'Can I upgrade my plan later?',
                        'answer' => 'Yes. You can upgrade your plan at any time. Changes apply at the next billing cycle. No long-term lock-in.',
                    ],
                    [
                        'question' => 'Do referral programs really work?',
                        'answer' => 'Yes. Referral programs are one of the most effective growth channels because people trust recommendations from friends. Referred customers often convert at 3–5x the rate of other channels.',
                    ],
                ] as $index => $faq)
                    <div
                        class="rounded-xl border border-gray-800 bg-gray-900 transition-colors hover:border-gray-700"
                        :class="{ 'ring-1 ring-blue-500/50 border-blue-500/50': openIndex === {{ $index }} }"
                    >
                        <button
                            type="button"
                            @click="openIndex = openIndex === {{ $index }} ? -1 : {{ $index }}"
                            class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left"
                            :aria-expanded="openIndex === {{ $index }}"
                            aria-controls="faq-answer-{{ $index }}"
                            :id="'faq-question-{{ $index }}'"
                        >
                            <span class="text-lg font-semibold text-white">{{ $faq['question'] }}</span>
                            <span class="shrink-0 text-gray-400 transition-transform" :class="openIndex === {{ $index }} ? 'rotate-180' : ''">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>
                        <div
                            id="faq-answer-{{ $index }}"
                            role="region"
                            aria-labelledby="faq-question-{{ $index }}"
                            x-show="openIndex === {{ $index }}"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="border-t border-gray-800 overflow-hidden"
                            x-cloak
                        >
                            <p class="px-6 pb-5 pt-2 text-gray-400">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-20 rounded-2xl border border-gray-800 bg-gray-900 px-8 py-10 text-center">
                <p class="text-gray-400">Still have questions?</p>
                <x-marketing.button href="{{ route('contact') }}" variant="secondary" class="mt-6 px-6 py-3">Contact us</x-marketing.button>
            </div>
        </x-marketing.container>
    </section>
@endsection
