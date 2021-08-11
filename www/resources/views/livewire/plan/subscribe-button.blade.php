<x-default-submit-button type="button" class="font-bold px-10 py-3 transition-colors w-full"
    wire:click.prevent="subscribe" wire:loading.attr="disabled">
    {{ __('plan.subscribe-now') }}
</x-default-submit-button>
