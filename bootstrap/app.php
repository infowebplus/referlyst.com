<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust proxies only in production (Cloudflare). Local (Herd / .test) does not use a proxy.
        $env = env('APP_ENV', 'production');
        $landingEnv = env('REFERLYST_LANDING_ENV');
        $isLocal = $landingEnv === 'local' || $env === 'local' || str_ends_with(parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?? '', '.test');
        if (! $isLocal) {
            $middleware->trustProxies(at: '*');
        }

        $middleware->alias([
            'api.brand.token' => \App\Http\Middleware\ResolveBrandByApiToken::class,
            'api.scope' => \App\Http\Middleware\CheckApiTokenScope::class,
            'resolve.brand.subdomain' => \App\Http\Middleware\ResolveBrandBySubdomain::class,
            'resolve.brand.path' => \App\Http\Middleware\ResolveBrandByPath::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
