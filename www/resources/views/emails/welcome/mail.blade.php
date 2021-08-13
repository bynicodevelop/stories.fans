@component('mail::message')
{{ __('welcome-mail.hello', [ 'name' => $name ]) }},

{{ __('welcome-mail.welcome-intro', ['sitename' => config('app.name')]) }}

{{ __('welcome-mail.profile-access') }}

@component('mail::button', ['url' => route('profiles-slug', ['slug' => $slug])])
    {{ __('welcome-mail.button-label') }}
@endcomponent

{{__('welcome-mail.cordially')}}<br>
{{ config('app.name') }}
@endcomponent
