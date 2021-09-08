<div class="space-y-6" wire:init="loadPosts">
    @if (count($posts) == 0)
        <div class="bg-white rounded p-6 text-center">
            <p>{{ __('feed.items-not-found') }}</p>
        </div>
    @else
        @foreach ($posts as $post)
            @livewire('post.item', ['post' => $post], key("content-{$post['id']}"))
        @endforeach
    @endif

    @if ($finished)
        <x-all-content-loaded />
    @endif

    @push('scripts')
        <script>
            window.onscroll = function(ev) {
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                    window.livewire.emit('loadMore')
                }
            };

            document.addEventListener('livewire:load', function() {
                window.addEventListener('newPostsLoaded', function() {
                    Prism.highlightAll();
                    window.livewire.emit('loadMore')
                })
            })
        </script>
    @endpush
</div>
