<?php

namespace App\Models;

use App\CommissionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferrerBrand extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referrers_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'brand_id',
        'service_id',
        'comission_type',
        'comission',
        'date',
        'recommended_to_name',
        'recommended_to_surname',
        'recommended_to_phone',
        'recommended_to_email',
        'paid',
        'paid_sum',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'comission_type' => CommissionType::class,
            'comission' => 'decimal:2',
            'date' => 'date',
            'paid' => 'boolean',
            'paid_sum' => 'integer',
        ];
    }

    /**
     * Get the user that made the referral.
     *
     * @return BelongsTo<User, ReferrerBrand>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company for this referral.
     *
     * @return BelongsTo<Company, ReferrerBrand>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the brand (brands_referral) for this referral.
     *
     * @return BelongsTo<BrandReferral, ReferrerBrand>
     */
    public function brandReferral(): BelongsTo
    {
        return $this->belongsTo(BrandReferral::class, 'brand_id');
    }

    /**
     * Get the service for this referral.
     *
     * @return BelongsTo<Service, ReferrerBrand>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
