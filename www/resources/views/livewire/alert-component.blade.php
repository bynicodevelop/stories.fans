<div x-cloak x-data="{ show: false }" x-show="$wire.show" x-transition x-bind:class="$wire.show ? '' : 'hidden'"
    class="fixed z-40 bottom-5 right-5 w-24 md:w-auto bg-pink-500 text-white py-2 px-3 rounded transition">
    <i class="fas fa-bell mx-3"></i>
    <p class="inline-block pb-1">
        @lang($message)
    </p>
    <button type="button" class="inline-block mx-2 pt-1" wire:click="toggleShow()">
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
