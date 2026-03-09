<?php

namespace App;

enum ApiTokenScope: string
{
    case Form = 'form';
    case Referrals = 'referrals';

    public function label(): string
    {
        return match ($this) {
            self::Form => 'Landing page form',
            self::Referrals => 'Referral submissions',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Form => 'Read brand & form data (GET /api/v1/referral-form)',
            self::Referrals => 'Submit new referrals (POST /api/v1/referrals)',
        };
    }
}
