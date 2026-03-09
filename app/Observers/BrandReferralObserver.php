<?php

namespace App\Observers;

use App\Models\BrandReferral;
use App\Services\SubdomainHostsSyncService;

class BrandReferralObserver
{
    public function __construct(
        private SubdomainHostsSyncService $syncService
    ) {}

    /**
     * Handle the BrandReferral "saved" event (created or updated).
     * Regenerates the local hosts sync script so it always includes current subdomains.
     */
    public function saved(BrandReferral $brandReferral): void
    {
        $this->syncService->regenerateScript();
    }

    /**
     * Handle the BrandReferral "deleted" event.
     * Regenerate script so the removed brand's subdomain is no longer in the script.
     */
    public function deleted(BrandReferral $brandReferral): void
    {
        $this->syncService->regenerateScript();
    }

    /**
     * Handle the BrandReferral "created" event.
     */
    public function created(BrandReferral $brandReferral): void
    {
        //
    }

    /**
     * Handle the BrandReferral "updated" event.
     */
    public function updated(BrandReferral $brandReferral): void
    {
        //
    }

    /**
     * Handle the BrandReferral "restored" event.
     */
    public function restored(BrandReferral $brandReferral): void
    {
        $this->syncService->regenerateScript();
    }

    /**
     * Handle the BrandReferral "force deleted" event.
     */
    public function forceDeleted(BrandReferral $brandReferral): void
    {
        $this->syncService->regenerateScript();
    }
}
