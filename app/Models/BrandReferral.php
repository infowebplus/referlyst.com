<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Store only the image filename (no path) in the database.
     */
    protected function logo(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            set: fn (?string $value) => $value ? basename($value) : null,
        );
    }
}
