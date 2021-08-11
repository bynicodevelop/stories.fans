<x-jet-form-section submit="updateSocialNetwork">
    <x-slot name="title">
        {{ __('profile.update-social-title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('profile.update-social-description') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="instagram" value="{{ __('profile.social-network-instagram') }}" />
            <x-jet-input id="instagram" type="text" class="mt-1 block w-full" wire:model.defer="instagram"
                autocomplete="instagram" />
            <x-jet-input-error for="instagram" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="facebook" value="{{ __('profile.social-network-facebook') }}" />
            <x-jet-input id="facebook" type="text" class="mt-1 block w-full" wire:model.defer="facebook"
                autocomplete="facebook" />
            <x-jet-input-error for="facebook" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="youtube" value="{{ __('profile.social-network-youtube') }}" />
            <x-jet-input id="youtube" type="text" class="mt-1 block w-full" wire:model.defer="youtube"
                autocomplete="youtube" />
            <x-jet-input-error for="youtube" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="tiktok" value="{{ __('profile.social-network-tiktok') }}" />
            <x-jet-input id="tiktok" type="text" class="mt-1 block w-full" wire:model.defer="tiktok"
                autocomplete="tiktok" />
            <x-jet-input-error for="tiktok" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="snapchat" value="{{ __('profile.social-network-snapchat') }}" />
            <x-jet-input id="snapchat" type="text" class="mt-1 block w-full" wire:model.defer="snapchat"
                autocomplete="snapchat" />
            <x-jet-input-error for="snapchat" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="twitter" value="{{ __('profile.social-network-twitter') }}" />
            <x-jet-input id="twitter" type="text" class="mt-1 block w-full" wire:model.defer="twitter"
                autocomplete="twitter" />
            <x-jet-input-error for="twitter" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled">
            {{ __('profile.save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
