<div class="w-full" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
    <div class="py-1" role="none">
        <button id="btn-{{ $model['id'] }}" type="button"
            class="w-full text-gray-700 block px-4 py-2 text-sm focus:outline-none focus:font-semibold" role="menuitem"
            data-clipboard-text="{{ route('post', ['postId' => $model['id']]) }}">
            {{ __('contextual-menu.share') }}
        </button>
    </div>

    <script type="application/javascript">
        document.addEventListener("DOMContentLoader", function() {
            let clipboard = new ClipboardJS('#btn-{{ $model['id'] }}');

            clipboard.on('success', function(e) {
                window.livewire.emit('copied')
            });
        })
    </script>
</div>
