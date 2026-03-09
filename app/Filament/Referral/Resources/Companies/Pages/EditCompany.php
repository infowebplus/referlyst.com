<?php

namespace App\Filament\Referral\Resources\Companies\Pages;

use App\Filament\Referral\Resources\Companies\CompanyResource;
use App\Services\ReferralBrandColors;
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

    public function mutateFormDataBeforeFill(array $data): array
    {
        if (empty($data['default_brand_id'])) {
            $first = $this->record->brandReferrals()->orderBy('id')->first();
            $data['default_brand_id'] = $first?->id;
        }

        $ac = $data['admin_menu_colors'] ?? [];
        if (empty($ac['main'] ?? null)) {
            $data['admin_menu_colors'] = array_merge($ac, ['main' => ReferralBrandColors::REFERLYST_PRIMARY]);
        }
        $ac = $data['admin_menu_colors'] ?? [];
        if (empty($ac['secondary'] ?? null)) {
            $data['admin_menu_colors'] = array_merge($ac, ['secondary' => ReferralBrandColors::DEFAULT_MENU_BG]);
        }

        return $data;
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['default_brand_id'])) {
            $first = $this->record->brandReferrals()->orderBy('id')->first();
            $data['default_brand_id'] = $first?->id;
        }

        return $data;
    }
}
