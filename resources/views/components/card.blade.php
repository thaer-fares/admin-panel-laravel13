@props(['padding' => 'p-5'])
<div {{ $attributes->merge(['class' => "bg-white rounded-2xl border border-border {$padding}"]) }}>
    {{ $slot }}
</div>
