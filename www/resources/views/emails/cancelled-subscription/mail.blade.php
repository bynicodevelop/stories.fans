@component('mail::message')
{{ __('cancelled-subscription-mail.hello', ['name' => Str::ucfirst($name)]) }}

{{ __('cancelled-subscription-mail.cancelled-subscription-intro') }}

{{ __('cancelled-subscription-mail.cancelled-subscription-complement-1', [
    "authorName" => $authorName
]) }}

{{ __('cancelled-subscription-mail.cancelled-subscription-complement-2') }}

@component('mail::button', ['url' => $url])
    {{ __('cancelled-subscription-mail.button-label') }}
@endcomponent

{{ __('cancelled-subscription-mail.cordially') }}<br>
{{ config('app.name') }}
@endcomponent
