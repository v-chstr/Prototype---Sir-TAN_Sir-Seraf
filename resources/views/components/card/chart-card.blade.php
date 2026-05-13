@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'bg-surface-card rounded shadow-[0_1px_4px_rgba(0,0,0,0.08)] p-5']) }}>
    @if($title)
        <h5 class="text-base font-semibold text-gray-900 mb-4">{{ $title }}</h5>
    @endif
    {{ $slot }}
</div>
