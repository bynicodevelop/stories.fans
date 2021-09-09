@props(['user', 'post'])

<div class="flex px-4 py-4">
    <a href="{{ route('profiles-slug', ['slug' => $user['slug']]) }}">
        <img class="w-12 h-12 rounded-full" src="{{ $user['profile_photo_url'] }}" />
    </a>
    <div class="ml-2 mt-0.5 flex-auto">
        <a href="{{ route('profiles-slug', ['slug' => $user['slug']]) }}">
            <span class="block font-medium text-base leading-snug text-black dark:text-gray-100">
                {{ $user['name'] }}
            </span>
        </a>
        <span class="block text-sm text-gray-500 dark:text-gray-400 font-light leading-snug">
            {{ $post['created_at']->diffForHumans() }}
        </span>
    </div>

    @livewire('commons.contextual-menu.modal', [
    'model' => $post,
    'menus' => [ "share", "delete", "cancel" ],
    'isUniquePost' => $isUniquePost,
    ], key("post-menu-item-{$post['id']}"))
</div>
