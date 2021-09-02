<div>
    <span class="ml-0.5 hidden group-hover:inline">
        <button wire:click="$toggle('openModal')" type="button" id="menu-button" aria-expanded="true"
            aria-haspopup="true">
            <i class="fas fa-ellipsis-h text-gray-500 text-xs"></i>
        </button>
    </span>

    <x-dialog-component class="p-0 m-0" maxWidth="sm" wire:model="openModal">
        <x-slot name="slot">
            <div class="divide-y divide-gray-100 focus:outline-none">
                <div class="w-full" role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                    tabindex="-1">
                    <div class="py-1" role="none">
                        <button type="button"
                            class="w-full text-red-700 block px-4 py-2 text-sm focus:outline-none focus:font-semibold"
                            role="menuitem" tabindex="0"
                            wire:click="delete">{{ __('contextual-menu.delete-comment') }}</button>
                    </div>
                </div>
                <div class="w-full" role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                    tabindex="-1">
                    <div class="py-1" role="none">
                        <button type="button"
                            class="w-full text-gray-700 block px-4 py-2 text-sm focus:outline-none focus:font-semibold"
                            role="menuitem" tabindex="1" wire:click="$toggle('openModal')">
                            {{ __('contextual-menu.cancel') }}
                        </button>
                    </div>
                </div>
            </div>

        </x-slot>
    </x-dialog-component>
</div>
