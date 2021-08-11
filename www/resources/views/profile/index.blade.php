<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hiddens space-y-6">
                <div class="p-3 pb-5 bg-white grid justify-items-center rounded">

                    <div class="mb-5">
                        <x-header-profile :user="$user" />
                    </div>

                    <div class="flex justify-center space-x-6">
                        @auth
                            @if ($user['id'] != Auth::id())
                                @livewire("profiles.follow-btn", ['user' => $user])
                            @endif
                        @else
                            <x-default-link-button href="{{ route('register', ['slug' => $user['slug']]) }}"
                                type="button">
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
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    @endpush
</x-app-layout>
