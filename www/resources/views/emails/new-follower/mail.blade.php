@component('mail::message')
{{ __('new-follower-mail.hello', ['name' => Str::ucfirst($name)]) }}

{{ __('new-follower-mail.new-follower-intro', [
    'followerName' => $followerName,
]) }}

@component('mail::button', ['url' => $url])
    {{ __('new-follower-mail.button-label') }}
@endcomponent

{{ __('new-follower-mail.cordially') }}<br>
{{ config('app.name') }}
@endcomponent
