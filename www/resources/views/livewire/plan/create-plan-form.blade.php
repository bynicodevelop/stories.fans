<x-jet-form-section submit="savePlan">
    <x-slot name="title">
        {{ __('plan.information') }}
    </x-slot>

    <x-slot name="description">
        {!! __('plan.description') !!}
    </x-slot>

    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('plan.name') }} *" />
            <x-default-input id="name" type="text" class="mt-1 block w-full" wire:model="name" autocomplete="name" />
            <x-default-input-helper message="{{ __('plan.name-helper') }}" />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- Content -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="content" value="{{ __('plan.content') }}" />
            <textarea id="content"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                wire:model="content" rows="3">
            </textarea>
            <x-default-input-helper message="{{ __('plan.content-helper') }}" />
            <x-jet-input-error for="content" class="mt-2" />
        </div>

        <!-- Price -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="priceMonthly" value="{{ __('plan.price-monthly') }} *" />
            <x-default-input id="priceMonthly" type="text" class="mt-1 block w-full" wire:model="priceMonthly"
                autocomplete="priceMonthly" />
            <x-jet-input-error for="priceMonthly" class="mt-2" />
        </div>

        <!-- Price -->
        <div class="col-span-6 sm:col-span-4">
            @if ($offPriceQuarterly)
                <x-jet-label for="priceQuarterly"
                    value="{{ __('plan.price-quarterly') }} - {{ __('plan.off', ['number' => number_format($offPriceQuarterly, 2)]) }}" />
            @else
                <x-jet-label for="priceQuarterly" value="{{ __('plan.price-quarterly') }}" />
            @endif

            <x-default-input id="priceQuarterly" type="text" class="mt-1 block w-full" wire:model="priceQuarterly"
                autocomplete="priceQuarterly" />
            <x-jet-input-error for="priceQuarterly" class="mt-2" />
        </div>

        <!-- Price -->
        <div class="col-span-6 sm:col-span-4">
            @if ($offPriceAnnually)
                <x-jet-label for="priceAnnually"
                    value="{{ __('plan.price-annually') }} - {{ __('plan.off', ['number' => number_format($offPriceAnnually, 2)]) }}" />
            @else
                <x-jet-label for="priceAnnually" value="{{ __('plan.price-annually') }}" />
            @endif

            <x-default-input id="priceAnnually" type="text" class="mt-1 block w-full" wire:model="priceAnnually"
                autocomplete="priceAnnually" />
            <x-jet-input-error for="priceAnnually" class="mt-2" />
        </div>

        <!-- Price -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="dayTrial" value="{!! __('plan.day-trial') !!}" />
            <x-default-input id="dayTrial" type="text" class="mt-1 block w-full" wire:model="dayTrial"
                autocomplete="dayTrial" />
            <x-jet-input-error for="dayTrial" class="mt-2" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-jet-button wire:loading.attr="disabled">
            {{ __('plan.create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
