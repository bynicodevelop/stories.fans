@props(['post'])

<div class="flex py-5 justify-end">
    @livewire('post.copy-button', ['post' => $post], key($post['id']))

    @auth
        @livewire('post.like-button', ['post' => $post], key($post['id']))
    @endauth

    @premium ($post)
    <x-default-link-button class="ml-2" href="{{ route('plans-show', ['userId' => $post['user_id']]) }}">
        {{ __('plan.premium-button') }}
    </x-default-link-button>
    @endpremium
</div>
