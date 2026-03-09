<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Landing page environment
    |--------------------------------------------------------------------------
    |
    | Distinguishes local development (e.g. Windows + Herd, .test domain,
    | path-based URLs, optional hosts file sync) from live production behind
    | Cloudflare (subdomain URLs, wildcard DNS, trusted proxies).
    |
    | Set to 'local' or 'production', or leave null to auto-detect:
    | - local: REFERLYST_LANDING_ENV=local, or APP_ENV=local, or APP_URL host ends with .test
    | - production: otherwise (Cloudflare, wildcard DNS, proxy headers)
    |
    */

    'landing' => [
        'environment' => env('REFERLYST_LANDING_ENV') ?? (
            env('APP_ENV') === 'local' || str_ends_with(parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?? '', '.test')
                ? 'local'
                : 'production'
        ),
    ],

];
