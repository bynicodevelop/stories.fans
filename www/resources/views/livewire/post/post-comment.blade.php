<div>
    @livewire('post.post-comments-item', ['post' => $post, 'isUnique' => $isUnique], key($post->id))

    <form wire:submit.prevent="sendComment">
        <div class="px-2 border-t">
            <div class="relative">
                <input type="text"
                    class="border-none ring-0 focus:ring-0 focus:border-none focus:outline-none bg-white h-14 w-full hover:text-pointer placeholder-opacity-50 placeholder-gray-500"
                    name="comment" placeholder="{{ __('post.post-comment') }}" wire:model="comment">
                @if ($isDisabled)
                    <button type="submit" class="absolute top-4 right-3 border-l pl-4 opacity-25">
                        <i class="fa fa-comment text-pink-500 hover:cursor-pointer"></i>
                    </button>
                @else
                    <button type="submit" class="absolute top-4 right-3 border-l pl-4 ">
                        <i class="fa fa-comment text-pink-500 hover:cursor-pointer"></i>
                    </button>
                @endif
            </div>
        </div>
    </form>

</div>
