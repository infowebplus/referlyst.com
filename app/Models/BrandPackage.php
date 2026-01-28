<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BrandPackage extends Model
{
    /** @use HasFactory<\Database\Factories\BrandPackageFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'brand_id',
        'model_id',
        'model_type',
        'price',
        'purchase_date',
        'expiry_date',
        'details',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'purchase_date' => 'date',
            'expiry_date' => 'date',
            'details' => 'array',
        ];
    }

    /**
     * Get the brand referral that owns this package.
     *
     * @return BelongsTo<BrandReferral, BrandPackage>
     */
    public function brandReferral(): BelongsTo
    {
        return $this->belongsTo(BrandReferral::class, 'brand_id');
    }

    /**
     * Get the parent model (Package or Addon).
     *
     * @return MorphTo<Model, BrandPackage>
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
