{{--
    Bar chart — vertical or horizontal. borderRadius: 2 per CLAUDE.MD spec.
    Usage: <x-analytics.bar-chart id="ratingChart" :labels="$labels" :data="$data" color="#800000" />
--}}
@props([
    'id',
    'title' => null,
    'labels' => [],
    'data' => [],
    'color' => '#198754',
    'horizontal' => false,
    'maxY' => null,
    'height' => 200,
])

<x-analytics.chart :id="$id" :title="$title" :height="$height" {{ $attributes }}>
</x-analytics.chart>

@push('scripts')
<script>
(function() {
    const ctx = document.getElementById('{{ $id }}');
    if (!ctx) return;
    new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: '{{ $title }}',
                data: @json($data),
                backgroundColor: '{{ $color }}',
                borderRadius: 2,
            }]
        },
        options: {
            responsive: true,
            indexAxis: '{{ $horizontal ? "y" : "x" }}',
            plugins: { legend: { display: false } },
            scales: {
                {{ $horizontal ? 'x' : 'y' }}: {
                    beginAtZero: true,
                    @if($maxY) max: {{ $maxY }}, @endif
                }
            }
        }
    });
})();
</script>
@endpush
