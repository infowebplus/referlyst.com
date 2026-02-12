<?php

namespace App\Models;

use App\CommissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'brand_id',
        'name',
        'commission_type',
        'commission',
        'texts',
        'pictures',
    ];

    /**
     * Get the brand that owns the service.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\BrandReferral, Service>
     */
    public function brandReferral(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BrandReferral::class, 'brand_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'commission_type' => CommissionType::class,
            'commission' => 'decimal:2',
            'pictures' => 'array',
        ];
    }
}
