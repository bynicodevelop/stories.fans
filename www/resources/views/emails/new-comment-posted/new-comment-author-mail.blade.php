@component('mail::message')
{{ __('new-comment-author-mail.hello', ['name' => Str::ucfirst($name)]) }}

{{ __('new-comment-author-mail.intro') }}

@component('mail::button', ['url' => $url])
    {{ __('new-comment-author-mail.button-label') }}
@endcomponent

{{ __('new-comment-author-mail.cordially') }}<br>
{{ config('app.name') }}
@endcomponent
