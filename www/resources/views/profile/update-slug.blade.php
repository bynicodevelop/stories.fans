<x-jet-form-section submit="updateSlug">
    <x-slot name="title">
        {{ __('profile.update-slug') }}
    </x-slot>

    <x-slot name="description">
        {{ __('profile.update-slug-description') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="slug" value="{{ __('profile.slug') }}" />
            <x-jet-input id="slug" type="text" class="mt-1 block w-full" wire:model="slug" autocomplete="slug" />
            <x-jet-input-error for="slug" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="bio" value="{{ __('profile.bio') }}" />
            <x-jet-input id="bio" type="text" class="mt-1 block w-full" wire:model="bio" autocomplete="bio" />
            <x-jet-input-error for="bio" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        @error('slug')
            <x-jet-button disabled>
                {{ __('profile.save') }}
            </x-jet-button>
        @else
            <x-jet-button wire:loading.attr="disabled">
                {{ __('profile.save') }}
            </x-jet-button>
        @enderror

    </x-slot>
</x-jet-form-section>
