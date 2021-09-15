@component('mail::message')
{{ __('new-comment-to-other-mail.hello', ['name' => Str::ucfirst($name)]) }}

{{ __('new-comment-to-other-mail.intro') }}

@component('mail::button', ['url' => $url])
    {{ __('new-comment-to-other-mail.button-label') }}
@endcomponent

{{ __('new-comment-to-other-mail.cordially') }}<br>
{{ config('app.name') }}
@endcomponent
