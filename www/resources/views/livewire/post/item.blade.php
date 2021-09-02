<div class="bg-white rounded">
    <x-card-header-component :post="$post" :user="$post['user']" :wire:key="$post['id']" />

    @if (!empty($post['content']))
        <div class="text-gray-800 dark:text-gray-100 leading-snug md:leading-normal px-4">
            @md($post['content'])
        </div>
    @endif


    <div>
        @foreach ($post['media'] as $media)
            @livewire('post.video-item', ['post' => $post, 'media' => $media])
        @endforeach
    </div>

    <x-card-footer-component :post="$post" :isUnique="$isUnique" :wire:key="$post['id']" />
</div>
