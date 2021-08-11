@props(['user'])

<div class="space-x-3">
    @if (!empty($user['instagram']))
        <a href="{{ $user['instagram'] }}" target="_blank">
            <i class="fab fa-instagram text-xl text-gray-700 hover:text-pink-600"></i>
        </a>
    @endif

    @if (!empty($user['facebook']))
        <a href="{{ $user['facebook'] }}" target="_blank">
            <i class="fab fa-facebook text-xl text-gray-700 hover:text-pink-600"></i>
        </a>
    @endif

    @if (!empty($user['youtube']))
        <a href="{{ $user['youtube'] }}" target="_blank">
            <i class="fab fa-youtube text-xl text-gray-700 hover:text-pink-600"></i>
        </a>
    @endif

    @if (!empty($user['tiktoc']))
        <a href="{{ $user['tiktoc'] }}" target="_blank">
            <i class="fab fa-tiktoc text-xl text-gray-700 hover:text-pink-600"></i>
        </a>
    @endif

    @if (!empty($user['snapchat']))
        <a href="{{ $user['snapchat'] }}" target="_blank">
            <i class="fab fa-snapchat text-xl text-gray-700 hover:text-pink-600"></i>
        </a>
    @endif

    @if (!empty($user['twitter']))
        <a href="{{ $user['twitter'] }}" target="_blank">
            <i class="fab fa-twitter-square text-xl text-gray-700 hover:text-pink-600"></i>
        </a>
    @endif
</div>
