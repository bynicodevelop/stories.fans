<div class="space-y-6" wire:init="loadPosts">
    @if (count($posts) == 0)
        <div class="bg-white rounded p-6 text-center">
            @if ($user['id'] == Auth::id())
                <p>{!! __('post.items-not-found-auth', ['route' => route('home')]) !!}</p>
            @else
                <p>{!! __('post.items-not-found') !!}</p>
            @endif
        </div>
    @else
        @foreach ($posts as $post)
            @livewire('post.item', ['post' => $post], key("content-{$post['id']}"))
        @endforeach
    @endif

    @if ($finished && count($posts) != 0)
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
                })
            })
        </script>
    @endpush
</div>
