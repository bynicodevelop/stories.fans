<x-jet-form-section submit="saveCard">
    <x-slot name="title">
        {{ __('card.information') }}
    </x-slot>

    <x-slot name="description">
        {!! __('card.description') !!}

        <div class="mt-8 flex justify-center">
            <img src="/logo-stripe.png" alt="{{ __('card.stripe-alt') }}" class="w-80">
        </div>
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('card.name') }}" />
            <x-default-input id="name" type="text" class="mt-1 block w-full" wire:model="cardName" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="number" value="{{ __('card.number') }}" />
            <x-default-input id="number" type="text" class="mt-1 block w-full" wire:model="cardNumber" />
            <x-jet-input-error for="number" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="month" value="{{ __('card.expiration') }}" />
            <x-default-input id="monthExpiration" type="text" class="mt-1" wire:model="monthExpiration"
                placeholder="{{ date('m') }}" />
            <x-default-input id="yearExpiration" type="text" class="mt-1" wire:model="yearExpiration"
                placeholder="{{ date('Y') }}" />
            <x-jet-input-error for="monthExpiration" class="mt-2" />
            <x-jet-input-error for="yearExpiration" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="cvc" value="{{ __('card.cvc') }}" />
            <x-default-input id="cvc" type="text" class="mt-1" wire:model="cvc" placeholder="000" />
            <x-jet-input-error for="cvc" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <input
                class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                type="checkbox" name="confirm" id="confirm" class="form-checkbox" wire:model="confirm">
            <label for="confirm" class="ml-2">{{ __('card.confirm-radio') }}</label>
            <x-jet-input-error for="confirm" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('card.saved') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled">
            {{ __('card.create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
