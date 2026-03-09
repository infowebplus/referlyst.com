# How to run the Referlyst project on your PC

## Local vs live

| | **Local (Windows + Herd)** | **Live (Cloudflare)** |
|---|---------------------------|------------------------|
| **URL** | `http://referlyst.test` | `https://referlyst.com` |
| **Landing pages** | Path-based: `referlyst.test/acme-coffee` (no DNS). Optional subdomain: `acme-coffee.referlyst.test` via `php artisan subdomains:sync` + hosts file | Subdomain: `acme-coffee.referlyst.com` (wildcard DNS) |
| **Proxies** | Not used | Trusted (X-Forwarded-* from Cloudflare) |
| **Config** | `APP_ENV=local` and/or `APP_URL=http://referlyst.test` | `APP_ENV=production`, `APP_URL=https://referlyst.com` |

The app detects the environment from `APP_ENV` and `APP_URL` (or set `REFERLYST_LANDING_ENV=local` / `production`). Trusted proxies are enabled only for production so Cloudflare headers are respected; local Herd is never treated as behind a proxy.

---

## With Laravel Herd (recommended)

You already have **PHP** and **Composer** via Herd. You only need **Node.js 18+** and **npm** for the frontend.

### First-time setup

In a terminal, from the project folder:

```bash
composer run setup
```

This installs PHP dependencies, copies `.env`, generates the application key, runs migrations on SQLite, installs npm packages, and builds frontend assets.

### Running the app

**Important – which URL to use with Herd**

- **Open the app in your browser at:** **http://referlyst.test**
- **Do not use** `http://localhost` or `http://localhost:5173` to view the site:
  - Herd serves your app only on the `.test` domain, so `localhost` will show “Herd not found” or similar.
  - `localhost:5173` is Vite’s dev server; it only serves JS/CSS for Laravel. The actual pages are served by Herd at `referlyst.test`.

If Herd doesn’t list your project yet, add the site in the Herd app (or ensure the project folder is in the path Herd uses for sites).

To get live reload for CSS and JS while developing, keep Vite running in a terminal:

```bash
npm run dev
```

Optionally, for background jobs (e.g. emails, Stripe):

```bash
php artisan queue:listen --tries=1
```

---

## Requirements (without Herd)

- **PHP 8.2+** (8.4 recommended)
- **Composer**
- **Node.js 18+** and **npm**

### Step-by-step setup (alternative)

If you prefer to run commands one by one:

```bash
composer install
# cp .env.example .env   # if .env doesn't exist
# php artisan key:generate
# touch database/database.sqlite   # on Bash; on CMD: type nul > database\database.sqlite
php artisan migrate
php artisan storage:link   # Required for brand logos on subdomain landings
npm install
npm run build
```

---

## Without Herd (php artisan serve)

```bash
# All in one terminal
composer run dev
```

Or in separate terminals:

```bash
php artisan serve
npm run dev
# optional: php artisan queue:listen --tries=1
```

The app will be at **http://localhost:8000**.

---

## Stripe (optional)

For payments / referrals, set in `.env`:

- `STRIPE_KEY`
- `STRIPE_SECRET`
- `STRIPE_WEBHOOK_SECRET` (for webhooks)

You can run the project without these; Stripe features will be disabled or throw errors when used.

---

## Useful commands summary

| Command               | Description                    |
|-----------------------|--------------------------------|
| `composer run setup`  | Full first-time setup          |
| `composer run dev`    | Start server + queue + Vite    |
| `npm run dev`         | Vite only (hot reload)        |
| `npm run build`       | Production frontend build      |
| `php artisan migrate` | Run migrations                 |
| `php artisan test`    | Run tests                      |
| `php artisan subdomains:sync` | Sync brand subdomains → hosts file |
| `php artisan subdomains:sync --dry-run` | Preview without applying |

---

## Troubleshooting: `composer: command not found` (Bash / terminal)

Herd adds `php` and `composer` to your PATH, but sometimes the terminal (e.g. Bash in Cursor) doesn’t see it.

**Solution 1 – Add Herd to PATH manually**

1. Press **Win + R** → type **sysdm.cpl** → Enter.
2. **Advanced** tab → **Environment Variables**.
3. Under **User variables**, select **Path** → **Edit** → **New**.
4. Add exactly: `%USERPROFILE%\.config\herd\bin`
5. OK on all dialogs, **close all terminals**, and open a new one.

**Solution 2 – Use PowerShell**

Open **PowerShell** (Start → PowerShell) and run:

```powershell
cd c:\workspace\referlyst.com
composer run setup
```

In PowerShell, Herd’s PATH is often already available.

**Solution 3 – Empty bin folder**

If `C:\Users\YOUR_USERNAME\.config\herd\bin` only contains `HerdHelper.exe` (no `php.exe` or `composer`), reinstall Herd from [herd.laravel.com](https://herd.laravel.com), then apply Solution 1.

---

## Subdomain-based landing pages

Brands on the **Starter or Growth** plan get a subdomain landing page in production (e.g. `acme-coffee.referlyst.com`). Brands on the **Free** plan use path-based routing only (`referlyst.com/acme-coffee`).

### Local (Windows + Herd)

**Zero-config — path URL works for every brand:**

| Plan | Local URL (no DNS) | Live URL (Cloudflare) |
|------|--------------------|------------------------|
| Free | `http://referlyst.test/acme-coffee` | `https://referlyst.com/acme-coffee` |
| Starter / Growth | `http://referlyst.test/acme-coffee` | `https://acme-coffee.referlyst.com` |

Use the path URL locally; no hosts file or DNS needed.

**Optional — true subdomain locally:** Herd uses the Windows hosts file (no wildcard). To use `http://acme-coffee.referlyst.test/`:

1. Run once: `php artisan subdomains:sync` — this creates `scripts/sync-subdomains.ps1` and `scripts/sync-subdomains.bat`.
2. **Right-click `scripts/sync-subdomains.bat`** → **Run as administrator**. (Or run the `.ps1` in PowerShell; it will prompt for admin.)
3. After it finishes, open `http://acme-coffee.referlyst.test/` in your browser.

**Automatic updates:** When you create or edit a brand and set or change its subdomain (path/subdomain field), the script is regenerated automatically so it always includes the latest list. Run the `.bat` as Administrator again to apply changes to your hosts file.

If subdomains stop working (e.g. after restarting Herd), run the `.bat` as administrator again. Re-run `php artisan subdomains:sync` only if you want to refresh the script from scratch.

### Live (Cloudflare)

1. **Wildcard DNS** in Cloudflare:

   | Type | Name | Content | Proxy |
   |------|------|---------|-------|
   | A | `*` | `<your-server-ip>` | ✅ Proxied |
   | A | `@` | `<your-server-ip>` | ✅ Proxied |

2. **SSL/TLS**: **Full (strict)** in Cloudflare → SSL/TLS.

3. **Trusted proxies**: Enabled automatically when not local (`bootstrap/app.php`). Laravel trusts Cloudflare’s `X-Forwarded-*` headers so `request()->secure()`, `request()->ip()`, and URLs are correct.

4. **Env**: `APP_URL=https://referlyst.com`, `APP_ENV=production` on the server.

Subdomain requests (e.g. `acme-coffee.referlyst.com`) hit your server via the wildcard; Laravel routes them with `Route::domain('{subdomain}.referlyst.com')`.

---

## Add-ons implementation status

| Add-on | Implemented | Notes |
|--------|-------------|--------|
| **Extra Referrers** | Yes | +500 referrers per active addon; `BrandReferral::maxReferrers()` includes package limit + addon count; used on landing page and in `LandingReferralSubmitRequest`. |
| **API Access** | Yes | GET `/api/v1/referral-form` returns all landing-page data (brand, services with commission, slots, URLs, primary_color). POST `/api/v1/referrals` submits referrals. Auth: `Authorization: Bearer <token>`. Generate token in brand edit (API Access section). |
| **Extra custom domain** | No | Currently one landing identifier per brand (`subdomain_for_landing_pages`). Would require storing multiple domains per brand and routing/middleware to resolve brand by domain. |

---

## Troubleshooting: "Herd not found" or localhost doesn’t show the app

With Herd, the app is **not** served on `http://localhost` or `http://localhost:5173`. It is served on a **`.test`** domain.

- Open **https://referlyst.test** (or **http://referlyst.test**) in your browser.
- If that doesn’t load, open the Herd app and check that this project’s folder is added as a site (Herd may show the exact `.test` URL there).
- `localhost:5173` is only Vite’s asset server; always use the `.test` URL to view the Laravel app.
