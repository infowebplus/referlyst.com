<?php

namespace App\Http\Middleware;

use App\Models\BrandReferral;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveBrandBySubdomain
{
    /**
     * Handle an incoming request. Resolve brand by subdomain and store in session.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = $request->route('subdomain');

        if (! $subdomain) {
            abort(404);
        }

        $brand = BrandReferral::query()
            ->whereNotNull('subdomain_for_landing_pages')
            ->where('subdomain_for_landing_pages', $subdomain)
            ->first();

        if (! $brand) {
            abort(404);
        }

        session([
            'landing_brand_id' => $brand->id,
            'landing_company_id' => $brand->company_id,
        ]);

        if (auth()->check()) {
            session(['landing_user_id' => auth()->id()]);
        }

        $request->attributes->set('landing_brand', $brand);

        return $next($request);
    }
}
