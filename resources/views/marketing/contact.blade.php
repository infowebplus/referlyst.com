@extends('layouts.marketing')

@section('title', 'Contact – ' . config('app.name'))

@section('content')
    <section class="bg-gray-950 py-24">
        <x-marketing.container class="max-w-5xl">
            <div class="mx-auto max-w-2xl text-center">
                <h1 class="text-5xl font-bold tracking-tight text-white">Contact us</h1>
                <p class="mt-6 text-lg leading-relaxed text-gray-300">
                    Have a question or want to learn more? Send us a message and we'll get back to you.
                </p>
            </div>

            @if (session('contact_success'))
                <div class="mx-auto mt-8 max-w-2xl rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-6 py-4 text-center text-emerald-400">
                    Thank you for your message. We'll respond within 1–2 business days.
                </div>
            @endif

            <div class="mt-16 grid gap-10 lg:grid-cols-2 lg:gap-14">
                {{-- Contact info column (left) --}}
                <div class="lg:order-1">
                    <div class="sticky top-24 space-y-6">
                        <div class="rounded-2xl border border-gray-800 bg-gray-900/80 p-8">
                            <h2 class="text-lg font-semibold text-white">Or email us</h2>
                            <p class="mt-3 text-gray-400">
                                General inquiries and support
                            </p>
                            <a href="mailto:hello@referlyst.com" class="mt-2 inline-block text-blue-400 hover:text-blue-300 transition-colors">hello@referlyst.com</a>
                        </div>
                        <div class="rounded-2xl border border-gray-800 bg-gray-900/80 p-8">
                            <h2 class="text-lg font-semibold text-white">Sales and partnerships</h2>
                            <p class="mt-3 text-gray-400">
                                Pricing, enterprise plans, partnerships
                            </p>
                            <a href="mailto:sales@referlyst.com" class="mt-2 inline-block text-blue-400 hover:text-blue-300 transition-colors">sales@referlyst.com</a>
                        </div>
                        <div class="rounded-2xl border border-gray-800 bg-gray-900/80 p-8">
                            <h2 class="text-lg font-semibold text-white">Part of UPHOLD Group</h2>
                            <p class="mt-3 text-gray-400">
                                Referlyst is part of <a href="https://uphold.ro/en/brands" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-300 transition-colors">UPHOLD Group</a>.
                            </p>
                        </div>
                        <p class="text-sm text-gray-500">
                            We typically respond within 1–2 business days.
                        </p>
                    </div>
                </div>

                {{-- Form column (right) --}}
                <div class="lg:order-2">
                    <form action="{{ route('contact.submit') }}" method="POST" class="rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-xl">
                        @csrf

                        <div class="space-y-6">
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old('name') }}"
                                        required
                                        autocomplete="name"
                                        class="mt-2 block w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('name') border-red-500/50 focus:border-red-500 focus:ring-red-500 @enderror"
                                        placeholder="Your name"
                                    />
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        class="mt-2 block w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('email') border-red-500/50 focus:border-red-500 focus:ring-red-500 @enderror"
                                        placeholder="you@example.com"
                                    />
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-300">Subject</label>
                                <input
                                    type="text"
                                    name="subject"
                                    id="subject"
                                    value="{{ old('subject') }}"
                                    required
                                    class="mt-2 block w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('subject') border-red-500/50 focus:border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="What is this about?"
                                />
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-300">Message</label>
                                <textarea
                                    name="message"
                                    id="message"
                                    rows="5"
                                    required
                                    class="mt-2 block w-full rounded-lg border border-gray-700 bg-gray-950 px-4 py-3 text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('message') border-red-500/50 focus:border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="Your message..."
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8">
                            <button
                                type="submit"
                                class="w-full rounded-lg bg-blue-600 px-6 py-3 font-medium text-white shadow-lg shadow-blue-500/20 transition-colors hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-950 sm:w-auto sm:min-w-[160px]"
                            >
                                Send message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-16 flex justify-center border-t border-gray-800 pt-10">
                <x-marketing.button href="{{ route('home') }}" variant="secondary" class="px-6 py-3">Back to home</x-marketing.button>
            </div>
        </x-marketing.container>
    </section>
@endsection
