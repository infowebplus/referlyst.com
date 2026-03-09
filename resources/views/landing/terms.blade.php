@extends('landing.layout')

@section('title', 'Terms & conditions – ' . $brand->name)

@section('content')

    {{-- ── Hero ── --}}
    <section style="background:var(--bg-surface);border-bottom:1px solid var(--border);">
        <div class="l-wrap" style="padding-top:40px;padding-bottom:32px;display:flex;align-items:center;gap:16px;">
            @if ($logoUrl ?? null)
                <img src="{{ $logoUrl }}" alt="{{ $brand->name }}"
                     style="height:52px;width:auto;max-width:160px;object-fit:contain;border-radius:12px;flex-shrink:0;"
                     onerror="this.style.display='none';document.getElementById('terms-fallback').style.display='flex';">
                <span id="terms-fallback" class="l-logo-mark"
                      style="display:none;width:52px;height:52px;font-size:20px;border-radius:12px;flex-shrink:0;">
                    {{ Str::upper(Str::substr($brand->name, 0, 1)) }}
                </span>
            @else
                <span class="l-logo-mark"
                      style="display:flex;width:52px;height:52px;font-size:20px;border-radius:12px;flex-shrink:0;">
                    {{ Str::upper(Str::substr($brand->name, 0, 1)) }}
                </span>
            @endif
            <div>
                <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);">{{ $brand->name }}</p>
                <h1 style="font-size:22px;font-weight:800;letter-spacing:-.03em;color:var(--text-primary);margin-top:2px;">Terms &amp; conditions</h1>
            </div>
        </div>
    </section>

    {{-- ── Content ── --}}
    <main style="flex:1;">
        <div class="l-wrap" style="padding-top:28px;padding-bottom:48px;display:flex;flex-direction:column;gap:12px;">

            {{-- Brand referral terms --}}
            <div class="l-card">
                <div class="l-card-hd">
                    <div style="display:flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;flex-shrink:0;background:color-mix(in srgb,var(--brand-primary) 12%,transparent);">
                        <svg style="width:15px;height:15px;color:var(--brand-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $brand->name }} referral terms</h2>
                        <p style="font-size:12px;color:var(--text-muted);margin-top:1px;">Specific to this referral programme</p>
                    </div>
                </div>
                <div class="l-card-bd">
                    @if ($termsAndConditionsUrl)
                        <a href="{{ $termsAndConditionsUrl }}" target="_blank" rel="noopener noreferrer"
                           style="display:flex;align-items:center;gap:12px;padding:13px 15px;border-radius:10px;background:var(--bg-subtle);border:1px solid var(--border);text-decoration:none;transition:border-color .15s;"
                           onmouseover="this.style.borderColor='var(--brand-primary)'"
                           onmouseout="this.style.borderColor='var(--border)'">
                            <svg style="width:16px;height:16px;flex-shrink:0;color:var(--brand-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                            <div style="min-width:0;flex:1;">
                                <p style="font-size:13px;font-weight:600;color:var(--text-primary);">View full terms</p>
                                <p style="font-size:12px;color:var(--text-muted);margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $termsAndConditionsUrl }}</p>
                            </div>
                            <svg style="width:14px;height:14px;flex-shrink:0;color:var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </a>
                    @else
                        <div style="font-size:14px;line-height:1.65;color:var(--text-secondary);display:flex;flex-direction:column;gap:10px;">
                            <p>By submitting a referral through this programme, you confirm that:</p>
                            <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;">
                                @foreach ([
                                    'The information you provide is accurate and truthful.',
                                    'You have the consent of the people you are referring to share their details with ' . $brand->name . '.',
                                    'You understand that ' . $brand->name . ' will contact referred individuals for the purposes of this programme.',
                                ] as $point)
                                    <li style="display:flex;align-items:flex-start;gap:8px;">
                                        <svg style="width:15px;height:15px;flex-shrink:0;margin-top:3px;color:var(--brand-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                        {{ $point }}
                                    </li>
                                @endforeach
                            </ul>
                            @if ($brand->correspondence_email)
                                <p style="padding-top:10px;border-top:1px solid var(--border);">
                                    Questions? Contact
                                    <a href="mailto:{{ $brand->correspondence_email }}" class="l-link-brand">{{ $brand->correspondence_email }}</a>
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Referlyst platform --}}
            <div class="l-card">
                <div class="l-card-hd">
                    <div style="display:flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;flex-shrink:0;background:var(--bg-subtle);border:1px solid var(--border);">
                        <svg style="width:15px;height:15px;color:var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253M3 12c0 .778.099 1.533.284 2.253"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="font-size:14px;font-weight:600;color:var(--text-primary);">Referlyst platform</h2>
                        <p style="font-size:12px;color:var(--text-muted);margin-top:1px;">Terms &amp; privacy for the platform powering this form</p>
                    </div>
                </div>
                <div class="l-card-bd" style="display:flex;flex-wrap:wrap;gap:8px;">
                    @if (isset($referlystTermsUrl) && $referlystTermsUrl)
                        <a href="{{ $referlystTermsUrl }}" target="_blank" rel="noopener noreferrer" class="l-btn-ghost">
                            Terms of service
                            <svg style="width:12px;height:12px;opacity:.55;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endif
                    @if (isset($referlystPrivacyUrl) && $referlystPrivacyUrl)
                        <a href="{{ $referlystPrivacyUrl }}" target="_blank" rel="noopener noreferrer" class="l-btn-ghost">
                            Privacy policy
                            <svg style="width:12px;height:12px;opacity:.55;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </main>

@endsection
