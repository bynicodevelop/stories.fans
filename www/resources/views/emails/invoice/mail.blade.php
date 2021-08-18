@component('mail::message')
{{ __('invoice-mail.hello', ['name' => Str::ucfirst($name)]) }},

{!! __('invoice-mail.invoice-intro', [
    'link' => $link,
    'author' => $authorName,
]) !!}

{{ __('invoice-mail.invoice') }}

@component('mail::button', ['url' => $url])
    {{ __('invoice-mail.button-label') }}
@endcomponent

{{ __('invoice-mail.cordially') }}<br>
{{ config('app.name') }}
@endcomponent
