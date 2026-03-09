<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Pages;

use App\Actions\CreateApiKeyAction;
use App\Actions\RevokeApiKeyAction;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\BrandReferralResource;
use App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Support\BrandReferralFormData;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBrandReferral extends EditRecord
{
    protected static string $resource = BrandReferralResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Generate a new API token for the current brand.
     * Dispatches a browser event so Alpine can display the token without touching
     * Livewire form state (which would wipe all other form fields).
     *
     * @param  list<string>  $scopes
     */
    public function createApiKey(string $name = '', array $scopes = []): void
    {
        $plain = (new CreateApiKeyAction)->execute($this->record, $name, $scopes);

        $this->data['api_tokens_list'] = BrandReferralFormData::apiTokensList($this->record);

        $this->dispatch('rly-key-created', token: $plain);
    }

    /**
     * Revoke (delete) one API token, scoped to the current brand.
     */
    public function revokeApiKey(int $id): void
    {
        (new RevokeApiKeyAction)->execute($this->record, $id);

        $this->data['api_tokens_list'] = BrandReferralFormData::apiTokensList($this->record);
    }

    /**
     * Populate feature-flag fields before the form renders.
     * BrandReferralFormData loads all active BrandPackages in a single query and
     * derives every flag from the in-memory collection, avoiding N+1 queries.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateFormDataBeforeFill(array $data): array
    {
        return array_merge($data, BrandReferralFormData::forRecord($this->record));
    }
}
