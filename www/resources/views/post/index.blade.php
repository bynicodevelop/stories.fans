<x-app-layout>
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hiddens space-y-6">
            <div class="py-5 bg-white grid justify-items-center rounded">
                <x-header-profile :user="$post['user']" />

                <div class="flex justify-center space-x-6">
                    @livewire("profiles.count-post", ['user' => $post['user']])
                    @livewire("profiles.count-like", ['user' => $post['user']])
                </div>
            </div>

            @livewire('post.item', ['post' => $post, 'isUniquePost' => true], key("content-{$post['id']}"))
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    @endpush
</x-app-layout>
