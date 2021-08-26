<div class="relative inline-block text-left dropdown z-50">
    <button type="button" id="menu-button" aria-expanded="true" aria-haspopup="true">
        <i class="fas fa-ellipsis-h text-gray-500"></i>
    </button>

    <div
        class="opacity-0 invisible dropdown-menu transition-all duration-300 transform origin-top-right -translate-y-2 scale-95">
        <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
                <button type="button" data-clipboard-text="{{ route('post', ['postId' => $post['id']]) }}"
                    class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                    id="menu-item-6">{{ __('contextual-menu.share') }}</button>
            </div>

            @auth
                @if ($post['user_id'] == Auth::id())
                    <div class="py-1" role="none">
                        <a href="#" wire:click.prevent="$toggle('confirmingPostDeletion')"
                            class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                            id="menu-item-6">{{ __('contextual-menu.delete') }}</a>
                    </div>
                @endif

            @endauth
        </div>
    </div>

    <x-jet-confirmation-modal wire:model="confirmingPostDeletion">
        <x-slot name="title">
            {{ __('post.confirm-delete') }}
        </x-slot>

        <x-slot name="content">
            {!! __('post.explain') !!}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingPostDeletion')" wire:loading.attr="disabled">
                {{ __('post.cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('post.confirm') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>

@push('scripts')
    <script type="application/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            let clipboard = new ClipboardJS('button[type=button]');

            clipboard.on('success', function(e) {
                window.livewire.emit('copied')
            });
        })
    </script>
@endpush
