<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'default_package',
        'price',
        'description',
        'email_notifications',
        'no_of_referrers',
        'no_of_referrers_brands',
        'commission_tracking',
        'export_csv',
        'custom_branding',
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
}
