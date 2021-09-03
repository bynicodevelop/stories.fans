<div x-cloak x-data="{ show: false }" x-show="$wire.show" x-transition x-bind:class="$wire.show ? '' : 'hidden'"
    class="fixed flex items-center py-3 px-3 z-50 bottom-16 md:bottom-5 inset-x-2 md:right-5 md:left-auto md:w-auto bg-pink-500 text-white text-sm md:text-base rounded transition">
    <i class="fas fa-bell mr-2"></i>
    <p>
        @lang($message)
    </p>
    <button type="button" class="flex-1 ml-2" wire:click="toggleShow()">
        <i class="fas fa-times"></i>
    </button>

    @push('scripts')
        <script>
            window.addEventListener('show-alert', function() {
                setTimeout(function() {
                    window.livewire.emit('toggleShow')
                }, 4000);
            })
        </script>
    @endpush
</div>
