<div class="space-y-6 {{ $attributes }}">
    <div class="bg-white rounded px-4 py-4">
        <div class="flex mb-4">
            <a href="{{ route('profiles-slug', ['slug' => $user['slug']]) }}">
                <img class="w-10 h-10 rounded-full" src="{{ $user['profile_photo_url'] }}" />
            </a>
            <div class="ml-2 mt-0.5">
                <a href="{{ route('profiles-slug', ['slug' => $user['slug']]) }}">
                    <span class="block font-medium text-base leading-snug text-black dark:text-gray-100">
                        {{ $user['name'] }}
                    </span>
                </a>
            </div>
        </div>

        <form wire:submit.prevent="post">
            <div wire:ignore
                class="tracking-wide px-2 mb-3 leading-relaxed appearance-none block w-full bg-white border border-gray-200 rounded focus:outline-none focus:border-gray-500">
                <textarea id="editor" wire:model="content" rows="4" class="border-none resize-none h-16"></textarea>
            </div>
            <div class="flex justify-between" x-cloak x-data="{ isUploading: false, progress: 0 }"
                x-on:livewire-upload-start="isUploading = true; $wire.uploading(true)"
                x-on:livewire-upload-finish="isUploading = false; $wire.uploading(false)"
                x-on:livewire-upload-error="isUploading = false; $wire.uploading(false)"
                x-on:livewire-upload-progress="progress = $event.detail.progress">
                <span>
                    <input type="file" wire:model="media" accept="{{ config('livewire.accept') }}"
                        class="hidden">

                    <button id="upload-media" type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-base text-gray-500 hover:text-pink-400 disabled:opacity-25 transition">
                        <i class="fas fa-photo-video"></i>
                    </button>

                    <div class="relative pt-1" x-show="isUploading">
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-pink-200">

                            <div x-bind:style="`width:${progress}%`"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-pink-500">
                            </div>
                        </div>
                    </div>
                </span>

                <span>
                    @if ($havePlan)
                        <label class="inline-flex items-center mr-3">
                            <span class="mr-1 text-base">@lang('post.premium-content')</span>
                            <input type="checkbox" class="h-5 w-5 rounded" wire:model="isPremium">
                        </label>
                    @else
                        <a class="mr-1 text-base" href="{{ route('plans') }}">@lang('post.monetize-posts')</a>
                    @endif

                    <x-default-submit-button x-bind:disabled="$wire.isUploading || $wire.isDisabled">
                        @lang('post.button')
                    </x-default-submit-button>
                </span>
            </div>
            @error('media')
                <div>
                    <span class="italic text-red-500 text-sm">{{ $message }}</span>
                </div>
            @enderror
        </form>
    </div>

    @if ($mediaTmpUrl)
        <div class="relative bg-white rounded px-4 py-4 flex items-center">
            @if (Str::contains(strtolower($mediaTmpUrl), ['.mov', '.mp4', '.ogg']))
                <div class="w-1/12 h-10 inline-block mr-2 flex items-center justify-center">
                    <i class="far fa-play-circle text-gray-500 text-medium"></i>
                </div>
            @else
                <div class="w-1/12 h-10 bg-contain bg-no-repeat bg-center inline-block mr-2"
                    style="background-image: url('{{ $mediaTmpUrl }}')"></div>
            @endif
            <div class="inline-block w-10/12">
                <p class="min-h-5 h-5 w-full text-sm text-gray-500 overflow-hidden truncate">
                    {{ $content }}</p>
                <p class="text-xs text-gray-300 italic">@lang('post.content-ready-to-send')</p>
            </div>
            <a class="absolute top-2 right-3" title="@lang('post.clear-content')" href="#" wire:click.prevent="clear">
                <i class="fas fa-times text-gray-500"></i>
            </a>
        </div>
    @endif

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

        <style>
            .CodeMirror,
            .CodeMirror-scroll {
                min-height: 3.4rem;
                border: none;
                font-size: 16px;
            }

            .CodeMirror {
                padding: 10px 0;
            }

            .CodeMirror-vscrollbar {
                display: none !important;
            }

        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

        <script type="application/javascript">
            document.querySelector('#upload-media').addEventListener('click', function() {
                document.querySelector('input[type=file]').click()
            })

            document.addEventListener('livewire:load', function() {
                var simplemde = new SimpleMDE({
                    element: document.getElementById("editor"),
                    toolbar: false,
                    status: false,
                    autofocus: false,
                    spellChecker: false,
                    placeholder: '# Laissez libre cours à votre imagination...',
                    forceSync: true,
                });

                simplemde.codemirror.on("change", function(e) {
                    @this.content = simplemde.value();
                });

                window.addEventListener('clear', function() {
                    simplemde.value('');
                })
            })
        </script>
    @endpush
</div>
