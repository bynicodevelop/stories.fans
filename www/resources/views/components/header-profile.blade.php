@props(['user', 'showBio' => false])

<div class="grid justify-items-center">
    <img class="w-20 h-20 rounded-full mb-3" src="{{ $user['profile_photo_url'] }}" />
    <h1>{{ $user['name'] }}</h1>
    <h2 class="text-gray-500 italic text-sm mb-3"><span>@</span>{{ $user['slug'] }}</h2>

    <x-profile-social-link :user="$user" />

    @if ($showBio)
        <div>
            <p>{{ $user['bio'] }}</p>
        </div>
    @endif
</div>
