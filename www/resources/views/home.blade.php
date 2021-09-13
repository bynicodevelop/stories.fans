<x-app-layout>
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="space-y-6">
            @livewire('post.editor', ['attributes' => 'hidden md:block'])

            @livewire('post.feed', ['user' => $user])
        </div>
    </div>

    <x-bottom-navigation-bar />

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    @endpush
</x-app-layout>
