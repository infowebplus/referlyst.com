<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $brand->name }} – {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen p-6 lg:p-8">
        <main class="mx-auto max-w-2xl">
            <h1 class="mb-2 text-2xl font-medium">{{ $brand->name }}</h1>

            <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">
                Please fill in the form below to submit your referrals. Select the service and add the people you wish to recommend.
            </p>

            @if (session('success'))
                <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @if (! $activePackage)
                <div class="rounded-md border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-200">
                    There is no package allocated to this brand.
                </div>
            @else
                @if ($slotsRemaining === 0)
                    <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-200">
                        No referral slots remaining for this brand at the moment.
                    </div>
                @else
                    <form action="{{ route('landing.submit', ['subdomain' => $brand->subdomain_for_landing_pages]) }}" method="POST" class="space-y-6">
                        @csrf

                        @if ($errors->any())
                            <div class="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
                                <ul class="list-inside list-disc space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="service_id" class="mb-1 block text-sm font-medium">Select service</label>
                            <select
                                id="service_id"
                                name="service_id"
                                required
                                class="w-full rounded-md border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-[#1b1b18] dark:text-[#EDEDEC] focus:border-[#19140035] focus:outline-none focus:ring-1 focus:ring-[#19140035] dark:focus:border-[#62605b] dark:focus:ring-[#62605b]"
                            >
                                <option value="">— Select a service —</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <label class="text-sm font-medium">Recommend to</label>
                                <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Up to {{ $slotsRemaining }} {{ Str::plural('entry', $slotsRemaining) }}</span>
                            </div>

                            <div id="referrers-container" class="space-y-4" data-max="{{ $slotsRemaining }}">
                                <template id="referrer-row-template">
                                    <div class="referrer-row rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-4 shadow-sm">
                                        <div class="mb-3 flex items-center justify-between">
                                            <span class="text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Person <span class="referrer-index">1</span></span>
                                            <button type="button" class="remove-referrer rounded px-2 py-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950" aria-label="Remove">Remove</button>
                                        </div>
                                        <div class="grid gap-3 sm:grid-cols-2">
                                            <div>
                                                <label class="mb-1 block text-xs font-medium">Name</label>
                                                <input type="text" name="recommend_to[__INDEX__][name]" required maxlength="255" class="w-full rounded border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] px-3 py-2 text-sm" placeholder="Name">
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium">Surname</label>
                                                <input type="text" name="recommend_to[__INDEX__][surname]" required maxlength="255" class="w-full rounded border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] px-3 py-2 text-sm" placeholder="Surname">
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium">Phone</label>
                                                <input type="text" name="recommend_to[__INDEX__][phone]" required maxlength="255" class="w-full rounded border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] px-3 py-2 text-sm" placeholder="Phone">
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium">Email</label>
                                                <input type="email" name="recommend_to[__INDEX__][email]" required class="w-full rounded border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#0a0a0a] px-3 py-2 text-sm" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button
                                type="button"
                                id="add-referrer"
                                class="mt-6 inline-flex items-center rounded-md border border-[#19140035] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-4 py-3 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#f5f5f4] dark:hover:bg-[#262625]"
                            >
                                Add another person
                            </button>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-[#1b1b18] dark:bg-[#EDEDEC] px-5 py-3 text-sm font-medium text-white dark:text-[#1b1b18] hover:bg-black dark:hover:bg-white"
                            >
                                Submit referrals
                            </button>
                        </div>
                    </form>
                @endif
            @endif
        </main>

        @if ($activePackage && $slotsRemaining > 0)
            <script>
                (function () {
                    const container = document.getElementById('referrers-container');
                    const template = document.getElementById('referrer-row-template');
                    const addBtn = document.getElementById('add-referrer');
                    const max = parseInt(container.dataset.max, 10);
                    let index = 0;

                    function addRow() {
                        if (container.querySelectorAll('.referrer-row').length >= max) return;
                        const content = template.content.cloneNode(true);
                        const row = content.querySelector('.referrer-row');
                        row.querySelectorAll('[name]').forEach(function (input) {
                            input.name = input.name.replace('__INDEX__', index);
                        });
                        row.querySelector('.referrer-index').textContent = index + 1;
                        row.querySelector('.remove-referrer').addEventListener('click', function () {
                            row.remove();
                            reindex();
                        });
                        container.appendChild(content);
                        index++;
                        reindex();
                        addBtn.disabled = container.querySelectorAll('.referrer-row').length >= max;
                    }

                    function reindex() {
                        container.querySelectorAll('.referrer-row').forEach(function (row, i) {
                            row.querySelector('.referrer-index').textContent = i + 1;
                            row.querySelectorAll('[name]').forEach(function (input) {
                                input.name = input.name.replace(/recommend_to\[\d+\]/, 'recommend_to[' + i + ']');
                            });
                        });
                        index = container.querySelectorAll('.referrer-row').length;
                        addBtn.disabled = index >= max;
                    }

                    addBtn.addEventListener('click', addRow);
                    addRow();
                })();
            </script>
        @endif
    </body>
</html>
