<?php

namespace App\Http\Middleware;

use App\Models\BrandReferral;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveBrandByPath
{
    /**
     * Resolve brand by path segment (subdomain_for_landing_pages) and set request attributes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->route('path');

        if (! $path) {
            abort(404);
        }

        $brand = BrandReferral::query()
            ->whereNotNull('subdomain_for_landing_pages')
            ->where('subdomain_for_landing_pages', $path)
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
