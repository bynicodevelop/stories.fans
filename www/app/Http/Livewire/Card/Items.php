<?php

namespace App\Http\Livewire\Card;

use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\PaymentMethod;
use Livewire\Component;

class Items extends Component
{
    /**
     *
     * @var boolean $confirmingCardDeletion
     */
    public $confirmingCardDeletion = false;

    /**
     *
     * @var String $cardItemId
     */
    public $cardItemId;

    public $listeners = ['$refresh'];

    public function confirmDeletion($card): void
    {
        $this->confirmingCardDeletion = true;
        $this->cardItemId = $card;
    }

    public function delete(): void
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var PaymentMethod $paymentMethod
         */
        $paymentMethod = $user->findPaymentMethod($this->cardItemId);

        $paymentMethod->delete();

        $paymentMethods = $user->paymentMethods();

        if (count($paymentMethods) != 0) {
            $defaultPaymentMethodId = $paymentMethods->first()->asStripePaymentMethod()['id'];

            /**
             * @var PaymentMethod $paymentMethod
             */
            $paymentMethod = $user->findPaymentMethod($defaultPaymentMethodId);

            $user->updateDefaultPaymentMethod($paymentMethod->asStripePaymentMethod());
        }

        $this->dispatchBrowserEvent('banner-message', [
            'style' => 'success',
            'message' => __('card.deleted')
        ]);

        $this->confirmingCardDeletion = false;
    }

    public function setDefault($id)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var PaymentMethod $paymentMethod
         */
        $paymentMethod = $user->findPaymentMethod($id);

        $user->updateDefaultPaymentMethod($paymentMethod->asStripePaymentMethod());
    }

    public function render()
    {
        $defaultPaymentMethodId = null;

        /**
         * @var User $user
         */
        $user = Auth::user();

        $defaultPaymentMethod = $user->defaultPaymentMethod();

        if ($defaultPaymentMethod != null) {
            $defaultPaymentMethodId = $defaultPaymentMethod->asStripePaymentMethod()['id'];
        }

        $paymentMethods = $user->paymentMethods();

        $cards = $paymentMethods->map(function ($item) {
            return $item->asStripePaymentMethod();
        });

        return view('livewire.card.items', compact('cards', 'defaultPaymentMethodId'));
    }
}
