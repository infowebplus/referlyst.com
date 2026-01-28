<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'brand_id',
        'date',
        'model_id',
        'model_type',
        'price',
        'paid',
        'paid_date',
        'paid_response',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'price' => 'decimal:2',
            'paid' => 'boolean',
            'paid_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the order.
     *
     * @return BelongsTo<User, Order>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company that the order belongs to.
     *
     * @return BelongsTo<Company, Order>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the brand referral that the order belongs to.
     *
     * @return BelongsTo<BrandReferral, Order>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandReferral::class, 'brand_id');
    }

    /**
     * Get the parent model (package or addon).
     *
     * @return MorphTo<Model, Order>
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
