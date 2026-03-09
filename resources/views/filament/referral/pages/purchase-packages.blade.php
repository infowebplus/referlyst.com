<x-filament-panels::page>
    <div class="purchase-flow mx-auto max-w-xl">
        {{-- Header --}}
        <header class="mb-8 text-center sm:mb-10 sm:text-left">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                {{ $this->getTitle() }}
            </h1>
            <p class="mt-2 text-[15px] leading-relaxed text-gray-500 dark:text-gray-400">
                Choose your brand, plan or add-ons. Secure payment via Stripe.
            </p>
        </header>

        {{-- Card: form wizard --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-md dark:border-white/10 dark:bg-gray-900/50 sm:rounded-3xl">
            <div class="p-6 sm:p-8 lg:p-10">
                {{ $this->content }}
            </div>
        </div>

        {{-- Trust line --}}
        <p class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">
            Secured by Stripe · No card stored on our servers
        </p>
    </div>

    <style>
        /* Purchase flow: clean, spacious, modern */
        .purchase-flow {
            padding-bottom: 2rem;
        }

        .purchase-flow .fi-fo-wizard-steps {
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .purchase-flow .fi-fo-wizard-step {
            padding: 0.25rem 0;
        }

        .purchase-flow .fi-fo-wizard-step-heading {
            font-size: 1rem;
            font-weight: 600;
            color: rgb(17 24 39);
        }

        .dark .purchase-flow .fi-fo-wizard-step-heading {
            color: rgb(243 244 246);
        }

        .purchase-flow .fi-fo-wizard-step-description {
            font-size: 0.875rem;
            color: rgb(107 114 128);
            margin-top: 0.25rem;
        }

        .dark .purchase-flow .fi-fo-wizard-step-description {
            color: rgb(156 163 175);
        }

        .purchase-flow .fi-fo-wizard-step-content {
            padding: 0.75rem 0 0;
        }

        .purchase-flow [data-field-wrapper] {
            margin-bottom: 0.5rem;
        }

        .purchase-flow [data-field-wrapper] > label:not([class*="sr-only"]) {
            font-size: 0.875rem;
            font-weight: 500;
            color: rgb(75 85 99);
        }

        .dark .purchase-flow [data-field-wrapper] > label:not([class*="sr-only"]) {
            color: rgb(209 213 219);
        }

        .purchase-flow select,
        .purchase-flow [role="combobox"] {
            min-height: 3rem;
            padding: 0.625rem 1rem;
            border-radius: 12px;
            font-size: 0.9375rem;
        }

        .purchase-flow .fi-fo-checkbox-list {
            gap: 0.5rem;
        }

        .purchase-flow .fi-fo-checkbox-list > div {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid rgb(229 231 235);
            transition: border-color 0.15s, background-color 0.15s;
        }

        .dark .purchase-flow .fi-fo-checkbox-list > div {
            border-color: rgb(55 65 81);
        }

        .purchase-flow .fi-fo-checkbox-list > div:hover {
            border-color: rgb(156 163 175);
            background-color: rgb(249 250 251);
        }

        .dark .purchase-flow .fi-fo-checkbox-list > div:hover {
            border-color: rgb(75 85 99);
            background-color: rgb(31 41 55);
        }

        /* Summary block */
        .purchase-flow .purchase-summary-box {
            background: rgb(249 250 251);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-top: 0.5rem;
        }

        .dark .purchase-flow .purchase-summary-box {
            background: rgb(31 41 55);
        }

        .purchase-flow .purchase-summary-title {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: rgb(107 114 128);
            margin-bottom: 1rem;
        }

        .dark .purchase-flow .purchase-summary-title {
            color: rgb(156 163 175);
        }

        .purchase-flow .purchase-summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .purchase-flow .purchase-summary-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            font-size: 0.9375rem;
            color: rgb(55 65 81);
            border-bottom: 1px solid rgb(229 231 235);
        }

        .dark .purchase-flow .purchase-summary-list li {
            color: rgb(209 213 219);
            border-bottom-color: rgb(55 65 81);
        }

        .purchase-flow .purchase-summary-list li:last-of-type:not(.purchase-summary-total) {
            border-bottom: none;
        }

        .purchase-flow .purchase-summary-list .purchase-summary-total {
            margin-top: 0.5rem;
            padding-top: 1rem;
            margin-bottom: 0;
            font-weight: 700;
            font-size: 1.125rem;
            color: rgb(17 24 39);
            border-top: 2px solid rgb(229 231 235);
            border-bottom: none;
        }

        .dark .purchase-flow .purchase-summary-list .purchase-summary-total {
            color: rgb(243 244 246);
            border-top-color: rgb(55 65 81);
        }

        /* Primary CTA */
        .purchase-flow .purchase-flow-cta {
            width: 100%;
            margin-top: 1.5rem;
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 14px;
            transition: transform 0.1s, box-shadow 0.2s;
        }

        .purchase-flow .purchase-flow-cta:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }

        .dark .purchase-flow .purchase-flow-cta:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
        }
    </style>
</x-filament-panels::page>
