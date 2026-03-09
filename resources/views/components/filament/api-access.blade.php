@props([
    'tokens' => [],
    'landingUrl' => null,
    'landingUrlDisplay' => '— Set subdomain/path above first.',
    'expiry' => null,
    'docsUrl' => '',
    'getUrl' => '',
    'postUrl' => '',
])

@php $hasKeys = count($tokens) > 0; @endphp

{{--
    Scoped CSS for the API Access widget. Uses .rly-* classes to avoid collisions with
    Filament's own stylesheet. Dark-mode variants are included inline.
--}}
<style id="rly-api">
    .rly-api{display:flex;flex-direction:column;gap:1.5rem}
    .rly-card{border-radius:.75rem;border:1px solid #e5e7eb;background:#fff;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,.05)}
    .dark .rly-card{border-color:rgba(255,255,255,.1);background:rgba(17,24,39,.4)}
    .rly-card-hd{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.75rem 1.25rem;border-bottom:1px solid #f3f4f6}
    .dark .rly-card-hd{border-bottom-color:rgba(255,255,255,.07)}
    .rly-row{display:flex;align-items:center}
    .rly-lbl{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280}
    .dark .rly-lbl{color:#9ca3af}
    .rly-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .rly-p4{padding:1rem}
    .rly-empty{padding:2.5rem 1.5rem;text-align:center;border:1px dashed #e5e7eb;border-radius:.75rem;background:#fafafa}
    .dark .rly-empty{border-color:rgba(255,255,255,.1);background:rgba(255,255,255,.01)}
    .rly-ico{display:flex;align-items:center;justify-content:center;border-radius:9999px;background:#f3f4f6;color:#9ca3af}
    .dark .rly-ico{background:rgba(255,255,255,.06);color:#6b7280}
    .rly-h4{margin:.875rem 0 0;font-size:.875rem;font-weight:600;color:#111827}
    .dark .rly-h4{color:#f9fafb}
    .rly-sub{margin:.375rem 0 0;font-size:.875rem;color:#6b7280}
    .dark .rly-sub{color:#9ca3af}
    .rly-note{margin:.5rem 0 0;font-size:.75rem;color:#9ca3af}
    .dark .rly-note{color:#6b7280}
    .rly-btn-primary{display:inline-flex;align-items:center;gap:.5rem;border-radius:.5rem;background:#111827;padding:.5rem 1rem;font-size:.875rem;font-weight:600;color:#fff;border:none;cursor:pointer;margin-top:1.25rem}
    .dark .rly-btn-primary{background:#f9fafb;color:#111827}
    .rly-btn-sm{display:inline-flex;align-items:center;gap:.375rem;border-radius:.5rem;border:1px solid #d1d5db;background:#fff;padding:.375rem .75rem;font-size:.75rem;font-weight:600;color:#374151;cursor:pointer}
    .dark .rly-btn-sm{border-color:rgba(255,255,255,.2);background:rgba(255,255,255,.05);color:#e5e7eb}
    .rly-tbl{width:100%;border-collapse:collapse;text-align:left}
    .rly-tbl th{padding:.625rem 1rem;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;border-bottom:1px solid #f3f4f6;background:#f9fafb;white-space:nowrap}
    .dark .rly-tbl th{color:#9ca3af;border-bottom-color:rgba(255,255,255,.07);background:rgba(255,255,255,.03)}
    .rly-tbl td{padding:.75rem 1rem;font-size:.875rem;color:#374151;border-bottom:1px solid #f9fafb;vertical-align:middle}
    .dark .rly-tbl td{color:#d1d5db;border-bottom-color:rgba(255,255,255,.04)}
    .rly-tbl tr:last-child td{border-bottom:none}
    .rly-td-name{display:flex;align-items:center;gap:10px;font-weight:500;color:#111827}
    .dark .rly-td-name{color:#f9fafb}
    .rly-key-ic{display:flex;align-items:center;justify-content:center;border-radius:.375rem;background:#f3f4f6;color:#9ca3af;flex-shrink:0}
    .dark .rly-key-ic{background:rgba(255,255,255,.07);color:#6b7280}
    .rly-revoke{padding:.25rem .5rem;border-radius:.375rem;font-size:.75rem;font-weight:500;color:#9ca3af;background:transparent;border:none;cursor:pointer}
    .rly-revoke:hover{background:rgba(239,68,68,.08);color:#dc2626}
    .dark .rly-revoke:hover{background:rgba(239,68,68,.12);color:#f87171}
    .rly-badge-ok{display:inline-flex;align-items:center;gap:.375rem;border-radius:9999px;padding:.25rem .625rem;font-size:.75rem;font-weight:600;background:rgba(16,185,129,.1);color:#059669}
    .dark .rly-badge-ok{color:#34d399}
    .rly-url-box{display:flex;align-items:center;border-radius:.5rem;border:1px solid #e5e7eb;overflow:hidden}
    .dark .rly-url-box{border-color:rgba(255,255,255,.1)}
    .rly-url-code{min-width:0;flex:1;padding:.5rem .75rem;font-size:.75rem;font-family:ui-monospace,SFMono-Regular,Menlo,monospace;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .dark .rly-url-code{color:#d1d5db}
    .rly-icon-btn{display:flex;align-items:center;justify-content:center;padding:.5rem;color:#9ca3af;background:transparent;border-top:none;border-right:none;border-bottom:none;border-left:1px solid #e5e7eb;cursor:pointer}
    .dark .rly-icon-btn{border-left-color:rgba(255,255,255,.1)}
    .rly-ep{display:flex;align-items:center;gap:.75rem;border-radius:.5rem;border:1px solid #e5e7eb;padding:.5rem .75rem;font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:.75rem}
    .dark .rly-ep{border-color:rgba(255,255,255,.1);background:rgba(17,24,39,.3)}
    .rly-ep+.rly-ep{margin-top:.5rem}
    .rly-mget{padding:.1rem .4rem;border-radius:.25rem;font-weight:700;font-size:.65rem;background:rgba(59,130,246,.1);color:#2563eb;flex-shrink:0;font-family:ui-monospace,monospace}
    .dark .rly-mget{color:#60a5fa}
    .rly-mpost{padding:.1rem .4rem;border-radius:.25rem;font-weight:700;font-size:.65rem;background:rgba(245,158,11,.1);color:#d97706;flex-shrink:0;font-family:ui-monospace,monospace}
    .dark .rly-mpost{color:#fbbf24}
    .rly-ep-url{flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#374151}
    .dark .rly-ep-url{color:#d1d5db}
    .rly-cp{padding:.25rem;border-radius:.25rem;color:#9ca3af;background:transparent;border:none;cursor:pointer;opacity:0;flex-shrink:0;transition:opacity .15s}
    .rly-ep:hover .rly-cp{opacity:1}
    .rly-docs{display:inline-flex;align-items:center;gap:.375rem;border-radius:.375rem;border:1px solid #e5e7eb;background:#f9fafb;padding:.25rem .625rem;font-size:.75rem;font-weight:500;color:#374151;text-decoration:none}
    .dark .rly-docs{border-color:rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:#d1d5db}
    .rly-mbox{position:relative;z-index:10;width:100%;max-width:28rem;border-radius:1rem;border:1px solid #e5e7eb;background:#fff;padding:1.5rem;box-shadow:0 25px 50px rgba(0,0,0,.2)}
    .dark .rly-mbox{border-color:rgba(255,255,255,.1);background:#0f172a}
    .rly-mh{font-size:1rem;font-weight:600;color:#111827;margin:0 0 1.25rem}
    .dark .rly-mh{color:#f9fafb}
    .rly-mclose{position:absolute;right:1rem;top:1rem;display:flex;align-items:center;justify-content:center;border-radius:.375rem;color:#9ca3af;background:transparent;border:none;cursor:pointer}
    .rly-mclose:hover{background:#f3f4f6;color:#374151}
    .dark .rly-mclose:hover{background:rgba(255,255,255,.1);color:#d1d5db}
    .rly-warn-ico{display:flex;align-items:center;justify-content:center;border-radius:9999px;background:rgba(245,158,11,.1);margin:0 auto}
    .rly-token-wrap{display:flex;align-items:stretch;border-radius:.5rem;border:1px solid #1e293b;background:#030712;overflow:hidden}
    .rly-token-in{min-width:0;flex:1;border:0;background:transparent;padding:.75rem 1rem;font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:.75rem;color:#e2e8f0;outline:none}
    .rly-token-copy{flex-shrink:0;border:none;border-left:1px solid #1e293b;padding:.75rem 1rem;font-size:.75rem;font-weight:600;color:#94a3b8;background:transparent;cursor:pointer}
    .rly-usage{border-radius:.5rem;border:1px solid #e5e7eb;background:#f9fafb;padding:.75rem 1rem}
    .dark .rly-usage{border-color:rgba(255,255,255,.07);background:rgba(255,255,255,.03)}
    .rly-label{display:block;font-size:.875rem;font-weight:500;color:#374151}
    .dark .rly-label{color:#d1d5db}
    .rly-input{display:block;width:100%;border-radius:.5rem;border:1px solid #d1d5db;background:#fff;padding:.5rem .75rem;font-size:.875rem;margin-top:.375rem;box-sizing:border-box;outline:none}
    .dark .rly-input{border-color:rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:#f9fafb}
    .rly-helper{margin:.375rem 0 0;font-size:.75rem;color:#9ca3af}
    .rly-actions{display:flex;justify-content:flex-end;gap:.5rem;margin-top:1.25rem}
    .rly-btn-cancel{border-radius:.5rem;border:1px solid #e5e7eb;background:#fff;padding:.5rem 1rem;font-size:.875rem;font-weight:500;color:#374151;cursor:pointer}
    .dark .rly-btn-cancel{border-color:rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:#d1d5db}
    .rly-btn-create{border-radius:.5rem;background:#111827;padding:.5rem 1rem;font-size:.875rem;font-weight:600;color:#fff;border:none;cursor:pointer}
    .dark .rly-btn-create{background:#f9fafb;color:#111827}
</style>

<div class="rly-api">

    {{-- ─── API Keys card ─── --}}
    <div class="rly-card">
        <div class="rly-card-hd">
            <span class="rly-lbl">API Keys</span>
            @if ($hasKeys)
                <button type="button" x-on:click="$dispatch('rly-key-modal-open')" class="rly-btn-sm">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add key
                </button>
            @endif
        </div>

        @if ($hasKeys)
            <table class="rly-tbl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Created</th>
                        <th>Last used</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tokens as $token)
                        @php
                            $scopes = $token['scopes'] ?? ['form', 'referrals'];
                            $neverUsed = ($token['last_used_at'] ?? null) === null;
                        @endphp
                        <tr>
                            <td>
                                <div class="rly-td-name">
                                    <span class="rly-key-ic" style="width:24px;height:24px">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159-.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                                    </span>
                                    <div>
                                        {{ $token['name'] ?? 'Unnamed' }}
                                        <div style="display:flex;flex-wrap:wrap;gap:3px;margin-top:3px">
                                            @if (in_array('form', $scopes, true))
                                                <span style="font-size:.6875rem;font-weight:600;padding:1px 6px;border-radius:4px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe">Form</span>
                                            @endif
                                            @if (in_array('referrals', $scopes, true))
                                                <span style="font-size:.6875rem;font-weight:600;padding:1px 6px;border-radius:4px;background:#faf5ff;color:#7c3aed;border:1px solid #ddd6fe">Referrals</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="color:#6b7280;font-size:.8125rem">{{ $token['created_at'] ?? '—' }}</td>
                            <td style="color:#6b7280;font-size:.8125rem">
                                @if ($neverUsed)
                                    <span style="font-style:italic;color:#9ca3af">Never</span>
                                @else
                                    {{ $token['last_used_at'] }}
                                @endif
                            </td>
                            <td style="text-align:right">
                                <button
                                    type="button"
                                    wire:click="revokeApiKey({{ (int) ($token['id'] ?? 0) }})"
                                    wire:confirm="Revoke this key? It will stop working immediately."
                                    class="rly-revoke"
                                >Revoke</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="rly-empty">
                <div class="rly-ico" style="width:48px;height:48px;margin:0 auto">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159-.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                </div>
                <p class="rly-h4">No API keys yet</p>
                <p class="rly-sub">Generate a key to start authenticating API requests.</p>
                <button type="button" x-on:click="$dispatch('rly-key-modal-open')" class="rly-btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Generate API key
                </button>
            </div>
        @endif
    </div>

    {{-- ─── Status + Landing URL grid ─── --}}
    <div class="rly-grid">

        {{-- Status --}}
        <div class="rly-card rly-p4">
            <div class="rly-row" style="gap:6px;margin-bottom:12px">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0;opacity:.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                <span class="rly-lbl">Status</span>
            </div>
            <span class="rly-badge-ok">
                <span aria-hidden="true" style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#10b981;flex-shrink:0"></span>
                @if ($expiry) Active until {{ $expiry }} @else Active @endif
            </span>
            <p class="rly-note">
                @if ($expiry) Requests are rejected after the expiry date.
                @else No expiry set — access is open-ended. @endif
            </p>
        </div>

        {{-- Landing page URL --}}
        <div class="rly-card rly-p4">
            <div class="rly-row" style="gap:6px;margin-bottom:12px">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0;opacity:.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                <span class="rly-lbl">Landing page URL</span>
            </div>
            <div class="rly-url-box" x-data="{ copied: false }">
                <code class="rly-url-code">{{ $landingUrlDisplay }}</code>
                @if ($landingUrl)
                    <input type="hidden" id="landing-url-copy-field" value="{{ $landingUrl }}" />
                    <div style="display:flex;flex-shrink:0">
                        <a href="{{ $landingUrl }}" target="_blank" rel="noopener noreferrer" class="rly-icon-btn" title="Open in new tab">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <button
                            type="button"
                            x-on:click="navigator.clipboard.writeText(document.getElementById('landing-url-copy-field').value); copied = true; setTimeout(() => copied = false, 2000)"
                            class="rly-icon-btn"
                            title="Copy URL"
                        >
                            <span x-show="!copied"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg></span>
                            <span x-show="copied" style="display:none"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" style="flex-shrink:0"><path d="M20 6L9 17l-5-5"/></svg></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ─── Endpoints card ─── --}}
    <div class="rly-card rly-p4">
        <div class="rly-row" style="justify-content:space-between;gap:12px;margin-bottom:16px">
            <div class="rly-row" style="gap:6px">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0;opacity:.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg>
                <span class="rly-lbl">Endpoints</span>
            </div>
            <a href="{{ $docsUrl }}" target="_blank" rel="noopener" class="rly-docs">
                View docs
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="flex-shrink:0;opacity:.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
        <div>
            {{-- GET endpoint --}}
            <div class="rly-ep" x-data="{ copied: false }">
                <span class="rly-mget">GET</span>
                <span class="rly-ep-url">{{ $getUrl }}</span>
                <input type="hidden" id="endpoint-get-url" value="{{ $getUrl }}" />
                <button
                    type="button"
                    x-on:click="navigator.clipboard.writeText(document.getElementById('endpoint-get-url').value); copied = true; setTimeout(() => copied = false, 2000)"
                    class="rly-cp"
                    title="Copy URL"
                >
                    <span x-show="!copied"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg></span>
                    <span x-show="copied" style="display:none"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M20 6L9 17l-5-5"/></svg></span>
                </button>
            </div>
            {{-- POST endpoint --}}
            <div class="rly-ep" x-data="{ copied: false }">
                <span class="rly-mpost">POST</span>
                <span class="rly-ep-url">{{ $postUrl }}</span>
                <input type="hidden" id="endpoint-post-url" value="{{ $postUrl }}" />
                <button
                    type="button"
                    x-on:click="navigator.clipboard.writeText(document.getElementById('endpoint-post-url').value); copied = true; setTimeout(() => copied = false, 2000)"
                    class="rly-cp"
                    title="Copy URL"
                >
                    <span x-show="!copied"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg></span>
                    <span x-show="copied" style="display:none"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M20 6L9 17l-5-5"/></svg></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ─── Key generation modal (Alpine-driven; never touches Livewire form state) ─── --}}
    <div
        x-data="{ open: false, step: 1, token: null, keyName: '', scopes: ['form', 'referrals'] }"
        x-on:rly-key-modal-open.window="open = true; step = 1; token = null; keyName = ''; scopes = ['form', 'referrals']"
        x-on:rly-key-created.window="token = $event.detail.token; step = 2"
    >
        <div
            x-show="open"
            :style="open ? 'display:flex' : 'display:none'"
            style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;padding:1rem"
        >
            {{-- Backdrop --}}
            <div
                style="position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px)"
                x-on:click="open = false"
            ></div>

            {{-- Dialog --}}
            <div class="rly-mbox">
                <button type="button" x-on:click="open = false" class="rly-mclose" style="width:28px;height:28px" title="Close">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>

                <h3 class="rly-mh" x-text="step === 1 ? 'Generate API key' : 'Key generated'"></h3>

                {{-- Step 1 — name + scope selection --}}
                <div x-show="step === 1" style="display:flex;flex-direction:column;gap:1rem">
                    <div class="rly-ico" style="width:40px;height:40px;margin:0 auto">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159-.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/></svg>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="rly-label">
                            Key name <span style="font-weight:400;color:#9ca3af">(optional)</span>
                        </label>
                        <input
                            type="text"
                            x-model="keyName"
                            x-on:keydown.enter="scopes.length > 0 && $wire.createApiKey(keyName, scopes)"
                            placeholder="e.g. Production, Staging, CI"
                            class="rly-input"
                        />
                        <p class="rly-helper">A descriptive name helps you identify the key later.</p>
                    </div>

                    {{-- Scopes --}}
                    <div>
                        <label class="rly-label">Permissions</label>
                        <p class="rly-helper" style="margin-bottom:.5rem">Select at least one scope for this key.</p>
                        <div style="display:flex;flex-direction:column;gap:6px">

                            {{-- Form scope --}}
                            <label
                                style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;padding:10px 12px;border-radius:6px;border:1px solid #e5e7eb;transition:border-color .15s"
                                :style="scopes.includes('form') ? 'border-color:#6366f1;background:#f5f3ff' : ''"
                            >
                                <input type="checkbox" value="form" x-model="scopes" style="margin-top:2px;accent-color:#6366f1;width:15px;height:15px;flex-shrink:0" />
                                <div>
                                    <div style="display:flex;align-items:center;gap:5px;font-size:.8125rem;font-weight:600;color:#374151">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0;margin-top:1px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                        Landing page form
                                    </div>
                                    <p style="font-size:.75rem;color:#6b7280;margin:.15rem 0 0">Read brand &amp; form data · GET /api/v1/referral-form</p>
                                </div>
                            </label>

                            {{-- Referrals scope --}}
                            <label
                                style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;padding:10px 12px;border-radius:6px;border:1px solid #e5e7eb;transition:border-color .15s"
                                :style="scopes.includes('referrals') ? 'border-color:#6366f1;background:#f5f3ff' : ''"
                            >
                                <input type="checkbox" value="referrals" x-model="scopes" style="margin-top:2px;accent-color:#6366f1;width:15px;height:15px;flex-shrink:0" />
                                <div>
                                    <div style="display:flex;align-items:center;gap:5px;font-size:.8125rem;font-weight:600;color:#374151">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0;margin-top:1px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                        Referral submissions
                                    </div>
                                    <p style="font-size:.75rem;color:#6b7280;margin:.15rem 0 0">Submit new referrals · POST /api/v1/referrals</p>
                                </div>
                            </label>

                        </div>
                        <p x-show="scopes.length === 0" style="display:none;font-size:.75rem;color:#ef4444;margin:.4rem 0 0">
                            At least one permission is required.
                        </p>
                    </div>

                    <div class="rly-actions">
                        <button type="button" x-on:click="open = false" class="rly-btn-cancel">Cancel</button>
                        <button
                            type="button"
                            x-on:click="scopes.length > 0 && $wire.createApiKey(keyName, scopes)"
                            :disabled="scopes.length === 0"
                            :style="scopes.length === 0 ? 'opacity:.45;cursor:not-allowed' : ''"
                            class="rly-btn-create"
                        >Generate key</button>
                    </div>
                </div>

                {{-- Step 2 — token display --}}
                <div x-show="step === 2" style="display:none;flex-direction:column;gap:1rem">
                    <div class="rly-warn-ico" style="width:40px;height:40px">
                        <svg width="20" height="20" fill="none" stroke="#d97706" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                    </div>
                    <div style="text-align:center">
                        <p style="font-size:.875rem;font-weight:600;color:#111827;margin:0">Save your API key</p>
                        <p style="font-size:.875rem;color:#6b7280;margin:.25rem 0 0">This key will not be shown again. Copy it now.</p>
                    </div>
                    <div class="rly-token-wrap" x-data="{ copied: false }">
                        <input type="text" readonly :value="token" class="rly-token-in" x-ref="tokenField" />
                        <button
                            type="button"
                            x-on:click="navigator.clipboard.writeText($refs.tokenField.value); copied = true; setTimeout(() => copied = false, 2500)"
                            class="rly-token-copy"
                            :style="copied ? 'color:#34d399' : ''"
                        >
                            <span x-show="!copied" style="display:inline-flex;align-items:center;gap:5px">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                Copy
                            </span>
                            <span x-show="copied" style="display:none;align-items:center;gap:5px">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                Copied!
                            </span>
                        </button>
                    </div>
                    <div class="rly-usage">
                        <p style="font-size:.75rem;color:#6b7280;margin:0">Use in every request:</p>
                        <code style="display:block;margin-top:.25rem;font-size:.75rem;font-family:ui-monospace,monospace;color:#374151">
                            Authorization: Bearer <span style="color:#d97706">&lt;key&gt;</span>
                        </code>
                    </div>
                    <div style="display:flex;justify-content:flex-end;padding-top:.25rem">
                        <button type="button" x-on:click="open = false" class="rly-btn-create">Done</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
