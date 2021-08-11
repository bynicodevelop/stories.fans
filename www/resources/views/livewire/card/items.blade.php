<div class="space-y-6">
    <x-jet-action-message class="mr-3" on="deleted">
        {{ __('card.deleted') }}
    </x-jet-action-message>

    @if (count($cards) == 0)
        <div class="bg-white rounded p-6 text-center">
            <p>{{ __('card.empty') }}</p>
        </div>
    @else
        @foreach ($cards as $card)
            <div class="bg-white p-2 rounded">
                <div class="flex p-1">
                    <div class="pt-3">
                        <a wire:click="setDefault('{{ $card['id'] }}')">
                            @if ($card['id'] == $defaultPaymentMethodId)
                                <i class="fas fa-star text-yellow-400"></i>
                            @else
                                <i class="far fa-star text-yellow-400"></i>
                            @endif
                        </a>
                    </div>

                    <div class="flex-1 ml-3 mt-1">
                        <span class="block font-medium text-base leading-snug text-black dark:text-gray-100">
                            {{ Str::ucfirst($card['card']['brand']) }}
                            @if ($card['id'] == $defaultPaymentMethodId)
                                <i class="text-xs text-gray-500 dark:text-gray-400">({{ __('card.default') }})</i>
                            @endif
                        </span>
                        <span class="block text-sm text-gray-500 dark:text-gray-400 font-light leading-snug">
                            XXXX - XXXX - XXXX - {{ $card['card']['last4'] }} --
                            {{ $card['card']['exp_month'] }} / {{ $card['card']['exp_year'] }}
                        </span>
                    </div>

                    <div class="pt-3">
                        <x-jet-danger-button type="button" wire:click="confirmDeletion('{{ $card['id'] }}')">
                            {{ __('plan.delete') }}
                        </x-jet-danger-button>
                    </div>

                </div>
            </div>
        @endforeach
    @endif

    <x-jet-confirmation-modal wire:model="confirmingCardDeletion">
        <x-slot name="title">
            {{ __('card.confirm-delete') }}
        </x-slot>

        <x-slot name="content">
            {!! __('card.explain') !!}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingCardDeletion')" wire:loading.attr="disabled">
                {{ __('card.cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('card.confirm') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
