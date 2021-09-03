<div class="space-y-6">
    <input
        class="tracking-wide py-2 px-4 mb-3 leading-relaxed appearance-none block w-full bg-white border border-gray-200 rounded focus:outline-none focus:border-gray-500"
        type="text" placeholder="@lang('profile.search')" wire:model.debounce.300ms="search">

    @foreach ($users as $user)
        <div class="bg-white p-2 rounded">
            <div class="flex p-1">
                <img class="w-12 h-12 rounded-full" src="{{ $user['profile_photo_url'] }}" />
                <div class="flex-1 ml-3 mt-1">
                    <span class="block font-medium text-base leading-snug text-black dark:text-gray-100">
                        {{ $user['name'] }}
                    </span>
                    <span class="block font-medium text-sm text-gray-500">
                        {{ $user['bio'] }}
                    </span>
                </div>

                <div class="pt-3">
                    <x-default-link-button href="{{ route('profiles-slug', ['slug' => $user['slug']]) }}">
                        Voir
                    </x-default-link-button>
                </div>
            </div>
        </div>
    @endforeach

    @if ($finished && empty($search))
        <div class="bg-white rounded p-6 text-center">
            <p>@lang('profile.all-profiles-found')</p>
        </div>
    @endif

    <x-bottom-navigation-bar />

    @push('scripts')
        <script>
            window.onscroll = function(ev) {
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                    window.livewire.emit('loadMore')
                }
            };
        </script>
    @endpush
</div>
