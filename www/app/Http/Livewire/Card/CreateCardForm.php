<?php

namespace App\Http\Livewire\Card;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Stripe\StripeClient;

class CreateCardForm extends Component
{
    public $cardName;

    public $cardNumber;

    public $monthExpiration;

    public $yearExpiration;

    public $cvc;

    public $confirm;

    protected $rules;

    public function __construct()
    {
        $month = date('m');
        $year = date('Y');

        $this->rules = [
            'cardName' => 'required|string',
            'cardNumber' => 'required|min:16',
            'monthExpiration' => "date_format:m|required|min:2|after_or_equal:{$month}",
            'yearExpiration' => "date_format:Y|required|min:4|after_or_equal:{$year}",
            'cvc' => 'required|min:3',
            'confirm' => 'required|accepted',
        ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function saveCard(): void
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->card()->get()->each(function ($card) {
            $card['default'] = false;
            $card->save();
        });

        $stripe = new StripeClient(config('services.stripe.secret'));

        $user->createAsStripeCustomer([
            'email' => $user['email'],
            'name' => $user['name'],
        ]);

        $paymentMethods = $stripe->paymentMethods->create([
            'type' => 'card',
            'billing_details' => [
                'email' => $user['email'],
                'name' => $this->cardName,
            ],
            'card' => [
                'number' => $this->cardNumber,
                'exp_month' => $this->monthExpiration,
                'exp_year' => $this->yearExpiration,
                'cvc' => $this->cvc,
            ],
        ]);

        $stripe->paymentMethods->attach(
            $paymentMethods['id'],
            [
                'customer' => $user['stripe_id']
            ]
        );

        /**
         * @var PaymentMethod $paymentMethod
         */
        $paymentMethod = $user->findPaymentMethod($paymentMethods['id']);

        $user->updateDefaultPaymentMethod($paymentMethod->asStripePaymentMethod());

        $this->emit('saved');

        $this->emitTo('card.items', '$refresh');

        $this->cardName = null;
        $this->cardNumber = null;
        $this->monthExpiration = null;
        $this->yearExpiration = null;
        $this->cvc = null;
        $this->confirm = false;
    }

    public function render()
    {
        return view('livewire.card.create-card-form');
    }
}
