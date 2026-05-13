{{--
    Pie chart — alias for doughnut with type=pie.
    Usage: <x-analytics.pie-chart id="catChart" :labels="$labels" :data="$data" />
--}}
@props([
    'id',
    'title' => null,
    'labels' => [],
    'data' => [],
    'colors' => ['#198754', '#FFD700', '#0d5c36', '#dc3545', '#0dcaf0', '#6c757d', '#ffc107', '#0d6efd'],
    'height' => 200,
])

<x-analytics.doughnut-chart
    :id="$id"
    :title="$title"
    :labels="$labels"
    :data="$data"
    :colors="$colors"
    type="pie"
    :height="$height"
    {{ $attributes }}
/>
