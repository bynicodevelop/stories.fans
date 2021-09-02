@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        @if (!empty($title))
            <div class="text-lg mb-4">
                {{ $title }}
            </div>
        @endif

        <div>
            {{ $content }}
        </div>
    </div>

    @if (!empty($footer))
        <div class="px-6 py-4 bg-gray-100 text-right">
            {{ $footer }}
        </div>
    @endif
</x-jet-modal>
