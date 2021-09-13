<x-app-layout>
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden space-y-6">
            <div class="relative p-3 pb-5 bg-white grid justify-items-center rounded">
                @auth
                    <a class="absolute top-3 right-5 md:hidden" href="{{ route('settings') }}"
                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                        <i class="fas fa-cogs text-gray-500"></i>
                    </a>
                @endauth

                <div class="mb-5">
                    <x-header-profile :user="$user" />
                </div>

                <div class="flex justify-center space-x-6">
                    @auth
                        @if ($user['id'] != Auth::id())
                            @livewire("profiles.follow-btn", ['user' => $user])
                        @endif
                    @else
                        <x-default-link-button href="{{ route('register', ['slug' => $user['slug']]) }}" type="button">
                            {{ __('profile.follow') }}
                        </x-default-link-button>
                    @endauth

                    @livewire("profiles.count-post", ['user' => $user])
                    @livewire("profiles.count-like", ['user' => $user])
                </div>
            </div>

            @livewire('post.items', ['user' => $user])
        </div>
    </div>

    <x-bottom-navigation-bar />

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    @endpush
</x-app-layout>
