@props([
    'rating' => 0,
    'max' => 5,
    'showMax' => false,
])

@php
    $r = floatval($rating);
    $variant = $r >= 4 ? 'success' : ($r >= 3 ? 'warning' : 'danger');
@endphp

<span class="inline-flex items-center gap-1">
    <x-ui.badge :variant="$variant">
        {{ number_format($r, 1) }}
    </x-ui.badge>
    @if($showMax)
        <span class="text-xs text-gray-400">/ {{ $max }}</span>
    @endif
</span>
