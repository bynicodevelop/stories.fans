<div class="space-y-6">
    @if (count($posts) == 0)
        <div class="bg-white rounded p-6 text-center">
            <p>{!! __('post.items-not-found', ['route' => route('home')]) !!}</p>
        </div>
    @else
        @foreach ($posts as $post)
            @livewire('post.item', ['post' => $post], key(time() . $post['id']))
        @endforeach
    @endif

    @if ($finished && count($posts) != 0)
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
        </script>
    @endpush
</div>
