@props(['post'])

<div>
    <div class="flex justify-end px-2 py-2">
        @livewire('post.copy-button', ['post' => $post, 'isUniquePost' => $isUniquePost],
        key("share-btn-{$post['id']}"))

        @auth
            @livewire('post.like-button', ['post' => $post], key("like-btn-{$post['id']}"))
        @endauth

        @cannot('seePremiumButton', $post)
            @auth
                <x-default-link-button class="ml-2" href="{{ route('plans-show', ['userId' => $post['user_id']]) }}">
                    {{ __('plan.premium-button') }}
                </x-default-link-button>
            @endauth

            @guest
                <x-default-link-button class="ml-2"
                    href="{{ route('register', ['slug' => $post['user']['slug']]) }}">
                    {{ __('plan.premium-button') }}
                </x-default-link-button>
            @endguest

        @endcannot
    </div>

    @livewire('post.post-comment', ['post' => $post, 'isUniquePost' => $isUniquePost],
    key("comment-form-{$post['id']}"))
</div>
