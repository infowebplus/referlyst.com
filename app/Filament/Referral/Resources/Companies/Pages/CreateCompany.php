<?php

namespace App\Filament\Referral\Resources\Companies\Pages;

use App\Filament\Referral\Resources\Companies\CompanyResource;
use App\Services\ReferralBrandColors;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['admin_menu_colors']['main'] ?? null) || empty($data['admin_menu_colors']['secondary'] ?? null)) {
            $data['admin_menu_colors'] = array_merge($data['admin_menu_colors'] ?? [], [
                'main' => $data['admin_menu_colors']['main'] ?? ReferralBrandColors::REFERLYST_PRIMARY,
                'secondary' => $data['admin_menu_colors']['secondary'] ?? ReferralBrandColors::DEFAULT_MENU_BG,
            ]);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        auth()->user()?->update(['company_id' => $this->record->id]);
    }
}
