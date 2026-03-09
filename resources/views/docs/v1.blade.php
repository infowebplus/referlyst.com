@extends('layouts.marketing')

@section('title', 'API v1 Reference – ' . config('app.name'))
@section('meta_description', 'Full API reference for the Referlyst v1 referral API. Authentication, endpoints, request/response schemas and error codes.')

@php
    $nav = [
        [
            'group' => 'Overview',
            'links' => [
                ['id' => 'authentication', 'label' => 'Authentication'],
                ['id' => 'base-url',        'label' => 'Base URL'],
            ],
        ],
        [
            'group' => 'Endpoints',
            'links' => [
                ['id' => 'get-referral-form', 'label' => 'GET /referral-form'],
                ['id' => 'post-referrals',    'label' => 'POST /referrals'],
            ],
        ],
        [
            'group' => 'Reference',
            'links' => [
                ['id' => 'errors', 'label' => 'Error codes'],
            ],
        ],
    ];

    $errorCodes = [
        ['code' => '200', 'color' => 'green', 'description' => 'OK — request succeeded.'],
        ['code' => '201', 'color' => 'green', 'description' => 'Created — referral(s) stored.'],
        ['code' => '401', 'color' => 'red',   'description' => 'Unauthorized — missing or invalid API token.'],
        ['code' => '403', 'color' => 'amber', 'description' => 'Forbidden — API Access addon expired or inactive for this brand.'],
        ['code' => '422', 'color' => 'amber', 'description' => 'Unprocessable — validation failed. Response body includes an <code class="rounded bg-gray-800 px-1 text-gray-300">errors</code> object with field-level messages.'],
    ];
@endphp

@section('content')
<div class="border-b border-gray-800/60 bg-gray-950">
    <x-marketing.container class="max-w-6xl py-10 md:py-14">

        {{-- ─── Page header ─── --}}
        <div class="mb-10 flex flex-wrap items-start justify-between gap-4">
            <div>
                <div class="mb-3 flex items-center gap-2">
                    <span class="inline-flex items-center rounded-full border border-gray-700 bg-gray-800/60 px-2.5 py-0.5 text-xs font-medium text-gray-400">v1</span>
                    <span class="text-xs text-gray-600">/</span>
                    <span class="text-xs text-gray-500">REST</span>
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-white md:text-4xl">API Reference</h1>
                <p class="mt-2 max-w-xl text-lg text-gray-400">
                    Programmatically fetch referral form data and submit referrals.
                    Requires the <strong class="font-medium text-gray-300">API Access</strong> add-on enabled for your brand.
                </p>
            </div>

            {{-- Mobile jump-nav --}}
            <nav class="flex flex-wrap gap-x-5 gap-y-1 text-sm lg:hidden" aria-label="Page sections">
                @foreach ($nav as $group)
                    @foreach ($group['links'] as $link)
                        <a href="#{{ $link['id'] }}" class="text-gray-400 hover:text-white transition-colors">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                @endforeach
            </nav>
        </div>

        {{-- ─── Two-column docs layout ─── --}}
        <x-docs.layout :nav="$nav" version="1">

            {{-- ════════════════════════════════════
                 AUTHENTICATION
            ════════════════════════════════════ --}}
            <x-docs.section id="authentication" title="Authentication">
                <p class="text-gray-400">
                    Every request must carry an <code class="rounded bg-gray-800 px-1.5 py-0.5 text-sm font-mono text-gray-300">Authorization</code> header with a
                    <strong class="font-medium text-gray-300">Bearer token</strong>. Generate tokens in the Referral dashboard under
                    <strong class="font-medium text-gray-300">Brand → Edit → API Access</strong>.
                    Multiple tokens per brand are supported — revoke individually as needed.
                </p>

                <x-docs.code-block label="Header">Authorization: Bearer YOUR_API_KEY</x-docs.code-block>

                <div class="rounded-lg border border-amber-500/20 bg-amber-500/5 p-4 text-sm text-amber-400/90">
                    <strong class="font-semibold">Keep tokens secret.</strong>
                    Treat them like passwords — never expose them in client-side code or public repositories.
                </div>
            </x-docs.section>

            {{-- ════════════════════════════════════
                 BASE URL
            ════════════════════════════════════ --}}
            <x-docs.section id="base-url" title="Base URL">
                <p class="text-gray-400">All endpoints are relative to:</p>

                <div class="flex items-center gap-3 rounded-lg border border-gray-800 bg-[#0d1117] px-4 py-3 font-mono text-sm text-gray-300">
                    <span class="text-gray-600">//</span>
                    <span>{{ $baseUrl }}/api/v1</span>
                </div>

                <p class="text-sm text-gray-500">
                    All requests and responses use <strong class="text-gray-400">JSON</strong>.
                    Set <code class="rounded bg-gray-800 px-1 text-gray-400">Content-Type: application/json</code> on requests with a body.
                </p>
            </x-docs.section>

            {{-- ════════════════════════════════════
                 GET /referral-form
            ════════════════════════════════════ --}}
            <x-docs.section id="get-referral-form" title="GET /referral-form">
                <x-docs.endpoint
                    method="GET"
                    path="/referral-form"
                    summary="Returns everything needed to render a referral form: brand details, available services with commission rates, remaining referral slots, and the submit URL."
                />

                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Request</h3>
                <p class="text-sm text-gray-400">No request body. Include the Authorization header.</p>

                <x-docs.code-block label="cURL">curl -X GET {{ $referralFormUrl }} \
  -H "Authorization: Bearer YOUR_API_KEY"</x-docs.code-block>

                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Response — <span class="font-mono text-emerald-400">200 OK</span></h3>

                <x-docs.code-block label="JSON">
{
  "brand": {
    "id": 1,
    "name": "Acme Coffee",
    "website": "https://acme.example.com",
    "terms_and_conditions": "https://acme.example.com/terms",
    "ca": null,
    "landing_page_url": "https://acme.referlyst.test",
    "primary_color": "#2d5016"
  },
  "services": [
    {
      "id": 1,
      "name": "Coffee subscription",
      "commission_type": "percent",
      "commission": 10,
      "texts": null
    }
  ],
  "max_referrers": 250,
  "slots_remaining": 248,
  "has_package": true,
  "submit_url": "{{ $baseUrl }}/api/v1/referrals"
}</x-docs.code-block>

                <x-docs.params-table :headers="['Field', 'Type', 'Description']">
                    <x-docs.field-row field="brand"           type="object"  description="Brand details." />
                    <x-docs.field-row field="brand.id"        type="integer" description="Unique brand ID." :nested="true" />
                    <x-docs.field-row field="brand.primary_color" type="string" description="Hex color for theming the form (#rrggbb)." :nested="true" />
                    <x-docs.field-row field="brand.landing_page_url" type="string" description="Public landing page URL for this brand." :nested="true" />
                    <x-docs.field-row field="services"        type="array"   description="Services the referrer can choose from." />
                    <x-docs.field-row field="max_referrers"   type="integer" description="Total referral slots on the active package." />
                    <x-docs.field-row field="slots_remaining" type="integer" description="How many new referrals can still be submitted." />
                    <x-docs.field-row field="submit_url"      type="string"  description="Convenience URL for POST /referrals." />
                </x-docs.params-table>
            </x-docs.section>

            {{-- ════════════════════════════════════
                 POST /referrals
            ════════════════════════════════════ --}}
            <x-docs.section id="post-referrals" title="POST /referrals">
                <x-docs.endpoint
                    method="POST"
                    path="/referrals"
                    summary="Submit one or more referrals for the authenticated brand. The number of entries in recommend_to cannot exceed slots_remaining from the GET response."
                />

                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Request body</h3>

                <x-docs.params-table>
                    <x-docs.field-row field="service_id"             type="integer" :required="true" description="ID of the service (from GET /referral-form)." />
                    <x-docs.field-row field="recommend_to"           type="array"   :required="true" description="List of people to refer. Each entry is an object with the fields below." />
                    <x-docs.field-row field="recommend_to[].name"    type="string"  :required="true" description="First name. Max 255 characters." :nested="true" />
                    <x-docs.field-row field="recommend_to[].surname" type="string"  :required="true" description="Last name. Max 255 characters." :nested="true" />
                    <x-docs.field-row field="recommend_to[].phone"   type="string"  :required="true" description="Phone number. Max 255 characters." :nested="true" />
                    <x-docs.field-row field="recommend_to[].email"   type="string"  :required="true" description="Valid email address." :nested="true" />
                </x-docs.params-table>

                <x-docs.code-block label="cURL">curl -X POST {{ $referralsUrl }} \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "service_id": 1,
    "recommend_to": [
      {
        "name": "Jane",
        "surname": "Doe",
        "phone": "+1234567890",
        "email": "jane@example.com"
      }
    ]
  }'</x-docs.code-block>

                <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Response — <span class="font-mono text-emerald-400">201 Created</span></h3>

                <x-docs.code-block label="JSON">{
  "message": "One referral has been submitted successfully.",
  "created": 1
}</x-docs.code-block>
            </x-docs.section>

            {{-- ════════════════════════════════════
                 ERRORS
            ════════════════════════════════════ --}}
            <x-docs.section id="errors" title="Error codes">
                <p class="text-gray-400">
                    The API uses standard HTTP status codes. All error responses return JSON with a
                    <code class="rounded bg-gray-800 px-1.5 py-0.5 font-mono text-sm text-gray-300">message</code> field.
                    Validation errors (422) additionally include an
                    <code class="rounded bg-gray-800 px-1.5 py-0.5 font-mono text-sm text-gray-300">errors</code> object keyed by field name.
                </p>

                <x-docs.response-codes :codes="$errorCodes" />

                <x-docs.code-block label="Example 422 response">{
  "message": "The given data was invalid.",
  "errors": {
    "recommend_to.0.email": ["The email field must be a valid email address."],
    "service_id": ["The selected service id is invalid."]
  }
}</x-docs.code-block>
            </x-docs.section>

        </x-docs.layout>
    </x-marketing.container>
</div>
@endsection
