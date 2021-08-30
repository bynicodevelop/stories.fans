<x-jet-form-section submit="copyLink">
    <x-slot name="title">
        {{ __('invitation.information') }}
    </x-slot>

    <x-slot name="description">
        {!! __('invitation.description') !!}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4 ">
            <x-jet-label for="link" value="{{ __('invitation.link') }}" />
            <x-default-input id="link" type="text" class="mt-1 w-full opacity-70" wire:model="link" readonly />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('invitation.copied') }}
        </x-jet-action-message>

        <x-jet-button type="button" data-clipboard-target="#link">
            {{ __('invitation.copy') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>

@push('scripts')
    <script type="application/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            let clipboardCopyLink = new ClipboardJS('button[type=button]');

            clipboardCopyLink.on('success', function(e) {
                window.livewire.emit('saved')
            });
        })
    </script>
@endpush
