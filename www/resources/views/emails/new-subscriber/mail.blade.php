@component('mail::message')
{{ __('new-subscriber-mail.hello', ['name' => Str::ucfirst($name)]) }}

{{ __('new-subscriber-mail.new-subscriber-intro', [
    "subscriberName" => $subscriberName
]) }}

@component('mail::button', ['url' => $url])
    {{ __('new-subscriber-mail.button-label') }}
@endcomponent

{{ __('new-subscriber-mail.cordially') }}<br>
{{ config('app.name') }}
@endcomponent
