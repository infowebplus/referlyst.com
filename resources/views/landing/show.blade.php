@extends('landing.layout')

@section('title', $brand->name . ' – Refer a friend')

@section('content')

    {{-- ── Hero ──────────────────────────────────────────────────────────── --}}
    <section style="background:var(--bg-surface);border-bottom:1px solid var(--border);">
        <div class="l-wrap" style="padding-top:48px;padding-bottom:40px;text-align:center;">

            {{-- Logo --}}
            <div style="margin-bottom:20px;display:flex;justify-content:center;">
                @if ($logoUrl ?? null)
                    <img src="{{ $logoUrl }}" alt="{{ $brand->name }}"
                         id="hero-logo"
                         style="height:72px;width:auto;max-width:200px;object-fit:contain;border-radius:14px;display:block;"
                         onerror="this.style.display='none';document.getElementById('hero-fallback').style.display='flex';">
                    @if (! ($activePackage && $activePackage->slug === 'free'))
                        <span id="hero-fallback"
                              class="l-logo-mark"
                              style="display:none;width:72px;height:72px;font-size:28px;">
                            {{ Str::upper(Str::substr($brand->name, 0, 1)) }}
                        </span>
                    @endif
                @else
                    @if (! ($activePackage && $activePackage->slug === 'free'))
                        <span class="l-logo-mark"
                              style="display:flex;width:72px;height:72px;font-size:28px;">
                            {{ Str::upper(Str::substr($brand->name, 0, 1)) }}
                        </span>
                    @endif
                @endif
            </div>

            <h1 style="font-size:26px;font-weight:800;line-height:1.2;letter-spacing:-.03em;color:var(--text-primary);">
                Refer friends to&nbsp;{{ $brand->name }}
            </h1>
            <p style="margin-top:10px;font-size:15px;line-height:1.6;color:var(--text-secondary);max-width:400px;margin-left:auto;margin-right:auto;">
                Share the people who might benefit, we'll handle the rest.
            </p>

            <div style="margin-top:18px;display:flex;flex-wrap:wrap;justify-content:center;gap:16px;">
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--text-muted);">
                    <svg style="width:15px;height:15px;color:#22c55e;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Your details stay private
                </span>
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--text-muted);">
                    <svg style="width:15px;height:15px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Takes under a minute
                </span>
            </div>

        </div>
    </section>

    {{-- ── Form ────────────────────────────────────────────────────────────── --}}
    <main style="flex:1;">
        <div class="l-wrap" style="padding-top:28px;padding-bottom:48px;display:flex;flex-direction:column;gap:12px;">

            {{-- Flash alerts --}}
            @if (session('success'))
                <div class="l-alert l-alert-success">
                    <svg style="width:16px;height:16px;flex-shrink:0;margin-top:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="l-alert l-alert-error">
                    <svg style="width:16px;height:16px;flex-shrink:0;margin-top:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if (! $activePackage)
                <div class="l-alert l-alert-warn">No active package configured for this brand.</div>
            @elseif ($slotsRemaining === 0)
                <div class="l-alert l-alert-warn">No referral slots are available at this time.</div>
            @else

                @if ($errors->any())
                    <div class="l-alert l-alert-error" style="flex-direction:column;align-items:flex-start;">
                        <p style="font-weight:600;margin-bottom:6px;">Please fix the following:</p>
                        <ul style="list-style:disc;padding-left:18px;display:flex;flex-direction:column;gap:2px;font-size:13px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ $submitUrl }}" method="POST" style="display:contents;">
                    @csrf

                    {{-- ① Service ------------------------------------------ --}}
                    <div class="l-card">
                        <div class="l-card-hd">
                            <span class="l-step">1</span>
                            <h2 style="font-size:14px;font-weight:600;color:var(--text-primary);">Service</h2>
                        </div>
                        <div class="l-card-bd">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <select id="service_id" name="service_id" required class="l-input">
                                    <option value="">Choose a service…</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}"
                                            @selected(old('service_id') == $service->id)
                                            data-commission-type="{{ $service->commission_type?->value ?? '' }}"
                                            data-commission="{{ $service->commission !== null ? (float) $service->commission : '' }}">
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="service_commission" aria-live="polite"
                                      style="flex-shrink:0;min-width:56px;text-align:right;font-size:13px;font-weight:700;color:var(--brand-primary);"></span>
                            </div>
                        </div>
                    </div>

                    {{-- ② Your details --------------------------------------- --}}
                    <div class="l-card">
                        <div class="l-card-hd">
                            <span class="l-step">2</span>
                            <div>
                                <h2 style="font-size:14px;font-weight:600;color:var(--text-primary);">Your details</h2>
                                <p style="font-size:12px;color:var(--text-muted);margin-top:1px;">So we know who to credit.</p>
                            </div>
                        </div>
                        <div class="l-card-bd">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div>
                                    <label for="referrer_first_name" class="l-label">First name <span style="color:#f87171;">*</span></label>
                                    <input type="text" id="referrer_first_name" name="referrer_first_name"
                                           value="{{ old('referrer_first_name') }}" required maxlength="255"
                                           placeholder="Jane"
                                           class="l-input {{ $errors->has('referrer_first_name') ? 'is-error' : '' }}">
                                    @error('referrer_first_name')
                                        <p style="margin-top:4px;font-size:12px;color:#f87171;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="referrer_surname" class="l-label">Last name <span style="color:#f87171;">*</span></label>
                                    <input type="text" id="referrer_surname" name="referrer_surname"
                                           value="{{ old('referrer_surname') }}" required maxlength="255"
                                           placeholder="Smith"
                                           class="l-input {{ $errors->has('referrer_surname') ? 'is-error' : '' }}">
                                    @error('referrer_surname')
                                        <p style="margin-top:4px;font-size:12px;color:#f87171;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="referrer_email" class="l-label">Email address <span style="color:#f87171;">*</span></label>
                                    <input type="email" id="referrer_email" name="referrer_email"
                                           value="{{ old('referrer_email') }}" required maxlength="255"
                                           placeholder="jane@example.com"
                                           class="l-input {{ $errors->has('referrer_email') ? 'is-error' : '' }}">
                                    @error('referrer_email')
                                        <p style="margin-top:4px;font-size:12px;color:#f87171;">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="referrer_phone" class="l-label">
                                        Phone <span style="font-weight:400;color:var(--text-muted);">(optional)</span>
                                    </label>
                                    <input type="tel" id="referrer_phone" name="referrer_phone"
                                           value="{{ old('referrer_phone') }}" maxlength="255"
                                           placeholder="+1 555 000 0000"
                                           class="l-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ③ Who to refer --------------------------------------- --}}
                    <div class="l-card">
                        <div class="l-card-hd" style="justify-content:space-between;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span class="l-step">3</span>
                                <div>
                                    <h2 style="font-size:14px;font-weight:600;color:var(--text-primary);">Who are you referring?</h2>
                                    <p style="font-size:12px;color:var(--text-muted);margin-top:1px;">Add the people you'd like to recommend.</p>
                                </div>
                            </div>
                            <span style="flex-shrink:0;background:var(--bg-subtle);border:1px solid var(--border);color:var(--text-secondary);font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px;">
                                Up to {{ $slotsRemaining }} {{ Str::plural('slot', $slotsRemaining) }}
                            </span>
                        </div>
                        <div class="l-card-bd" style="display:flex;flex-direction:column;gap:10px;">

                            <div id="referrers-container" style="display:flex;flex-direction:column;gap:10px;" data-max="{{ $slotsRemaining }}">
                                <template id="referrer-row-template">
                                    <div class="referrer-row l-ref-row">
                                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                                            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);">
                                                Person <span class="referrer-index">1</span>
                                            </span>
                                            <button type="button" class="remove-referrer"
                                                    style="display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:6px;border:none;background:transparent;font-size:12px;font-weight:500;color:var(--text-muted);cursor:pointer;font-family:inherit;transition:background .12s,color .12s;"
                                                    onmouseover="this.style.background='color-mix(in srgb,#ef4444 10%,var(--bg-surface))';this.style.color='#ef4444';"
                                                    onmouseout="this.style.background='transparent';this.style.color='var(--text-muted)';"
                                                    aria-label="Remove">
                                                <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Remove
                                            </button>
                                        </div>
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                            <div>
                                                <label class="l-label">First name <span style="color:#f87171;">*</span></label>
                                                <input type="text" name="recommend_to[__INDEX__][name]" required maxlength="255" placeholder="John" class="l-input" style="background:var(--bg-surface);">
                                            </div>
                                            <div>
                                                <label class="l-label">Last name <span style="color:#f87171;">*</span></label>
                                                <input type="text" name="recommend_to[__INDEX__][surname]" required maxlength="255" placeholder="Doe" class="l-input" style="background:var(--bg-surface);">
                                            </div>
                                            <div>
                                                <label class="l-label">Phone <span style="color:#f87171;">*</span></label>
                                                <input type="tel" name="recommend_to[__INDEX__][phone]" required maxlength="255" placeholder="+1 555 000 0000" class="l-input" style="background:var(--bg-surface);">
                                            </div>
                                            <div>
                                                <label class="l-label">Email <span style="color:#f87171;">*</span></label>
                                                <input type="email" name="recommend_to[__INDEX__][email]" required placeholder="john@example.com" class="l-input" style="background:var(--bg-surface);">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="button" id="add-referrer"
                                    style="align-self:flex-start;display:inline-flex;align-items:center;gap:6px;padding:9px 14px;border-radius:9px;border:1.5px dashed var(--border-input);background:transparent;color:var(--text-secondary);font-size:13px;font-weight:500;cursor:pointer;font-family:inherit;transition:border-color .12s,color .12s;"
                                    onmouseover="this.style.borderColor='var(--brand-primary)';this.style.color='var(--brand-primary)';"
                                    onmouseout="this.style.borderColor='var(--border-input)';this.style.color='var(--text-secondary)';">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Add another person
                            </button>

                        </div>
                    </div>

                    {{-- T&C + Submit ------------------------------------------ --}}
                    <div style="display:flex;flex-direction:column;gap:10px;padding-top:4px;">

                        @if (isset($termsUrl))
                            <label class="l-check-card">
                                <input type="checkbox" name="terms_accepted" id="terms_accepted" required value="1"
                                       style="width:16px;height:16px;margin-top:2px;flex-shrink:0;accent-color:var(--brand-primary);cursor:pointer;">
                                <span style="font-size:13.5px;line-height:1.55;color:var(--text-secondary);">
                                    I have read and accept the
                                    <a href="{{ $termsUrl }}" target="_blank" rel="noopener noreferrer" class="l-link-brand">terms &amp; conditions</a>
                                    for this referral programme.
                                </span>
                            </label>
                            @error('terms_accepted')
                                <p style="font-size:12px;color:#f87171;">{{ $message }}</p>
                            @enderror
                        @endif

                        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                            <p style="font-size:12px;color:var(--text-muted);">
                                <span style="color:#f87171;">*</span> Required fields
                            </p>
                            <button type="submit" class="l-btn-primary">
                                Submit referrals
                                <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </button>
                        </div>

                    </div>

                </form>

            @endif
        </div>
    </main>

    @if ($activePackage && $slotsRemaining > 0)
        @push('scripts')
            <script>
                (function () {
                    var select = document.getElementById('service_id');
                    var span   = document.getElementById('service_commission');
                    function update() {
                        var opt = select.options[select.selectedIndex];
                        if (!opt || !opt.value) { span.textContent = ''; return; }
                        var type = opt.dataset.commissionType || '';
                        var val  = parseFloat(opt.dataset.commission || '');
                        if (isNaN(val)) { span.textContent = ''; return; }
                        span.textContent = type === 'percent'
                            ? (val % 1 === 0 ? val : val.toFixed(1)) + '%'
                            : '€' + (val % 1 === 0 ? val : val.toFixed(2));
                    }
                    select.addEventListener('change', update);
                    update();
                })();
            </script>
            <script>
                (function () {
                    var container = document.getElementById('referrers-container');
                    var template  = document.getElementById('referrer-row-template');
                    var addBtn    = document.getElementById('add-referrer');
                    var max       = parseInt(container.dataset.max, 10);
                    var index     = 0;

                    function rows() { return container.querySelectorAll('.referrer-row'); }

                    function addRow() {
                        if (rows().length >= max) return;
                        var clone = template.content.cloneNode(true);
                        var row   = clone.querySelector('.referrer-row');
                        row.querySelectorAll('[name]').forEach(function (el) {
                            el.name = el.name.replace('__INDEX__', index);
                        });
                        row.querySelector('.referrer-index').textContent = index + 1;
                        row.querySelector('.remove-referrer').addEventListener('click', function () { row.remove(); reindex(); });
                        container.appendChild(clone);
                        index++;
                        reindex();
                    }

                    function reindex() {
                        rows().forEach(function (row, i) {
                            row.querySelector('.referrer-index').textContent = i + 1;
                            row.querySelectorAll('[name]').forEach(function (el) {
                                el.name = el.name.replace(/recommend_to\[\d+\]/, 'recommend_to[' + i + ']');
                            });
                            var rb = row.querySelector('.remove-referrer');
                            if (rb) rb.style.display = i === 0 ? 'none' : '';
                        });
                        index = rows().length;
                        var full = index >= max;
                        addBtn.disabled = full;
                        addBtn.style.opacity = full ? '0.4' : '';
                        addBtn.style.pointerEvents = full ? 'none' : '';
                    }

                    addBtn.addEventListener('click', addRow);
                    addRow();
                })();
            </script>
        @endpush
    @endif

@endsection
