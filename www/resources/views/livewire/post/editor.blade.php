<div class="space-y-6">
    <div class="bg-white p-2 rounded">
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
            <div class="flex justify-end">
                <label class="inline-flex items-center mr-3">
                    <span class="mr-1 text-base">Contenu premium</span>
                    <input type="checkbox" class="h-5 w-5 rounded" wire:model="isPremium">
                </label>

                @if ($isDisabled)
                    <x-default-submit-button disabled>
                        Post
                    </x-default-submit-button>
                @else
                    <x-default-submit-button wire:loading.attr="disabled">
                        Post
                    </x-default-submit-button>
                @endif

            </div>
        </form>
    </div>
</div>
