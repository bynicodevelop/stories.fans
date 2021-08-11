<div class="space-y-6">
    @if (count($plans) == 0)
        <div class="bg-white rounded p-6 text-center">
            <p>{{ __('plan.empty') }}</p>
        </div>
    @else
        @foreach ($plans as $plan)
            <div class="bg-white p-2 rounded {{ $plan['deleted'] ? 'opacity-50' : '' }}">
                <div class="flex p-1">
                    <div class="flex-1 ml-3 mt-1">
                        <span class="block font-medium text-base leading-snug text-black dark:text-gray-100">
                            {{ Str::ucfirst($plan['name']) }}
                        </span>
                        <span class="block text-sm text-gray-500 dark:text-gray-400 font-light leading-snug">
                            {{ __('plan.price-per-month', ['number' => number_format($plan['price_monthly'] / 100, 2)]) }}
                            @if (!empty($plan['price_quarterly']))
                                -
                                {{ __('plan.price-per-quart', ['number' => number_format($plan['price_quarterly'] / 100, 2)]) }}
                            @endif

                            @if (!empty($plan['price_annually']))
                                -
                                {{ __('plan.price-per-year', ['number' => number_format($plan['price_annually'] / 100, 2)]) }}
                            @endif

                            @if (!empty($plan['day_trial']))
                                -
                                {{ __('plan.day-trial-label', ['number' => $plan['day_trial']]) }}
                            @endif
                        </span>
                    </div>

                    @if (!$plan['deleted'])
                        <div class="pt-3">
                            <x-jet-danger-button type="button" wire:click="confirmDeletion({{ $plan['id'] }})">
                                {{ __('plan.delete') }}
                            </x-jet-danger-button>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
    @endif

    <x-jet-confirmation-modal wire:model="confirmingPlanDeletion">
        <x-slot name="title">
            {{ __('plan.confirm-delete') }}
        </x-slot>

        <x-slot name="content">
            {{ __('plan.explain') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingPlanDeletion')" wire:loading.attr="disabled">
                {{ __('plan.cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('plan.confirm') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
