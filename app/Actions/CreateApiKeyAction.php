<?php

namespace App\Actions;

use App\Models\BrandApiToken;
use App\Models\BrandReferral;

class CreateApiKeyAction
{
    /**
     * Generate a new API token for the given brand and return the plaintext value.
     *
     * @param  list<string>  $scopes
     */
    public function execute(BrandReferral $brand, string $name = '', array $scopes = []): string
    {
        $name = trim($name) ?: 'API key';
        $scopes = count($scopes) > 0 ? $scopes : ['form', 'referrals'];

        return BrandApiToken::createTokenForBrand($brand, $name, $scopes);
    }
}
