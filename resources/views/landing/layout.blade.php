<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ $landingTheme ?? 'light' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="color-scheme" content="{{ ($landingTheme ?? 'light') === 'dark' ? 'dark' : 'light' }}">

        <title>@yield('title', $brand->name . ' – Referral')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                --brand-primary:   {{ $brandColors['primary'] }};
                --brand-secondary: {{ $brandColors['secondary'] }};

                /* Light */
                --bg-page:      #f2f1ef;
                --bg-surface:   #ffffff;
                --bg-input:     #fafaf9;
                --bg-subtle:    #f5f4f2;
                --border:       #e6e4e0;
                --border-input: #d2d0cb;
                --text-primary:   #181715;
                --text-secondary: #5a5652;
                --text-muted:     #a09d98;
                --shadow-sm:      0 1px 2px rgba(0,0,0,.06);
                --shadow-card:    0 0 0 1px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.06);
                --header-bg:    #ffffff;
                --footer-bg:    #ffffff;
            }

            [data-theme="dark"] {
                --bg-page:      #0d0d0c;
                --bg-surface:   #191918;
                --bg-input:     #202020;
                --bg-subtle:    #1c1c1b;
                --border:       #2a2a28;
                --border-input: #363634;
                --text-primary:   #edebe8;
                --text-secondary: #a39f9a;
                --text-muted:     #615e58;
                --shadow-sm:      0 1px 2px rgba(0,0,0,.3);
                --shadow-card:    0 0 0 1px rgba(255,255,255,.05), 0 2px 8px rgba(0,0,0,.3);
                --header-bg:    #131312;
                --footer-bg:    #131312;
            }

            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
                background: var(--bg-page);
                color: var(--text-primary);
                line-height: 1.6;
                -webkit-font-smoothing: antialiased;
            }

            /* ─── Containers ──────────────────────── */
            .l-header { background: var(--header-bg); border-bottom: 1px solid var(--border); }
            .l-footer { background: var(--footer-bg); border-top: 1px solid var(--border); }

            .l-wrap {
                max-width: 680px;
                margin: 0 auto;
                padding: 0 24px;
            }

            /* ─── Card ────────────────────────────── */
            .l-card {
                background: var(--bg-surface);
                border-radius: 16px;
                box-shadow: var(--shadow-card);
                overflow: hidden;
            }

            /* Card header strip (step label area) */
            .l-card-hd {
                padding: 16px 24px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                gap: 10px;
            }

            /* Card body */
            .l-card-bd { padding: 20px 24px; }

            /* ─── Step badge ──────────────────────── */
            .l-step {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 22px;
                height: 22px;
                flex-shrink: 0;
                border-radius: 50%;
                background: var(--brand-primary);
                color: #fff;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .01em;
            }

            /* ─── Inputs ──────────────────────────── */
            .l-input {
                display: block;
                width: 100%;
                background: var(--bg-input);
                border: 1.5px solid var(--border-input);
                color: var(--text-primary);
                border-radius: 10px;
                padding: 10px 13px;
                font-size: 14px;
                font-family: inherit;
                outline: none;
                transition: border-color .15s, box-shadow .15s;
                -webkit-appearance: none;
                appearance: none;
            }
            .l-input::placeholder { color: var(--text-muted); }
            .l-input:focus {
                border-color: var(--brand-primary);
                box-shadow: 0 0 0 3px color-mix(in srgb, var(--brand-primary) 14%, transparent);
            }
            .l-input.is-error { border-color: #f87171; }

            .l-label {
                display: block;
                font-size: 12.5px;
                font-weight: 500;
                color: var(--text-secondary);
                margin-bottom: 5px;
            }

            /* ─── Buttons ─────────────────────────── */
            .l-btn-primary {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                background: var(--brand-primary);
                color: #fff;
                font-size: 14px;
                font-weight: 600;
                font-family: inherit;
                padding: 11px 22px;
                border-radius: 10px;
                border: none;
                cursor: pointer;
                transition: opacity .15s, transform .1s;
                text-decoration: none;
                letter-spacing: -.01em;
                white-space: nowrap;
            }
            .l-btn-primary:hover  { opacity: 0.87; }
            .l-btn-primary:active { transform: scale(.98); }
            .l-btn-primary:focus-visible { outline: none; box-shadow: 0 0 0 2px var(--bg-page), 0 0 0 4px var(--brand-primary); }

            .l-btn-ghost {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                background: var(--bg-subtle);
                border: 1px solid var(--border);
                color: var(--text-secondary);
                font-size: 13px;
                font-weight: 500;
                font-family: inherit;
                padding: 7px 13px;
                border-radius: 8px;
                cursor: pointer;
                transition: background .15s, color .15s;
                text-decoration: none;
            }
            .l-btn-ghost:hover { background: var(--border); color: var(--text-primary); }

            /* ─── Text ────────────────────────────── */
            .l-link-brand { color: var(--brand-primary); font-weight: 500; text-decoration: none; }
            .l-link-brand:hover { text-decoration: underline; }
            .l-link-muted { color: var(--text-muted); text-decoration: none; font-size: 13px; }
            .l-link-muted:hover { color: var(--text-secondary); }

            /* ─── Logo mark ───────────────────────── */
            .l-logo-mark {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                background: var(--brand-primary);
                color: #fff;
                font-weight: 800;
                letter-spacing: -.03em;
                border-radius: 18px;
            }

            /* ─── Referrer row ────────────────────── */
            .l-ref-row {
                background: var(--bg-subtle);
                border: 1px solid var(--border);
                border-radius: 12px;
                padding: 16px;
            }

            /* ─── Alerts ──────────────────────────── */
            .l-alert {
                display: flex;
                gap: 10px;
                align-items: flex-start;
                border-radius: 12px;
                padding: 13px 16px;
                font-size: 14px;
                line-height: 1.5;
            }
            .l-alert-success { border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534; }
            .l-alert-error   { border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; }
            .l-alert-warn    { border: 1px solid #fde68a; background: #fffbeb; color: #92400e; }
            [data-theme="dark"] .l-alert-success { border-color: #166534; background: #052e16; color: #86efac; }
            [data-theme="dark"] .l-alert-error   { border-color: #991b1b; background: #450a0a; color: #fca5a5; }
            [data-theme="dark"] .l-alert-warn    { border-color: #92400e; background: #422006; color: #fcd34d; }

            /* ─── Theme toggle ───────────────────── */
            .l-theme-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                border-radius: 8px;
                border: 1px solid var(--border);
                background: var(--bg-subtle);
                color: var(--text-secondary);
                cursor: pointer;
                transition: background .15s, color .15s, border-color .15s;
                flex-shrink: 0;
            }
            .l-theme-btn:hover { background: var(--border); color: var(--text-primary); }

            /* ─── Checkbox card ───────────────────── */
            .l-check-card {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 14px 18px;
                border-radius: 12px;
                border: 1.5px solid var(--border-input);
                background: var(--bg-surface);
                cursor: pointer;
                transition: border-color .15s, background .15s;
            }
            .l-check-card:hover { border-color: var(--brand-primary); }
            .l-check-card:has(input:checked) {
                border-color: var(--brand-primary);
                background: color-mix(in srgb, var(--brand-primary) 5%, var(--bg-surface));
            }
        </style>
    </head>

    <body style="min-height:100vh;display:flex;flex-direction:column;">

        {{-- Top bar (hidden for Free package) --}}
        @if ($brand->active_package?->slug !== 'free')
            <header class="l-header" style="flex-shrink:0;">
                <div class="l-wrap" style="padding-top:12px;padding-bottom:12px;display:flex;align-items:center;justify-content:space-between;gap:10px;">
                    <a href="{{ $landingUrl ?? '#' }}" style="font-size:13px;font-weight:600;color:var(--text-primary);text-decoration:none;outline:none;min-width:0;flex:1;">
                        {{ $brand->name }}
                    </a>

                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                        @if (isset($showBackLink) && $showBackLink)
                            <a href="{{ $landingUrl }}" class="l-btn-ghost">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back
                            </a>
                        @endif

                        {{-- Dark / light toggle --}}
                        <button id="theme-toggle" class="l-theme-btn" aria-label="Toggle dark mode" title="Toggle dark mode">
                            {{-- Sun icon (shown in dark mode) --}}
                            <svg id="icon-sun" style="width:16px;height:16px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                            </svg>
                            {{-- Moon icon (shown in light mode) --}}
                            <svg id="icon-moon" style="width:16px;height:16px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>
        @endif

        @yield('content')

        {{-- Footer --}}
        <footer class="l-footer" style="flex-shrink:0;padding:16px 0;">
            <div class="l-wrap" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                <nav style="display:flex;flex-wrap:wrap;align-items:center;gap:4px 6px;">
                    @if (isset($brandTermsDirectUrl) && $brandTermsDirectUrl)
                        {{-- Direct link to the brand's external T&C URL set in admin --}}
                        <a href="{{ $brandTermsDirectUrl }}" target="_blank" rel="noopener noreferrer" class="l-link-brand" style="font-size:12px;">{{ $brand->name }} terms</a>
                    @elseif (isset($termsUrl) && $termsUrl)
                        {{-- Fallback: internal terms page with default general terms --}}
                        <a href="{{ $termsUrl }}" class="l-link-brand" style="font-size:12px;">{{ $brand->name }} terms</a>
                    @endif
                </nav>
                <p style="font-size:12px;color:var(--text-muted);white-space:nowrap;">
                    Powered by
                    <a href="{{ rtrim(config('app.url'), '/') }}" target="_blank" rel="noopener noreferrer"
                       style="font-weight:600;color:var(--text-secondary);text-decoration:none;"
                       onmouseover="this.style.color='var(--brand-primary)'"
                       onmouseout="this.style.color='var(--text-secondary)'">Referlyst</a>
                </p>
            </div>
        </footer>

        @stack('scripts')

        <script>
            (function () {
                var DEFAULT = '{{ $landingTheme ?? "light" }}';
                var STORE_KEY = 'rly-theme';
                var html = document.documentElement;

                function current() {
                    return html.getAttribute('data-theme') || DEFAULT;
                }

                function apply(theme) {
                    html.setAttribute('data-theme', theme);
                    html.setAttribute('data-color-scheme', theme);
                    document.querySelector('meta[name="color-scheme"]').content = theme;
                    localStorage.setItem(STORE_KEY, theme);
                    var sun  = document.getElementById('icon-sun');
                    var moon = document.getElementById('icon-moon');
                    if (sun && moon) {
                        sun.style.display  = theme === 'dark' ? 'block' : 'none';
                        moon.style.display = theme === 'dark' ? 'none'  : 'block';
                    }
                }

                // Restore stored preference (user override) or use backend default
                var stored = localStorage.getItem(STORE_KEY);
                apply(stored || DEFAULT);

                var toggle = document.getElementById('theme-toggle');
                if (toggle) {
                    toggle.addEventListener('click', function () {
                        apply(current() === 'dark' ? 'light' : 'dark');
                    });
                }
            })();
        </script>
    </body>
</html>
