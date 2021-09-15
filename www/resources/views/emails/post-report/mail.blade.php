@component('mail::message')
{{ __('post-report-mail.hello', ['name' => Str::ucfirst($name)]) }}

@foreach($data as $d) 
@component('mail::item-button', ['url' => route('profiles-slug', ['slug' => $d['user']['slug']]), 'label' => __('post-report-mail.see')])
{!! __('post-report-mail.post-content', ['name' => Str::ucfirst($d['user']['name']), 'n' => $d['nPost']]) !!}
@endcomponent
@endforeach

{{ __('post-report-mail.cordially') }}<br>
{{ config('app.name') }}
@endcomponent
