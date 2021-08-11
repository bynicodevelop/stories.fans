<button class="inline-block px-2 py-2 text-center" wire:click="toggleLike">
    @if ($hasLike)
        <i class="fas fa-heart text-pink-500 pr-1"></i>
    @else
        <i class="far fa-heart pr-1"></i>
    @endif
    {{ \App\Helpers\FormatNumberHelper::thousandsFormat($nbLikes) }}
</button>
