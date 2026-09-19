@props(['variant' => 'neutral'])
@php
    $variants = [
        'success' => 'bg-success/10 text-success',
        'danger'  => 'bg-danger/10 text-danger',
        'gold'    => 'bg-gold-500/15 text-gold-500',
        'brand'   => 'bg-brand-800/10 text-brand-800',
        'neutral' => 'bg-gray-100 text-ink-muted',
    ];
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center text-xs px-2.5 py-1 rounded-full font-medium {$variants[$variant]}"]) }}>
    {{ $slot }}
</span>
