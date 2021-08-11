@props(['message'])

<p {{ $attributes->merge(['class' => 'text-xs text-gray-600 italic pt-1']) }}>{!! $message !!}</p>
