<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl md:max-w-lg  mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @livewire('post.editor')

                @livewire('post.feed', ['user' => $user])
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    @endpush
</x-app-layout>
