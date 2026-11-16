import packages from '../data/packages.json';
import addons from '../data/addons.json';
import { appPath } from './app-links.js';

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

function packageCtaHref(pkg) {
    if (pkg.slug === 'free') {
        return appPath('/referral/register');
    }

    return `/buy-package?package=${encodeURIComponent(pkg.slug)}`;
}

function pricingCardHtml(pkg) {
    const highlighted = pkg.slug === 'starter';
    const showBadge = highlighted || Boolean(pkg.badge);
    const badgeText = pkg.badge || 'Most Popular';
    const cardClass = highlighted
        ? 'relative rounded-2xl border-2 border-blue-500 bg-gray-900 p-10 shadow-lg shadow-blue-500/10'
        : 'rounded-2xl border border-gray-800 bg-gray-900 p-10 transition-all duration-200 hover:border-gray-700';

    const features = (pkg.features || [])
        .map(
            (feature) => `
        <li class="flex items-start gap-3 text-[15px] leading-snug text-gray-300">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06 1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
            </svg>
            <span>${escapeHtml(feature)}</span>
        </li>`,
        )
        .join('');

    const limitations = (pkg.limitations || [])
        .map(
            (limitation) => `
        <li class="flex items-start gap-3 text-sm leading-relaxed text-gray-500">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>${escapeHtml(limitation)}</span>
        </li>`,
        )
        .join('');

    return `
    <div class="${cardClass}">
        ${
            showBadge
                ? `<div class="absolute -top-3.5 left-1/2 -translate-x-1/2 rounded-full bg-blue-600 px-4 py-1.5 text-xs font-semibold tracking-wide text-white shadow-lg">${escapeHtml(badgeText)}</div>`
                : ''
        }
        <h3 class="text-xl font-semibold tracking-tight text-white">${escapeHtml(pkg.name)}</h3>
        <div class="mt-8 flex items-baseline gap-1.5">
            <span class="text-4xl font-bold tracking-tight tabular-nums text-white">€${Number(pkg.price).toFixed(0)}</span>
            <span class="text-base font-medium text-gray-500">/ month</span>
        </div>
        ${pkg.short_description ? `<p class="mt-5 text-[15px] leading-relaxed text-gray-400">${escapeHtml(pkg.short_description)}</p>` : ''}
        ${features ? `<ul class="mt-10 space-y-4" role="list">${features}</ul>` : ''}
        ${limitations ? `<ul class="mt-8 space-y-3 border-t border-gray-800 pt-8" role="list">${limitations}</ul>` : ''}
        <div class="mt-10">
            <a href="${packageCtaHref(pkg)}" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3.5 text-[15px] font-medium text-white shadow-lg shadow-blue-500/20 transition-all duration-200 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-950">
                ${escapeHtml(pkg.cta_label)}
            </a>
        </div>
    </div>`;
}

function addonCardHtml(addon) {
    return `
    <div class="rounded-2xl border border-gray-800 bg-gray-900 p-8 transition-all duration-200 hover:border-gray-700">
        <h3 class="text-lg font-semibold tracking-tight text-white">${escapeHtml(addon.name)}</h3>
        ${addon.description ? `<p class="mt-3 text-[15px] leading-relaxed text-gray-400">${escapeHtml(addon.description)}</p>` : ''}
        <div class="mt-6 flex items-baseline gap-2">
            <span class="text-2xl font-bold tracking-tight tabular-nums text-white">€${Number(addon.price).toFixed(0)}</span>
            <span class="text-sm text-gray-500">/ year</span>
        </div>
        <div class="mt-8">
            <p class="mb-4 text-xs text-gray-500">Valid with any plan. Sign in or create an account to purchase this add-on.</p>
            <a data-app-path="/referral/purchase-packages" href="#" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-[15px] font-medium text-white shadow-lg shadow-blue-500/20 transition-all duration-200 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-950">
                Buy now
            </a>
        </div>
    </div>`;
}

function boolCell(value) {
    return value
        ? '<span class="text-emerald-500">✓</span>'
        : '<span class="text-gray-600">❌</span>';
}

function dashCell(value) {
    return value
        ? '<span class="text-emerald-500">✓</span>'
        : '<span class="text-gray-600">—</span>';
}

export function renderPricingCards(selector = '[data-pricing-cards]') {
    document.querySelectorAll(selector).forEach((el) => {
        el.innerHTML = packages.map(pricingCardHtml).join('');
    });
}

export function renderAddons(selector = '[data-addons]') {
    document.querySelectorAll(selector).forEach((el) => {
        el.innerHTML = addons.map(addonCardHtml).join('');
    });
}

export function renderCompareTable(selector = '[data-compare-table]') {
    const table = document.querySelector(selector);
    if (!table) {
        return;
    }

    const headers = packages
        .map((pkg) => `<th scope="col" class="px-6 py-5 text-base font-semibold tracking-tight text-white">${escapeHtml(pkg.name)}</th>`)
        .join('');

    const rows = [
        {
            label: 'Domain',
            cells: packages.map((pkg) => `<td class="px-6 py-5 text-[15px] text-gray-400">${escapeHtml(pkg.domain_structure)}</td>`).join(''),
        },
        {
            label: 'Branding',
            cells: packages.map((pkg) => `<td class="px-6 py-5">${boolCell(pkg.custom_branding)}</td>`).join(''),
        },
        {
            label: 'Landing page',
            cells: packages.map((pkg) => `<td class="px-6 py-5">${boolCell(pkg.custom_landing_page)}</td>`).join(''),
        },
        {
            label: 'Referlyst branding',
            cells: packages.map((pkg) => `<td class="px-6 py-5">${boolCell(pkg.slug === 'free')}</td>`).join(''),
        },
        {
            label: 'Brands',
            cells: packages
                .map((pkg) => {
                    const value = pkg.no_of_referrers_brands > 0 ? pkg.no_of_referrers_brands : 'Unlimited';

                    return `<td class="px-6 py-5 text-[15px] text-gray-400">${escapeHtml(value)}</td>`;
                })
                .join(''),
        },
        {
            label: 'Referrers',
            cells: packages
                .map((pkg) => {
                    const value = pkg.no_of_referrers > 0 ? pkg.no_of_referrers : 'Unlimited';

                    return `<td class="px-6 py-5 text-[15px] text-gray-400">${escapeHtml(value)}</td>`;
                })
                .join(''),
        },
        {
            label: 'CSV export',
            cells: packages.map((pkg) => `<td class="px-6 py-5">${dashCell(pkg.export_csv)}</td>`).join(''),
        },
        {
            label: 'Email notifications',
            cells: packages.map((pkg) => `<td class="px-6 py-5">${dashCell(pkg.email_notifications)}</td>`).join(''),
        },
    ];

    table.innerHTML = `
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] border-collapse text-left">
                <thead>
                    <tr class="border-b border-gray-800 bg-gray-900/80">
                        <th scope="col" class="py-5 pl-8 pr-6 text-sm font-medium text-gray-400">Feature</th>
                        ${headers}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/80">
                    ${rows
                        .map(
                            (row) => `
                        <tr class="bg-gray-950/30 transition-colors hover:bg-gray-900/50">
                            <td class="py-5 pl-8 pr-6 text-[15px] font-medium text-gray-300">${escapeHtml(row.label)}</td>
                            ${row.cells}
                        </tr>`,
                        )
                        .join('')}
                </tbody>
            </table>
        </div>`;
}

export function renderBuyPackage(selector = '[data-buy-package]') {
    const root = document.querySelector(selector);
    if (!root) {
        return;
    }

    const slug = new URLSearchParams(window.location.search).get('package');
    const pkg = packages.find((item) => item.slug === slug);

    if (!pkg || pkg.slug === 'free') {
        window.location.replace('/pricing');

        return;
    }

    document.title = `Choose plan – ${pkg.name} – Referlyst`;

    const features = (pkg.features || [])
        .map(
            (feature) => `
        <li class="flex items-start gap-3 text-gray-300">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06 1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
            </svg>
            ${escapeHtml(feature)}
        </li>`,
        )
        .join('');

    root.innerHTML = `
        <h1 class="text-5xl font-bold tracking-tight text-white">Choose your plan</h1>
        <p class="mt-6 text-lg text-gray-300">Review your selection and proceed to checkout.</p>
        <div class="mt-14 rounded-2xl border border-gray-800 bg-gray-900 p-8 shadow-lg">
            <h2 class="text-xl font-semibold text-white">${escapeHtml(pkg.name)}</h2>
            <div class="mt-6 flex items-baseline gap-1">
                <span class="text-4xl font-bold text-white">€${Number(pkg.price).toFixed(0)}</span>
                <span class="text-gray-500">/ month</span>
            </div>
            ${features ? `<ul class="mt-8 space-y-4" role="list">${features}</ul>` : ''}
        </div>
        <p class="mt-10 text-center text-sm text-gray-500">Sign in or create an account to complete your purchase.</p>
        <div class="mt-6 flex flex-col justify-center gap-4 sm:flex-row">
            <a data-app-path="/referral/login" href="#" class="inline-flex items-center justify-center rounded-lg border border-gray-700 px-6 py-3 text-sm font-medium text-gray-300 transition-all duration-200 hover:border-gray-600 hover:bg-gray-800/50 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 focus:ring-offset-gray-950">Log in</a>
            <a data-app-path="/referral/register?package=${encodeURIComponent(pkg.slug)}" href="#" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-sm font-medium text-white shadow-lg shadow-blue-500/20 transition-all duration-200 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-950">Create your account</a>
        </div>
        <p class="mt-10 text-center">
            <a href="/pricing" class="text-sm text-gray-400 transition-colors hover:text-white">← Back to pricing</a>
        </p>`;
}
