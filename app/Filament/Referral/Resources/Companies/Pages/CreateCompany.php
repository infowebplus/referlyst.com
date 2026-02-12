<?php

namespace App\Filament\Referral\Resources\Companies\Pages;

use App\Filament\Referral\Resources\Companies\CompanyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    protected function afterCreate(): void
    {
        auth()->user()?->update(['company_id' => $this->record->id]);
    }
}
