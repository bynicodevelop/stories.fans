<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hiddens space-y-6">
                <div class="p-3 pb-5 bg-white grid justify-items-center rounded">
                    <x-header-profile :user="$post['user']" />

                    <div class="flex justify-center space-x-6">
                        @livewire("profiles.count-post", ['user' => $post['user']])
                        @livewire("profiles.count-like", ['user' => $post['user']])
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded px-4 py-2">
                        <x-card-header-component :post="$post" :user="$post['user']" :wire:key="$post['id']" />
                        <div class="text-gray-800 dark:text-gray-100 leading-snug md:leading-normal">
                            {{ $post['content'] }}
                        </div>
                        <x-card-footer-component :post="$post" :wire:key="$post['id']" />
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
        @endpush
</x-app-layout>
