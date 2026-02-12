<?php

namespace App\Filament\Referral\Pages;

use App\Models\Addon;
use App\Models\BrandReferral;
use App\Models\Package;
use App\Models\PendingPackagePurchase;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

/**
 * @property-read Schema $form
 */
class PurchasePackages extends Page
{
    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Purchase packages';

    protected static ?string $title = 'Purchase packages';

    protected string $view = 'filament-panels::pages.page';

    public static function getNavigationSort(): int
    {
        return 10;
    }

    public function mount(): void
    {
        if (session('success')) {
            Notification::make()->success()->title(session('success'))->send();
            session()->forget('success');
        }
        if (session('error')) {
            Notification::make()->danger()->title(session('error'))->send();
            session()->forget('error');
        }
        $this->callHook('beforeFill');
        $this->form->fill();
        $this->callHook('afterFill');
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        $user = auth()->user();
        $companyId = $user?->company_id;

        return $schema->components([
            Wizard::make([
                Step::make('Select brand')
                    ->schema([
                        \Filament\Forms\Components\Select::make('brand_id')
                            ->label('Brand')
                            ->options(
                                BrandReferral::query()
                                    ->where('company_id', $companyId)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->required()
                            ->searchable()
                            ->live(),
                    ]),
                Step::make('Select package')
                    ->description('Optional: skip if you only want to buy addons.')
                    ->schema([
                        \Filament\Forms\Components\Select::make('package_id')
                            ->label('Package')
                            ->options(Package::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->rule(function (Get $get): \Closure {
                                return function (string $attribute, $value, \Closure $fail) use ($get): void {
                                    if (! $value) {
                                        return;
                                    }
                                    $brandId = $get('brand_id');
                                    if (! $brandId) {
                                        return;
                                    }
                                    $brand = BrandReferral::find($brandId);
                                    if ($brand && $brand->active_package) {
                                        $fail('This brand already has an active package. You cannot purchase another package, or skip this step to buy addons only.');
                                    }
                                };
                            }),
                        \Filament\Forms\Components\Placeholder::make('skip_hint')
                            ->label('')
                            ->content(new HtmlString('<p class="text-sm text-gray-500 dark:text-gray-400">You may skip this step if you only want to purchase addons (no new package).</p>')),
                    ]),
                Step::make('Select addons')
                    ->schema([
                        \Filament\Forms\Components\CheckboxList::make('addon_ids')
                            ->label('Addons')
                            ->options(function (Get $get): array {
                                $packageId = $get('package_id');
                                $addonsQuery = Addon::query()->orderBy('name');
                                if ($packageId) {
                                    $includedIds = \DB::table('addon_package')
                                        ->where('package_id', $packageId)
                                        ->pluck('addon_id');
                                    $addonsQuery->whereNotIn('id', $includedIds);
                                }

                                return $addonsQuery->pluck('name', 'id')->toArray();
                            })
                            ->columns(1),
                    ]),
                Step::make('Review & pay')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('summary')
                            ->label('Order summary')
                            ->content(function (Get $get): string {
                                $brandId = $get('brand_id');
                                $packageId = $get('package_id');
                                $addonIds = $get('addon_ids') ?? [];
                                $lines = [];
                                if ($brandId) {
                                    $brand = BrandReferral::find($brandId);
                                    $lines[] = 'Brand: '.($brand?->name ?? '—');
                                }
                                if ($packageId) {
                                    $pkg = Package::find($packageId);
                                    $lines[] = 'Package: '.($pkg?->name ?? '—').' — '.($pkg ? number_format($pkg->price, 2).' EUR' : '');
                                }
                                foreach ($addonIds as $addonId) {
                                    $addon = Addon::find($addonId);
                                    $lines[] = 'Addon: '.($addon?->name ?? '—').' — '.($addon ? number_format($addon->price, 2).' EUR' : '');
                                }
                                $total = 0;
                                if ($packageId && $pkg = Package::find($packageId)) {
                                    $total += (float) $pkg->price;
                                }
                                foreach ($addonIds as $addonId) {
                                    $addon = Addon::find($addonId);
                                    if ($addon) {
                                        $total += (float) $addon->price;
                                    }
                                }
                                $lines[] = '---';
                                $lines[] = 'Total: '.number_format($total, 2).' EUR';

                                return implode("\n", $lines);
                            }),
                    ]),
            ])
                ->startOnStep(1)
                ->skippable(true)
                ->submitAction(new HtmlString('<button type="button" wire:click="proceedToPayment" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-btn-color-primary fi-btn-size-md fi-btn-outlined gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-transparent border border-custom-500 text-custom-600 hover:bg-custom-500/10 focus-visible:ring-custom-500/50 dark:border-custom-400 dark:text-custom-400 dark:hover:bg-custom-400/10 dark:focus-visible:ring-custom-400/50 fi-btn">Pay with Stripe</button>'))
                ->alpineSubmitHandler('$wire.proceedToPayment()'),
        ]);
    }

    public function getFormContentComponent(): Component
    {
        return Group::make([
            EmbeddedSchema::make('form'),
            Actions::make($this->getFormActions())->alignment(\Filament\Support\Enums\Alignment::Start)->key('form-actions'),
        ]);
    }

    /**
     * @return array<\Filament\Actions\Action>
     */
    protected function getFormActions(): array
    {
        return [];
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('proceedToPayment')
                ->footer([
                    Actions::make([])->key('form-actions'),
                ]),
        ]);
    }

    public function proceedToPayment(): void
    {
        $data = $this->form->getState();
        $brandId = $data['brand_id'] ?? null;
        $packageId = $data['package_id'] ?? null;
        $addonIds = $data['addon_ids'] ?? [];

        if (! $brandId) {
            Notification::make()->danger()->title('Please select a brand.')->send();

            return;
        }

        if (! $packageId && empty($addonIds)) {
            Notification::make()->danger()->title('Please select at least one package or addon.')->send();

            return;
        }

        $user = auth()->user();
        if (! $user?->company_id) {
            Notification::make()->danger()->title('You must have a company to purchase.')->send();

            return;
        }

        if ($packageId) {
            $brand = BrandReferral::find($brandId);
            if ($brand && $brand->active_package) {
                Notification::make()->danger()->title('This brand already has an active package.')->send();

                return;
            }
        }

        $pending = PendingPackagePurchase::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'brand_id' => $brandId,
            'package_id' => $packageId ?: null,
            'addon_ids' => array_values($addonIds),
        ]);

        $checkoutUrl = app(\App\Services\StripeCheckoutService::class)->createCheckoutSession($pending);

        if ($checkoutUrl) {
            $this->redirect($checkoutUrl);
        } else {
            Notification::make()->danger()->title('Could not create payment session. Please check Stripe configuration.')->send();
        }
    }
}
