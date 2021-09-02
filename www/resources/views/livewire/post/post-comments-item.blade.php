<div>
    @if ($nComments > $limitComments && !$isUnique)
        <div class="px-4 pt-2">
            <a class="text-sm text-gray-500 font-semibold tracking-wide"
                href="{{ route('post', ['postId' => $post['id']]) }}">
                @choice('post.number-comments', $nComments - $limitComments, ['number' => $nComments - $limitComments])
            </a>
        </div>
    @endif
    @if ($nComments)
        <div class="px-4 pb-2">
            @foreach ($comments as $comment)
                <div class="flex justify-between group">
                    <p class="text-sm inline pb-1">
                        <a
                            href="{{ route('profiles-slug', ['slug' => $comment['user']['slug']]) }}"><strong>{{ $comment['user']['name'] }}</strong></a>
                        {{ $comment['comment'] }}
                    </p>
                    @auth
                        @can('delete', $comment)
                            @livewire('post.post-comment-menu-contextual', ['comment' => $comment], key($comment['id']))
                        @endcan
                    @endauth
                </div>
            @endforeach
        </div>
    @endif
</div>
