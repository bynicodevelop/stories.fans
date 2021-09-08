<div>
    <span class="ml-0.5">
        <button wire:click="$toggle('openModal')" type="button" id="menu-button" aria-expanded="true"
            aria-haspopup="true">
            <i class="fas fa-ellipsis-h text-gray-500 text-xs"></i>
        </button>
    </span>

    <x-dialog-component class="p-0 m-0" maxWidth="sm" wire:model="openModal">
        <x-slot name="slot">
            <div class="divide-y divide-gray-100 focus:outline-none">
                @foreach ($menus as $menu)
                    @if ($menu == 'delete')
                        @can('delete', $model)
                            @livewire("commons.contextual-menu.menus.{$menu}", ['model' => $model],
                            key("{$menu}-{$model['id']}"))
                        @endcan
                    @else
                        @livewire("commons.contextual-menu.menus.{$menu}", ['model' => $model],
                        key("{$menu}-{$model['id']}"))
                    @endif
                @endforeach
            </div>
        </x-slot>
    </x-dialog-component>
</div>
