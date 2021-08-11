<div>
    @if (Auth::user()->hasPaymentMethod())
        <x-default-submit-button type="button" wire:click.prevent="toggleFollow" wire:loading.attr="disabled">
            {{ __($label) }}
        </x-default-submit-button>
    @else
        <x-default-submit-button type="button" wire:click.prevent="addPaymentMethod" wire:loading.attr="disabled">
            {{ __($label) }}
        </x-default-submit-button>
    @endif

    <x-confirm-modal maxWidth="md" wire:model="showAddPaymentMethod">
        <x-slot name="title">
            <x-header-profile :user="$user" :showBio="false" />
        </x-slot>

        <x-slot name="content">
            <p class="text-center pb-2">{!! __('follow.title', ['name' => $user['name']]) !!}</p>

            <p class="text-center">{!! __('follow.explain') !!}</p>

            <ul class="list-none mt-5 mx-5">
                <li class="pb-3"><i class="fas fa-check mr-2 text-green-500"></i> {{ __('follow.exclusive-content') }}
                </li>
                <li class="pb-3"><i class="fas fa-check mr-2 text-green-500"></i> {{ __('follow.easy-subscription') }}
                </li>
                <li class="pb-3"><i class="fas fa-check mr-2 text-green-500"></i>
                    {{ __('follow.cancel-subscription') }}</li>
                <li class="pb-3"><i class="fas fa-check mr-2 text-green-500"></i>
                    {{ __('follow.no-fees-card-registration') }}</li>
            </ul>
        </x-slot>

        <x-slot name="footer">
            <x-default-link-button href="{{ route('card') }}">{{ __('follow.add') }}
            </x-default-link-button>
        </x-slot>
    </x-confirm-modal>
</div>
