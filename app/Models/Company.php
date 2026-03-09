<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'legal_name',
        'registration_number',
        'vat_number',
        'billing_email',
        'billing_address',
        'billing_city',
        'billing_postal_code',
        'billing_country',
        'default_brand_id',
        'admin_menu_colors',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'admin_menu_colors' => 'array',
        ];
    }

    /**
     * Get the default brand for this company (used for panel colors).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<BrandReferral, Company>
     */
    public function defaultBrand(): BelongsTo
    {
        return $this->belongsTo(BrandReferral::class, 'default_brand_id');
    }

    /**
     * Get the users that belong to the company.
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the brand referrals that belong to the company.
     *
     * @return HasMany<BrandReferral>
     */
    public function brandReferrals(): HasMany
    {
        return $this->hasMany(BrandReferral::class, 'company_id');
    }

    /**
     * Get the orders that belong to the company.
     *
     * @return HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the referrer brands that belong to the company.
     *
     * @return HasMany<ReferrerBrand>
     */
    public function referrerBrands(): HasMany
    {
        return $this->hasMany(ReferrerBrand::class);
    }
}
