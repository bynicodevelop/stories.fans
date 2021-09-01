<div class="space-y-6">
    @if (count($posts) == 0)
        <div class="bg-white rounded p-6 text-center">
            <p>{{ __('feed.items-not-found') }}</p>
        </div>
    @else
        @foreach ($posts as $post)
            @livewire('post.item', ['post' => $post], key(time() . $post['id']))
        @endforeach
    @endif

    @if ($finished)
        <div class="bg-white rounded p-6 text-center">
            <p>Il n'y a plus de contenu</p>
        </div>
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
                })
            })
        </script>
    @endpush
</div>
