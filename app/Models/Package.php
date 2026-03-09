<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Package extends Model
{
    /** @use HasFactory<\Database\Factories\PackageFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'default_package',
        'price',
        'description',
        'short_description',
        'domain_structure',
        'badge',
        'cta_label',
        'sort_order',
        'email_notifications',
        'no_of_referrers',
        'no_of_referrers_brands',
        'commission_tracking',
        'export_csv',
        'custom_branding',
        'custom_landing_page',
        'limitations',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default_package' => 'boolean',
            'price' => 'decimal:2',
            'email_notifications' => 'boolean',
            'commission_tracking' => 'boolean',
            'export_csv' => 'boolean',
            'custom_branding' => 'boolean',
            'custom_landing_page' => 'boolean',
            'limitations' => 'array',
        ];
    }

    /**
     * Get the addons included in this package.
     *
     * @return BelongsToMany<Addon, Package>
     */
    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'addon_package');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get feature list for pricing card, built from package attributes.
     * Kept short for clarity (max ~6 items).
     *
     * @return array<int, string>
     */
    public function getFeaturesList(): array
    {
        $features = [];

        if ($this->no_of_referrers_brands > 0) {
            $features[] = (int) $this->no_of_referrers_brands === 1
                ? '1 brand'
                : 'Up to '.$this->no_of_referrers_brands.' brands';
        } elseif ($this->no_of_referrers_brands === 0) {
            $features[] = 'Unlimited brands';
        }

        if ($this->no_of_referrers > 0) {
            $features[] = 'Up to '.$this->no_of_referrers.' referrers';
        } elseif ($this->no_of_referrers === 0) {
            $features[] = 'Unlimited referrers';
        }

        $features[] = 'Referral & commission tracking';

        if ($this->email_notifications) {
            $features[] = 'Email notifications';
        }
        if ($this->export_csv) {
            $features[] = 'CSV export';
        }

        if ($this->slug === 'starter') {
            $features[] = 'Custom branding (logo + colors)';
            $features[] = 'Subdomain hosting';
            $features[] = 'Remove Referlyst branding';
        } elseif ($this->custom_branding && $this->slug !== 'starter') {
            $features[] = 'Custom branding';
        }

        if ($this->slug === 'growth') {
            $features[] = 'Custom domain support';
            $features[] = 'API Access';
            $features[] = 'Priority support';
        } elseif ($this->custom_landing_page) {
            $features[] = 'Custom domain & landing page';
        }

        return $features;
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Package $package): void {
            if (empty($package->slug) && ! empty($package->name)) {
                $base = Str::slug($package->name);
                $slug = $base;
                $count = 0;
                while (static::query()->where('slug', $slug)->exists()) {
                    $count++;
                    $slug = $base.'-'.$count;
                }
                $package->slug = $slug;
            }
        });
    }
}
