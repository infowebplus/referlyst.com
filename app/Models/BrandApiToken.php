<?php

namespace App\Models;

use App\ApiTokenScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class BrandApiToken extends Model
{
    protected $table = 'brand_api_tokens';

    protected $fillable = [
        'brand_id',
        'name',
        'token_hash',
        'scopes',
    ];

    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
            'scopes' => 'array',
        ];
    }

    public function brandReferral(): BelongsTo
    {
        return $this->belongsTo(BrandReferral::class, 'brand_id');
    }

    public function hasScope(ApiTokenScope|string $scope): bool
    {
        $value = $scope instanceof ApiTokenScope ? $scope->value : $scope;

        return in_array($value, $this->scopes ?? [], true);
    }

    /**
     * @param  list<string>  $scopes
     */
    public static function createTokenForBrand(BrandReferral $brand, ?string $name = null, array $scopes = ['form', 'referrals']): string
    {
        $plain = \Illuminate\Support\Str::random(64);
        self::query()->create([
            'brand_id' => $brand->id,
            'name' => $name ?: 'API key',
            'token_hash' => Hash::make($plain),
            'scopes' => $scopes,
        ]);

        return $plain;
    }

    /**
     * Resolve a BrandApiToken by its plaintext bearer value, updating last_used_at.
     * Returns null if no matching token is found.
     */
    public static function findByBearer(string $plain): ?self
    {
        $token = self::query()
            ->whereNotNull('token_hash')
            ->get()
            ->first(fn (self $t) => Hash::check($plain, $t->token_hash));

        if ($token) {
            $token->update(['last_used_at' => now()]);
        }

        return $token;
    }

    /**
     * Resolve the brand from a bearer token, checking both the new token table
     * and the legacy api_token column on BrandReferral for backward compatibility.
     */
    public static function findBrandByToken(string $token): ?BrandReferral
    {
        $row = self::findByBearer($token);

        if ($row) {
            return $row->brandReferral;
        }

        // Legacy: token stored as a hashed value directly on the brand record.
        $brands = BrandReferral::query()
            ->whereNotNull('api_token')
            ->get();

        return $brands->first(fn (BrandReferral $b) => Hash::check($token, $b->api_token));
    }
}
