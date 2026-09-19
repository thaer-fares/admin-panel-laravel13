@props(['variant' => 'primary', 'type' => 'button'])
@php
    $base = 'inline-flex items-center justify-center gap-1.5 text-sm font-medium rounded-xl px-4 py-2.5 transition disabled:opacity-50 disabled:cursor-not-allowed';
    $variants = [
        'primary'   => 'text-white bg-gradient-to-br from-brand-600 to-brand-950 shadow-sm hover:shadow-md',
        'gold'      => 'text-brand-950 font-semibold bg-gradient-to-br from-gold-300 to-gold-500 shadow-sm hover:shadow-md',
        'secondary' => 'text-ink bg-white border border-border hover:bg-app-bg',
        'danger'    => 'text-white bg-danger hover:opacity-90',
        'ghost'     => 'text-ink-muted hover:text-ink',
    ];
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => $base . ' ' . $variants[$variant]]) }}>
    {{ $slot }}
</button>
