<?php

namespace App\Actions;

use App\Models\BrandApiToken;
use App\Models\BrandReferral;

class RevokeApiKeyAction
{
    /**
     * Delete the given token, scoped to the brand to prevent cross-brand revocation.
     */
    public function execute(BrandReferral $brand, int $tokenId): void
    {
        BrandApiToken::query()
            ->where('id', $tokenId)
            ->where('brand_id', $brand->id)
            ->delete();
    }
}
