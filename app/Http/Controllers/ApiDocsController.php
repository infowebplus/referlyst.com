<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ApiDocsController extends Controller
{
    /**
     * Show API v1 documentation. Public; addon validity is enforced on actual API requests.
     */
    public function v1(): View
    {
        $baseUrl = rtrim(config('app.url'), '/');

        return view('docs.v1', [
            'baseUrl' => $baseUrl,
            'referralFormUrl' => $baseUrl.'/api/v1/referral-form',
            'referralsUrl' => $baseUrl.'/api/v1/referrals',
        ]);
    }
}
