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

    /** When true, wizard starts on Add-ons step (from "Add addons" link with brand_id + addons_only). */
    public bool $startOnAddonsStep = false;

    /** When true, wizard starts on Plan step (from "Upgrade package" link with brand_id). */
    public bool $startOnPlanStep = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Purchase packages';

    protected static ?string $title = 'Get your plan';

    protected string $view = 'filament.referral.pages.purchase-packages';

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

        $defaults = [];
        $brandId = request()->integer('brand_id');
        $addonsOnly = request()->boolean('addons_only');

        if ($brandId) {
            $companyId = auth()->user()?->company_id;
            $brand = $companyId
                ? BrandReferral::query()->where('company_id', $companyId)->where('id', $brandId)->first()
                : null;

            if ($brand) {
                $defaults['brand_id'] = $brandId;

                if ($addonsOnly) {
                    $this->startOnAddonsStep = true;
                    $defaults['package_id'] = $brand->active_package?->id
                        ?? Package::query()->where('default_package', true)->first()?->id;
                    $defaults['addon_ids'] = $brand->activeAddonsWithExpiry->pluck('id')->values()->all();
                } else {
                    $this->startOnPlanStep = true;
                }
            }
        }

        $this->form->fill($defaults);

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
                Step::make('Brand')
                    ->description('Which brand is this for?')
                    ->schema([
                        Group::make([
                            \Filament\Forms\Components\Select::make('brand_id')
                                ->label('')
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
                    ]),
                Step::make('Plan')
                    ->description('Upgrade to a new plan, or skip to add-ons only.')
                    ->schema([
                        Group::make([
                            \Filament\Forms\Components\Select::make('package_id')
                                ->label('')
                                ->options(function (Get $get): array {
                                    $brandId = $get('brand_id');
                                    $query = Package::query()->orderBy('price');
                                    if ($brandId) {
                                        $brand = BrandReferral::find($brandId);
                                        if ($brand?->active_package) {
                                            $query->where('id', '!=', $brand->active_package->id);
                                        }
                                    }

                                    return $query->pluck('name', 'id')->toArray();
                                })
                                ->searchable()
                                ->live(),
                            \Filament\Forms\Components\Placeholder::make('skip_hint')
                                ->label('')
                                ->content(new HtmlString('<p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Skip this step to buy add-ons only.</p>')),
                        ]),
                    ]),
                Step::make('Add-ons')
                    ->description('Current add-ons are pre-checked with expiry. Select more to purchase.')
                    ->schema([
                        Group::make([
                            \Filament\Forms\Components\CheckboxList::make('addon_ids')
                                ->label('')
                                ->options(function (Get $get): array {
                                    $brandId = $get('brand_id');
                                    $packageId = $get('package_id');
                                    $brand = $brandId ? BrandReferral::find($brandId) : null;
                                    $activeWithExpiry = $brand ? $brand->activeAddonsWithExpiry : collect();
                                    $includedInPackage = $packageId
                                        ? \DB::table('addon_package')->where('package_id', $packageId)->pluck('addon_id')->all()
                                        : [];
                                    $activeIds = $activeWithExpiry->pluck('id')->all();

                                    $options = [];
                                    foreach ($activeWithExpiry as $item) {
                                        $expiry = $item['expiry_date'] instanceof \Carbon\Carbon
                                            ? $item['expiry_date']->format('d/m/Y')
                                            : '—';
                                        $options[$item['id']] = $item['name'].' (expires '.$expiry.')';
                                    }
                                    $addonsQuery = Addon::query()->orderBy('name');
                                    $addonsQuery->whereNotIn('id', array_keys($options));
                                    if ($includedInPackage !== []) {
                                        $addonsQuery->whereNotIn('id', $includedInPackage);
                                    }
                                    foreach ($addonsQuery->get() as $addon) {
                                        $options[$addon->id] = $addon->name;
                                    }

                                    return $options;
                                })
                                ->columns(1),
                        ]),
                    ]),
                Step::make('Review')
                    ->description('Check your selection and pay securely.')
                    ->schema([
                        Group::make([
                            \Filament\Forms\Components\Placeholder::make('summary')
                                ->label('')
                                ->content(function (Get $get): HtmlString {
                                    $brandId = $get('brand_id');
                                    $packageId = $get('package_id');
                                    $addonIds = $get('addon_ids') ?? [];
                                    $addonIds = array_values($addonIds);
                                    $brand = $brandId ? BrandReferral::find($brandId) : null;
                                    $activeWithExpiry = $brand ? $brand->activeAddonsWithExpiry->keyBy('id') : collect();
                                    $newAddonIds = $brand
                                        ? array_values(array_diff($addonIds, $activeWithExpiry->pluck('id')->all()))
                                        : $addonIds;
                                    $lines = [];
                                    $total = 0;
                                    if ($brandId) {
                                        $lines[] = '<li><span>Brand</span><span>'.e($brand?->name ?? '—').'</span></li>';
                                    }
                                    if ($packageId && ($pkg = Package::find($packageId))) {
                                        $price = (float) $pkg->price;
                                        $lines[] = '<li><span>'.e($pkg->name).'</span><span>'.($price > 0 ? number_format($price, 2).' EUR' : 'Free').'</span></li>';
                                        $total += $price;
                                    }
                                    foreach ($addonIds as $addonId) {
                                        $addon = Addon::find($addonId);
                                        if (! $addon) {
                                            continue;
                                        }
                                        $isCurrent = $activeWithExpiry->has($addonId);
                                        if ($isCurrent) {
                                            $item = $activeWithExpiry->get($addonId);
                                            $expiry = isset($item['expiry_date']) && $item['expiry_date'] instanceof \Carbon\Carbon
                                                ? $item['expiry_date']->format('d/m/Y')
                                                : '—';
                                            $lines[] = '<li><span>'.e($addon->name).' (current, expires '.$expiry.')</span><span>—</span></li>';
                                        } else {
                                            $lines[] = '<li><span>'.e($addon->name).'</span><span>'.number_format((float) $addon->price, 2).' EUR</span></li>';
                                            $total += (float) $addon->price;
                                        }
                                    }
                                    $lines[] = '<li class="purchase-summary-total"><span>Total</span><span>'.number_format($total, 2).' EUR</span></li>';
                                    $html = '<div class="purchase-summary-box"><div class="purchase-summary-title">Order summary</div><ul class="purchase-summary-list">'.implode('', $lines).'</ul></div>';

                                    return new HtmlString($html);
                                }),
                        ]),
                    ]),
            ])
                ->contained(true)
                ->startOnStep($this->startOnAddonsStep ? 3 : ($this->startOnPlanStep ? 2 : 1))
                ->skippable(true)
                ->submitAction(new HtmlString('<button type="button" wire:click="proceedToPayment" class="purchase-flow-cta fi-btn fi-btn-color-primary fi-btn-size-lg grid-flow-col items-center justify-center gap-2 rounded-xl font-semibold shadow-sm outline-none transition focus-visible:ring-2 fi-btn">Pay securely</button>'))
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
        $addonIds = array_values($addonIds);

        if (! $brandId) {
            Notification::make()->danger()->title('Please select a brand.')->send();

            return;
        }

        $brand = BrandReferral::find($brandId);
        if (! $brand) {
            Notification::make()->danger()->title('Invalid brand.')->send();

            return;
        }

        $activeAddonIds = $brand->activeAddonsWithExpiry->pluck('id')->all();
        $addonIdsToCharge = array_values(array_diff($addonIds, $activeAddonIds));

        if (empty($addonIdsToCharge) && ! $packageId) {
            Notification::make()->danger()->title('Please select at least one package or new add-on to purchase.')->send();

            return;
        }

        $user = auth()->user();
        if (! $user?->company_id) {
            Notification::make()->danger()->title('You must have a company to purchase.')->send();

            return;
        }

        // Add-ons only but brand has no plan: assign default free plan.
        if (! $packageId && ! empty($addonIdsToCharge)) {
            $freePackage = $brand->active_package
                ?? Package::query()->where('default_package', true)->first()
                ?? Package::query()->where('slug', 'free')->first();
            if ($freePackage) {
                $packageId = $freePackage->id;
            }
        }

        $pending = PendingPackagePurchase::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'brand_id' => $brandId,
            'package_id' => $packageId ?: null,
            'addon_ids' => $addonIdsToCharge,
        ]);

        $checkoutUrl = app(\App\Services\StripeCheckoutService::class)->createCheckoutSession($pending);

        if ($checkoutUrl) {
            $this->redirect($checkoutUrl);
        } else {
            Notification::make()->danger()->title('Could not create payment session. Please check Stripe configuration.')->send();
        }
    }
}
