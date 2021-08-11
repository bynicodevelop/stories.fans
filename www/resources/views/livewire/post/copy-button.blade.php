<button type="button" id="share-button" class="inline-block px-2 py-2 text-center"
    data-clipboard-text="{{ route('post', ['postId' => $post['id']]) }}">
    @if ($hasCopy)
        <i class="fas fa-share-alt text-pink-500 pr-1"></i>
    @else
        <i class="fas fa-share-alt pr-1"></i>
    @endif
</button>

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
