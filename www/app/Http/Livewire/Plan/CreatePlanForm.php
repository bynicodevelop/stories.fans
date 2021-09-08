<?php

namespace App\Http\Livewire\Plan;

use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Stripe\StripeClient;
use Illuminate\Support\Str;

class CreatePlanForm extends Component
{
    /**
     *
     * @var String $name
     */
    public $name;

    /**
     *
     * @var String $content
     */
    public $content;

    /**
     *
     * @var double $priceMonthly
     */
    public $priceMonthly;

    /**
     *
     * @var double $priceQuarterly
     */
    public $priceQuarterly;

    /**
     *
     * @var double $priceAnnually
     */
    public $priceAnnually;

    /**
     *
     * @var string $dayTrial
     */
    public $dayTrial;

    protected $rules = [
        'name' => 'required|string',
        'content' => 'string',
        'priceMonthly' => 'required|numeric|min:5|regex:/^\d+(\.\d{1,2})?$/',
        'priceQuarterly' => 'regex:/^\d+(\.\d{1,2})?$/',
        'priceAnnually' => 'regex:/^\d+(\.\d{1,2})?$/',
        'dayTrial' => 'integer',
    ];

    public function __construct()
    {
        $this->messages = [
            'name.required' => __('plan.required-name'),
            'name.string' => __('plan.name-must-be-string'),
            'priceMonthly.required' => __('plan.required-price-monthly'),
            'priceMonthly.min' => __('plan.required-price-min'),
            'priceMonthly.regex' => __('plan.price-monthly-must-be-double'),
            'priceQuarterly.regex' => __('plan.price-quarterly-must-be-double'),
            'priceAnnually.regex' => __('plan.price-annually-must-be-double'),
            'dayTrial.integer' => __('plan.day-trial-must-be-int'),
        ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function savePlan(): void
    {
        $this->validate([
            'name' => 'required|string',
            'priceMonthly' => 'required|numeric|min:5|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();

        $stripe = new StripeClient(config('services.stripe.secret'));

        $nameSlug = Str::slug($this->name);

        $date = new DateTime();

        $product = $stripe->products->create([
            "id" => "sub_monthly_{$user['id']}_{$nameSlug}_{$date->getTimestamp()}",
            "name" => "sub_monthly_{$user['id']}_{$nameSlug}_{$date->getTimestamp()}",
        ]);

        $priceMonthly = $stripe->prices->create([
            'unit_amount' => $this->priceMonthly * 100,
            'currency' => 'EUR',
            'recurring' => [
                'interval' => 'month',
            ],
            'product' => $product['id'],
        ]);

        if (!empty($this->priceQuarterly)) {
            $priceQuarterly = $stripe->prices->create([
                'unit_amount' => $this->priceQuarterly * 100,
                'currency' => 'EUR',
                'recurring' => [
                    'interval' => 'month',
                    'interval_count' => 3,
                ],
                'product' => $product['id'],
            ]);
        }

        if (!empty($this->priceAnnually)) {
            $priceAnnually = $stripe->prices->create([
                'unit_amount' => $this->priceAnnually * 100,
                'currency' => 'EUR',
                'recurring' => [
                    'interval' => 'year',
                ],
                'product' => $product['id'],
            ]);
        }

        $result = $user->plans()->create([
            'name' => $this->name,
            'content' => $this->content,
            'price_monthly' => $this->priceMonthly * 100,
            'price_monthly_id' => $priceMonthly['id'],
            'price_quarterly' => !empty($this->priceQuarterly) ? $this->priceQuarterly * 100 : null,
            'price_quarterly_id' => $priceQuarterly['id'] ?? null,
            'price_annually' => !empty($this->priceAnnually) ? $this->priceAnnually * 100 : null,
            'price_annually_id' => $priceAnnually['id'] ?? null,
            'day_trial' => !empty($this->dayTrial) ? $this->dayTrial : null,
        ]);

        $user->plans()->where('id', '!=', $result['id'])->each(function ($plan) {
            $plan->update([
                'deleted' => true
            ]);
        });

        $this->emitTo('plan.items', '$refresh');

        $this->name = null;
        $this->content = null;
        $this->priceMonthly = null;
        $this->priceQuarterly = null;
        $this->priceAnnually = null;
        $this->dayTrial = null;
    }

    public function render(): View
    {
        $offPriceQuarterly = null;
        $offPriceAnnually = null;

        if (!empty($this->priceMonthly) && !empty($this->priceQuarterly)) {
            $offPriceQuarterly =  100 - (($this->priceQuarterly / ($this->priceMonthly * 3)) * 100);
        }

        if (!empty($this->priceMonthly) && !empty($this->priceAnnually)) {
            $offPriceAnnually =  100 - (($this->priceAnnually / ($this->priceMonthly * 12)) * 100);
        }

        return view('livewire.plan.create-plan-form', compact('offPriceQuarterly', 'offPriceAnnually'));
    }
}
