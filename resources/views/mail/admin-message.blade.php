<x-mail::message>
# {{ __('Hello :name', ['name' => $recipient->name]) }}

{!! nl2br(e($mailBody)) !!}

{{ config('app.name') }}
</x-mail::message>
