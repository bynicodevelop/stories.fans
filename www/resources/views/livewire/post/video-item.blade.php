<div style="{{ $media['orientation'] == App\Models\Media::LANDSCAPE ? '' : 'padding-top: 100%;' }}">
    @if ($media['type'] == \App\Models\Media::IMAGE)
        <div class="{{ $media['orientation'] == App\Models\Media::LANDSCAPE ? '' : 'absolute w-full top-0' }}">
            <div class="relative w-full h-full">
                @if ($media['orientation'] == App\Models\Media::PORTRAIT)
                    <div x-data="{ show: true, close() { this.show = !this.show; } }">
                        <div x-show="!show"
                            class="jetstream-modal fixed inset-0 overflow-y-auto px-0 z-50 flex flex-wrap content-center">
                            <div x-show="!show" class="fixed inset-0 transform transition-all"
                                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                x-on:click="close()">
                                <div class="absolute inset-0 bg-black opacity-30"></div>
                            </div>

                            <div x-show="!show"
                                class="vertical-video-modal relative bg-white overflow-hidden shadow-2xl transform transition-all max-h-screen w-full sm:max-w-md sm:mx-auto"
                                x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                <div class="absolute top-2 right-2 p-3 z-10" x-on:click="close()">
                                    <i class="fas fa-times text-white"></i>
                                </div>

                                <img class="object-contain" src="{{ route('media', ['id' => $media['id']]) }}"
                                    alt="{{ "@{$post['user']['name']}" }}">
                            </div>
                        </div>

                        <img class="object-contain" src="{{ route('media', ['id' => $media['id']]) }}"
                            alt="{{ "@{$post['user']['name']}" }}" x-on:click="close()">
                    </div>
                @else
                    <img class="object-contain" src="{{ route('media', ['id' => $media['id']]) }}"
                        alt="{{ "@{$post['user']['name']}" }}">
                @endif


            </div>
        </div>
    @else
        <div class="{{ $media['orientation'] == App\Models\Media::LANDSCAPE ? '' : 'absolute w-full top-0' }}">
            <div class="relative w-full h-full">
                @if ($media['type'] == \App\Models\Media::VIDEO)
                    @can('seePost', $post)
                        @auth
                            @if ($media['orientation'] == App\Models\Media::LANDSCAPE)
                                <video id="video-{{ $post['id'] }}"
                                    class="video-js vjs-default-skin vjs-big-play-centered vjs-icon-hd vjs-fluid" controls
                                    preload="auto" poster="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}">
                                    <source src="{{ route('media', ['id' => $media['id']]) }}"
                                        type="application/x-mpegURL" />

                                    <p class="vjs-no-js">
                                        To view this video please enable JavaScript, and consider upgrading to a
                                        web browser that
                                        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5
                                            video</a>
                                    </p>
                                </video>
                            @else
                                <img src="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}"
                                    alt="{{ "@{$post['user']['name']}" }}">
                            @endif
                        @endauth

                        @guest
                            <img src="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}"
                                alt="{{ "@{$post['user']['name']}" }}">
                        @endguest
                    @else
                        <img src="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}"
                            alt="{{ "@{$post['user']['name']}" }}">
                    @endcan
                @else
                    <img src="{{ route('media', ['id' => $media['id']]) }}"
                        alt="{{ "@{$post['user']['name']}" }}">
                @endif
            </div>
        </div>

        @if ($media['type'] == \App\Models\Media::VIDEO)
            @can('seePost', $post)
                @auth
                    <div x-data="{ show: true, player: null, toggle() { play('video-{{ $post['id'] }}'); this.show = !this.show; }, close() { pause('video-{{ $post['id'] }}'); this.show = !this.show; } }"
                        x-init="player = instantiateVideoPlayer('video-{{ $post['id'] }}'); player.on('enterpictureinpicture', function() { show = !show; }); player.on('leavepictureinpicture', function() { show = !show; })">
                        <div x-show="show">
                            <a @click="toggle" class="absolute inset-0 w-full h-full flex items-center justify-center">
                                <svg class="h-20 w-20 text-pink-500" fill="currentColor" viewBox="0 0 84 84">
                                    <circle opacity="0.9" cx="42" cy="42" r="42" fill="white"></circle>
                                    <path
                                        d="M55.5039 40.3359L37.1094 28.0729C35.7803 27.1869 34 28.1396 34 29.737V54.263C34 55.8604 35.7803 56.8131 37.1094 55.9271L55.5038 43.6641C56.6913 42.8725 56.6913 41.1275 55.5039 40.3359Z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                        @if ($media['orientation'] == App\Models\Media::PORTRAIT)
                            <div x-show="!show"
                                class="jetstream-modal fixed inset-0 overflow-y-auto px-0 z-50 flex flex-wrap content-center">
                                <div x-show="!show" class="fixed inset-0 transform transition-all"
                                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    x-on:click="close()">
                                    <div class="absolute inset-0 bg-black opacity-30"></div>
                                </div>


                                <div x-show="!show"
                                    class="vertical-video-modal relative bg-white overflow-hidden shadow-2xl transform transition-all max-h-screen w-full sm:max-w-md sm:mx-auto"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                    <div class="absolute top-2 right-2 p-3 z-10" x-on:click="close()">
                                        <i class="fas fa-times text-white"></i>
                                    </div>

                                    <video id="video-{{ $post['id'] }}" class="video-js vjs-default-skin vjs-9-16 vjs-nofull"
                                        controls preload="auto"
                                        poster="{{ route('media', ['id' => $media['id'], 'preview' => true]) }}">
                                        <source src="{{ route('media', ['id' => $media['id']]) }}"
                                            type="application/x-mpegURL" />

                                        <p class="vjs-no-js">
                                            To view this video please enable JavaScript, and consider upgrading to a
                                            web browser that
                                            <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5
                                                video</a>
                                        </p>
                                    </video>
                                </div>
                            </div>
                        @endif
                    </div>

                @endauth
            @else
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
        @endif
    @endif

    @if ($media['type'] == \App\Models\Media::VIDEO)
        @auth
            @can('seePost', $post)
                <script>
                    instantiateVideoPlayer('video-{{ $post['id'] }}')
                </script>
            @endcan
        @endauth
    @endif
</div>
