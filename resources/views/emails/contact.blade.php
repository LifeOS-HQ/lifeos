@component('mail::message')
# Nachricht von {{ $attributes['name'] }}

{!! $attributes['message'] !!}

{{ config('app.name') }}
@endcomponent