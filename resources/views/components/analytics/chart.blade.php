{{-- Base chart wrapper — all analytics components build on this --}}
@props([
    'id',
    'title' => null,
    'height' => 200,
])

<x-card.chart-card :title="$title" {{ $attributes }}>
    <canvas id="{{ $id }}" height="{{ $height }}"></canvas>
</x-card.chart-card>
