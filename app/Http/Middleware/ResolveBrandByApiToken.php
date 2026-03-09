<?php

namespace App\Http\Middleware;

use App\Models\BrandApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveBrandByApiToken
{
    /**
     * Resolve brand from Authorization Bearer token. Brand must have API Access addon.
     * Also sets 'api_token' on request attributes when a BrandApiToken record is found
     * (null for legacy tokens stored directly on the brand), so scope checks downstream
     * can inspect per-token permissions.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $plain = $request->bearerToken();

        if (! $plain) {
            return response()->json(['message' => 'Missing or invalid API token.'], 401);
        }

        // Try the brand_api_tokens table first (supports scopes).
        $tokenModel = BrandApiToken::findByBearer($plain);

        if ($tokenModel) {
            $brand = $tokenModel->brandReferral;
            $request->attributes->set('api_token', $tokenModel);
        } else {
            // Fall back to the legacy api_token column on the brand record.
            $brand = BrandApiToken::findBrandByToken($plain);
            $request->attributes->set('api_token', null);
        }

        if (! $brand) {
            return response()->json(['message' => 'Invalid API token.'], 401);
        }

        if (! $brand->hasApiAccess()) {
            return response()->json([
                'message' => 'API Access addon has expired or is not active for this brand. Renew the addon to continue using the API.',
            ], 403);
        }

        $request->attributes->set('api_brand', $brand);

        return $next($request);
    }
}
