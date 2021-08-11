<div class="bg-white rounded px-4 py-2">
    <x-card-header-component :post="$post" :user="$post['user']" :wire:key="$post['id']" />
    <div class="text-gray-800 dark:text-gray-100 leading-snug md:leading-normal">
        {{ $post['content'] }}
    </div>
    <x-card-footer-component :post="$post" :wire:key="$post['id']" />
</div>
