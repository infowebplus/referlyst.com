<?php

namespace App\Http\Middleware;

use App\Models\BrandApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiTokenScope
{
    /**
     * Verify the resolved API token has the required scope.
     * Legacy tokens (no BrandApiToken record) are granted all scopes for backward compatibility.
     */
    public function handle(Request $request, Closure $next, string $scope): Response
    {
        /** @var BrandApiToken|null $tokenModel */
        $tokenModel = $request->attributes->get('api_token');

        // Legacy token (null) → full access.
        if ($tokenModel === null) {
            return $next($request);
        }

        if (! $tokenModel->hasScope($scope)) {
            return response()->json([
                'message' => "This token does not have the '{$scope}' permission. Generate a new key with the required permissions.",
            ], 403);
        }

        return $next($request);
    }
}
