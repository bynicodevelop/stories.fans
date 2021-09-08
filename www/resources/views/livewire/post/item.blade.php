<div x-show="!$wire.deleted" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="bg-white rounded">
    <x-card-header-component :post="$post" :user="$post['user']" :wire:key="$post['id']" />

    <div class="{{ !empty($post['content']) ? 'mb-2' : '' }}">
        @foreach ($post['media'] as $media)
            @livewire('post.video-item', ['post' => $post, 'media' => $media])
        @endforeach
    </div>

    @if (!empty($post['content']))
        <div class="text-gray-800 dark:text-gray-100 leading-snug md:leading-normal px-4">
            @md($post['content'])
        </div>
    @endif

    <x-card-footer-component :post="$post" :isUnique="$isUnique" :wire:key="$post['id']" />
</div>
