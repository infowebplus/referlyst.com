<?php

namespace App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BrandReferralsTable
{
    public static function configure(Table $table): Table
    {
        $baseUrl = fn () => Filament::getPanel('referral')->getUrl();

        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('display_package_name')
                    ->label('Package')
                    ->getStateUsing(fn ($record) => $record->display_package_name),
                TextColumn::make('active_package_expiry')
                    ->label('Package expires')
                    ->getStateUsing(fn ($record) => $record->active_package_expiry?->format('d M Y'))
                    ->placeholder('—')
                    ->sortable(false),
                TextColumn::make('active_addons_list')
                    ->label('Active add-ons')
                    ->getStateUsing(fn ($record) => $record->active_addons->pluck('name')->join(', ') ?: '—')
                    ->wrap(),
                TextColumn::make('api_access_status')
                    ->label('API Access')
                    ->getStateUsing(function ($record): string {
                        if ($record->hasApiAccess()) {
                            return 'API Access';
                        }
                        $hasTokens = $record->api_token !== null && $record->api_token !== ''
                            || $record->brandApiTokens()->exists();
                        if ($hasTokens) {
                            return 'API Access expired';
                        }

                        return '—';
                    })
                    ->badge()
                    ->color(fn ($record): string => $record->hasApiAccess() ? 'success' : (($record->api_token || $record->brandApiTokens()->exists()) ? 'warning' : 'gray'))
                    ->placeholder('—'),
                TextColumn::make('active_addons_expiry')
                    ->label('Add-ons expire')
                    ->getStateUsing(function ($record): string {
                        $items = $record->activeAddonsWithExpiry->map(function ($item) {
                            $exp = $item['expiry_date'] instanceof \Carbon\Carbon
                                ? $item['expiry_date']->format('d M Y')
                                : '—';

                            return $item['name'].': '.$exp;
                        });

                        return $items->join(' · ');
                    })
                    ->placeholder('—')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('website')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subdomain_for_landing_pages')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => \App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\BrandReferralResource::getUrl('edit', [
                        'company' => $record->company_id,
                        'record' => $record,
                    ])),
                EditAction::make(),
                Action::make('upgrade')
                    ->label('Upgrade package')
                    ->icon('heroicon-o-arrow-trending-up')
                    ->url(fn ($record) => $baseUrl().'/purchase-packages?'.http_build_query(['brand_id' => $record->id]))
                    ->visible(fn ($record) => $record->active_package !== null),
                Action::make('add_addons')
                    ->label('Add addons')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn ($record) => $baseUrl().'/purchase-packages?'.http_build_query(['brand_id' => $record->id, 'addons_only' => 1])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
