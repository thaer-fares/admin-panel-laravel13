@props(['title'])
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="text-2xl font-bold text-ink">{{ $title }}</h1>
    @isset($action)
        <div>{{ $action }}</div>
    @endisset
</div>
