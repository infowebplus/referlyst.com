<?php

namespace App\Filament\Referral\Pages;

use App\Models\BrandReferral;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class MyBrands extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'My brands';

    protected static ?string $title = 'My brands';

    protected string $view = 'filament-panels::pages.page';

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            EmbeddedTable::make(),
        ]);
    }

    public function table(Table $table): Table
    {
        $companyId = auth()->user()?->company_id;
        $baseUrl = Filament::getPanel('referral')->getUrl();

        return $table
            ->query(
                BrandReferral::query()
                    ->where('company_id', $companyId)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('display_package_name')
                    ->label('Active package')
                    ->badge()
                    ->color(fn (BrandReferral $record): string => $record->active_package ? 'success' : 'gray'),
                TextColumn::make('active_package_expiry')
                    ->label('Package expires')
                    ->getStateUsing(fn (BrandReferral $record) => $record->active_package_expiry?->format('d M Y'))
                    ->placeholder('—')
                    ->sortable(false),
                TextColumn::make('active_addons_list')
                    ->label('Add-ons')
                    ->getStateUsing(fn (BrandReferral $record) => $record->active_addons->pluck('name')->join(', ') ?: '—')
                    ->wrap(),
                TextColumn::make('api_access_status')
                    ->label('API Access')
                    ->getStateUsing(function (BrandReferral $record): string {
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
                    ->color(fn (BrandReferral $record): string => $record->hasApiAccess() ? 'success' : (($record->api_token || $record->brandApiTokens()->exists()) ? 'warning' : 'gray'))
                    ->placeholder('—'),
                TextColumn::make('active_addons_expiry')
                    ->label('Add-ons expire')
                    ->getStateUsing(function (BrandReferral $record): string {
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
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (BrandReferral $record) => \App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\BrandReferralResource::getUrl('edit', [
                        'company' => $record->company_id,
                        'record' => $record,
                    ])),
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (BrandReferral $record) => \App\Filament\Referral\Resources\Companies\Resources\BrandReferrals\BrandReferralResource::getUrl('edit', [
                        'company' => $record->company_id,
                        'record' => $record,
                    ])),
                Action::make('upgrade')
                    ->label('Upgrade package')
                    ->icon('heroicon-o-arrow-trending-up')
                    ->url(fn (BrandReferral $record) => $baseUrl.'/purchase-packages?'.http_build_query(['brand_id' => $record->id]))
                    ->visible(fn (BrandReferral $record) => $record->active_package !== null),
                Action::make('add_addons')
                    ->label('Add addons')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn (BrandReferral $record) => $baseUrl.'/purchase-packages?'.http_build_query(['brand_id' => $record->id, 'addons_only' => 1])),
            ])
            ->defaultSort('name')
            ->striped()
            ->emptyStateHeading('No brands yet')
            ->emptyStateDescription('Create a company and add your first brand from My Company.')
            ->emptyStateIcon('heroicon-o-squares-2x2');
    }
}
