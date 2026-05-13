@props([
    'rating' => 0,
    'max' => 5,
])

@php
    $pct = $max > 0 ? ($rating / $max) * 100 : 0;
    $color = $rating >= 4 ? 'bg-rating-excellent' : ($rating >= 3 ? 'bg-rating-good' : 'bg-rating-poor');
@endphp

<div {{ $attributes->merge(['class' => 'w-24 bg-gray-200 rounded-sm h-5 overflow-hidden']) }}>
    <div class="{{ $color }} h-full rounded-sm transition-all" style="width: {{ $pct }}%"></div>
</div>
