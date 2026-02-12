<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingPackagePurchase extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'brand_id',
        'package_id',
        'addon_ids',
        'stripe_checkout_session_id',
        'completed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'addon_ids' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, PendingPackagePurchase>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Company, PendingPackagePurchase>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo<BrandReferral, PendingPackagePurchase>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandReferral::class, 'brand_id');
    }

    /**
     * @return BelongsTo<Package, PendingPackagePurchase>
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
