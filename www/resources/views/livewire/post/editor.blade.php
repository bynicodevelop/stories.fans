<div class="space-y-6">
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
            <div>
                <textarea
                    class="tracking-wide py-2 px-4 mb-3 leading-relaxed appearance-none block w-full bg-white border border-gray-200 rounded focus:outline-none focus:border-gray-500"
                    wire:model="content" rows="4">
                </textarea>
            </div>
            <div class="flex justify-between">
                <span x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <input type="file" wire:model="media" accept="{{ config('livewire.accept') }}" class="hidden">

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
                            <span class="mr-1 text-base">Contenu premium</span>
                            <input type="checkbox" class="h-5 w-5 rounded" wire:model="isPremium">
                        </label>
                    @else
                        <a class="mr-1 text-base" href="{{ route('plans') }}">Monétiser mes posts</a>
                    @endif


                    @if ($isDisabled)
                        <x-default-submit-button disabled>
                            Post
                        </x-default-submit-button>
                    @else
                        <x-default-submit-button wire:loading.attr="disabled">
                            Post
                        </x-default-submit-button>
                    @endif
                </span>
            </div>
            {{-- @if ($media)
                <div>
                    @if (Str::contains($media->temporaryUrl(), ['mov', 'mp4']))
                        <video controls controlsList="nodownload">
                            <source src="{{ $media->temporaryUrl() }}">
                        </video>
                    @else
                        <img src="{{ $media->temporaryUrl() }}">
                    @endif
                </div>
            @endif --}}

            @error('media')
                <div>
                    <span class="italic text-red-500 text-sm">{{ $message }}</span>
                </div>
            @enderror
        </form>
    </div>

    @push('scripts')
        <script type="application/javascript">
            document.querySelector('#upload-media').addEventListener('click', function() {
                document.querySelector('input[type=file]').click()
            })
        </script>
    @endpush
</div>
