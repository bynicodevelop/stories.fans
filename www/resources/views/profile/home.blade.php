<x-app-layout>
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hiddens space-y-6">
            @livewire('post.editor')

            @livewire('post.items')
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    @endpush

</x-app-layout>
