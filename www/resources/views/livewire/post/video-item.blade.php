<div>
    @if ($media['type'] == \App\Models\Media::IMAGE)
        <img class="object-contain" src="{{ route('media', ['id' => $media['id']]) }}"
            alt="@{{ $post['user']['name'] }}">
    @else

        <div x-data="{ play: false }" x-init="$watch('play', (value) => {
    if (value) {
        $refs.video.play()
    } else {
        $refs.video.pause()
    }
})">
            <div class="flex-shrink-0 relative">
                @protectedcontent ($post)
                <img src="{{ route('media', ['id' => $media['id']]) }}" alt="@{{ $post['user']['name'] }}">
                @elseprotectedcontent
                <video x-ref="video" @click="play = !play" loop playsinline preload="none"
                    poster="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}">
                    <source src="{{ route('media', ['id' => $media['id']]) }}">
                </video>
                @endprotectedcontent

                <div @click="play = true" x-show="!play" x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-90"
                    class="absolute inset-0 w-full h-full flex items-center justify-center">
                    <svg class="h-20 w-20 text-pink-500" fill="currentColor" viewBox="0 0 84 84">
                        <circle opacity="0.9" cx="42" cy="42" r="42" fill="white"></circle>
                        <path
                            d="M55.5039 40.3359L37.1094 28.0729C35.7803 27.1869 34 28.1396 34 29.737V54.263C34 55.8604 35.7803 56.8131 37.1094 55.9271L55.5038 43.6641C56.6913 42.8725 56.6913 41.1275 55.5039 40.3359Z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    @endif
</div>