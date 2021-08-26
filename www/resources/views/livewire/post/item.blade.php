<div class="bg-white rounded h-screen">
    <x-card-header-component :post="$post" :user="$post['user']" :wire:key="$post['id']" />

    @if (!empty($post['content']))
        <div class="text-gray-800 dark:text-gray-100 leading-snug md:leading-normal pb-3 px-4">
            {{ $post['content'] }}
        </div>
    @endif


    <div>
        @foreach ($post['media'] as $media)
            @livewire('post.video-item', ['post' => $post, 'media' => $media])
        @endforeach
    </div>
    <x-card-footer-component :post="$post" :wire:key="$post['id']" />
</div>
