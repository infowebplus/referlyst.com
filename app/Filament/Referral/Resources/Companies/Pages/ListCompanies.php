<?php

namespace App\Filament\Referral\Resources\Companies\Pages;

use App\Filament\Referral\Resources\Companies\CompanyResource;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    public function mount(): void
    {
        $companyId = auth()->user()?->company_id;

        if ($companyId) {
            $this->redirect(CompanyResource::getUrl('edit', ['record' => $companyId]));
        } else {
            $this->redirect(CompanyResource::getUrl('create'));
        }
    }
}
