<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrandReferral extends Model
{
    /** @use HasFactory<\Database\Factories\BrandReferralFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brands_referral';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'logo',
        'website',
        'terms_and_conditions',
        'ca',
        'subdomain_for_landing_pages',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_security',
        'colors',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'colors' => 'array',
        ];
    }

    /**
     * Get the company that owns the brand.
     *
     * @return BelongsTo<Company, BrandReferral>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the brand packages (packages and addons) for this brand.
     *
     * @return HasMany<BrandPackage, BrandReferral>
     */
    public function brandPackages(): HasMany
    {
        return $this->hasMany(BrandPackage::class, 'brand_id');
    }

    /**
     * Get the referrer brands (allocations) for this brand.
     *
     * @return HasMany<ReferrerBrand, BrandReferral>
     */
    public function referrerBrands(): HasMany
    {
        return $this->hasMany(ReferrerBrand::class, 'brand_id');
    }

    /**
     * Get the services for this brand.
     *
     * @return HasMany<Service, BrandReferral>
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'brand_id');
    }

    /**
     * Get the active package for this brand (expiry_date null or in the future).
     */
    public function getActivePackageAttribute(): ?Package
    {
        return $this->brandPackages()
            ->where('model_type', Package::class)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->with('model')
            ->first()?->model;
    }

    /**
     * Get the package name to display in the brands table: the active brand package
     * if one exists with a future expiry, otherwise the default package name.
     */
    public function getDisplayPackageNameAttribute(): string
    {
        $activePackage = $this->active_package;

        if ($activePackage !== null) {
            return $activePackage->name;
        }

        $defaultPackage = Package::query()->where('default_package', true)->first();

        return $defaultPackage?->name ?? '—';
    }

    /**
     * Get the active addons for this brand (expiry_date null or in the future).
     *
     * @return \Illuminate\Support\Collection<int, Addon>
     */
    public function getActiveAddonsAttribute(): \Illuminate\Support\Collection
    {
        $records = $this->brandPackages()
            ->where('model_type', Addon::class)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->with('model')
            ->get();

        return $records->map(fn (BrandPackage $bp) => $bp->model)->filter();
    }

    /**
     * Store only the image filename (no path) in the database.
     */
    protected function logo(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            set: fn (?string $value) => $value ? basename($value) : null,
        );
    }
}
