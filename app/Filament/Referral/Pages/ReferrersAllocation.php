<?php

namespace App\Filament\Referral\Pages;

use App\Models\BrandReferral;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ReferrersAllocation extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Referrers allocation';

    protected static ?string $title = 'Referrers allocation';

    protected string $view = 'filament-panels::pages.page';

    protected static string|\UnitEnum|null $navigationGroup = 'Reports';

    public static function getNavigationSort(): int
    {
        return 20;
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

        return $table
            ->query(
                BrandReferral::query()
                    ->where('company_id', $companyId)
                    ->withCount('referrerBrands')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('referrer_brands_count')
                    ->label('Referrers allocated')
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->striped();
    }
}
