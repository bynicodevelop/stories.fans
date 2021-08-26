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
                <div class="flex justify-between">
                    <span class="text-sm">
                        <a
                            href="{{ route('profiles-slug', ['slug' => $comment['user']['slug']]) }}"><strong>{{ $comment['user']['name'] }}</strong></a>
                        {{ $comment['comment'] }}
                    </span>
                    {{-- <span>
                        <i class="far fa-heart text-xs"></i>
                    </span> --}}
                </div>
            @endforeach
        </div>
    @endif
</div>
