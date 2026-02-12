<?php

namespace App\Filament\Referral\Resources\Companies\Pages;

use App\Filament\Referral\Resources\Companies\CompanyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function () {
                    auth()->user()?->update(['company_id' => null]);
                }),
        ];
    }
}
