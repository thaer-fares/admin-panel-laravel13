@props(['label', 'value', 'accent' => 'brand'])
@php
    $accents = [
        'brand'   => 'from-brand-600 to-brand-950',
        'gold'    => 'from-gold-300 to-gold-500',
        'success' => 'from-success to-brand-800',
        'danger'  => 'from-danger to-brand-950',
    ];
@endphp
<div class="bg-white rounded-2xl border border-border p-5 flex items-center gap-4">
    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 bg-gradient-to-br {{ $accents[$accent] }} text-white">
        {{ $slot }}
    </div>
    <div>
        <p class="text-ink-muted text-xs">{{ $label }}</p>
        <p class="text-2xl font-bold text-ink mt-0.5">{{ $value }}</p>
    </div>
</div>
