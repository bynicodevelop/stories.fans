{{-- <div class="h-96 overflow-hidden flex items-center"> --}}
<div class="mt-3">
    @if ($media['type'] == \App\Models\Media::IMAGE)
        <img class="object-contain" src="{{ route('media', ['id' => $media['id']]) }}"
            alt="@{{ $post['user']['name'] }}">
    @else

        <div class="flex-shrink-0 relative">
            @if ($media['type'] == \App\Models\Media::VIDEO)
                @can('seePost', $post)
                    @auth
                        <video id="video-{{ $post['id'] }}"
                            class="video-js vjs-default-skin vjs-big-play-centered vjs-fluid vjs-icon-hd" controls preload="auto"
                            poster="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}">
                            <source src="{{ route('media', ['id' => $media['id']]) }}" type="application/x-mpegURL" />

                            <p class="vjs-no-js">
                                To view this video please enable JavaScript, and consider upgrading to a
                                web browser that
                                <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                            </p>
                        </video>

                        <div x-data="{show: true, toggle() { play('video-{{ $post['id'] }}'); this.show = !this.show; }}"
                            x-show="show">
                            <a @click="toggle" class="absolute inset-0 w-full h-full flex items-center justify-center">
                                <svg class="h-20 w-20 text-pink-500" fill="currentColor" viewBox="0 0 84 84">
                                    <circle opacity="0.9" cx="42" cy="42" r="42" fill="white"></circle>
                                    <path
                                        d="M55.5039 40.3359L37.1094 28.0729C35.7803 27.1869 34 28.1396 34 29.737V54.263C34 55.8604 35.7803 56.8131 37.1094 55.9271L55.5038 43.6641C56.6913 42.8725 56.6913 41.1275 55.5039 40.3359Z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    @endauth

                    @guest
                        <img src="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}"
                            alt="@{{ $post['user']['name'] }}">
                    @endguest
                @else
                    <img src="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}"
                        alt="@{{ $post['user']['name'] }}">

                    <a href="{{ route('plans-show', ['userId' => $post['user']['id']]) }}"
                        class="absolute inset-0 w-full h-full flex items-center justify-center">
                        <svg class="h-20 w-20 text-pink-500" fill="currentColor" viewBox="0 0 84 84">
                            <circle opacity="0.9" cx="42" cy="42" r="42" fill="white"></circle>
                            <path
                                d="M55.5039 40.3359L37.1094 28.0729C35.7803 27.1869 34 28.1396 34 29.737V54.263C34 55.8604 35.7803 56.8131 37.1094 55.9271L55.5038 43.6641C56.6913 42.8725 56.6913 41.1275 55.5039 40.3359Z">
                            </path>
                        </svg>
                    </a>
                @endcan

            @else
                <img src="{{ route('media', ['id' => $media['id']]) }}" alt="@{{ $post['user']['name'] }}">
            @endif

            @guest
                <a href="{{ route('register', ['slug' => $post['user']['slug']]) }}"
                    class="absolute inset-0 w-full h-full flex items-center justify-center">
                    <svg class="h-20 w-20 text-pink-500" fill="currentColor" viewBox="0 0 84 84">
                        <circle opacity="0.9" cx="42" cy="42" r="42" fill="white"></circle>
                        <path
                            d="M55.5039 40.3359L37.1094 28.0729C35.7803 27.1869 34 28.1396 34 29.737V54.263C34 55.8604 35.7803 56.8131 37.1094 55.9271L55.5038 43.6641C56.6913 42.8725 56.6913 41.1275 55.5039 40.3359Z">
                        </path>
                    </svg>
                </a>
            @endguest
        </div>
    @endif

    @if ($media['type'] == \App\Models\Media::VIDEO)
        @auth
            @can('seePost', $post)
                <script>
                    instantiateVideoPlayer('video-{{ $post['id'] }}');
                </script>
            @endcan
        @endauth
    @endif

</div>
