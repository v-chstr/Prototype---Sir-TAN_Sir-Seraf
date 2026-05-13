{{--
    Line chart — renders Chart.js line chart with SPUP defaults.
    Usage: <x-analytics.line-chart id="monthlyChart" :labels="$labels" :data="$data" title="Monthly Evaluations" />
--}}
@props([
    'id',
    'title' => null,
    'labels' => [],
    'data' => [],
    'color' => '#198754',
    'fill' => true,
    'height' => 100,
])

<x-analytics.chart :id="$id" :title="$title" :height="$height" {{ $attributes }}>
</x-analytics.chart>

@push('scripts')
<script>
(function() {
    const ctx = document.getElementById('{{ $id }}');
    if (!ctx) return;
    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: '{{ $title }}',
                data: @json($data),
                borderColor: '{{ $color }}',
                backgroundColor: '{{ $color }}1a',
                fill: {{ $fill ? 'true' : 'false' }},
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
})();
</script>
@endpush
