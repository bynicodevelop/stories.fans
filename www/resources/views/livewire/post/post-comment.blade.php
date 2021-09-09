<div>
    @livewire('post.post-comments-item', ['post' => $post, 'isUniquePost' => $isUniquePost],
    key("list-comments-{$post['id']}"))

    @auth
        <form x-data wire:submit.prevent="sendComment">
            <div class="px-2 border-t">
                <div class="relative">
                    <input type="text"
                        class="border-none ring-0 focus:ring-0 focus:border-none focus:outline-none bg-white h-14 w-full hover:text-pointer placeholder-opacity-50 placeholder-gray-500 pr-11"
                        name="comment" placeholder="@lang('post.post-comment')" wire:model="comment">

                    <button x-bind:disabled="$wire.isDisabled" type="submit"
                        x-bind:class="$wire.isDisabled == false ? '' : 'opacity-25'"
                        class="absolute top-4 right-3 border-l pl-4">
                        <i class="fa fa-comment text-pink-500 hover:cursor-pointer"></i>
                    </button>
                </div>
            </div>
        </form>
    @endauth
</div>
