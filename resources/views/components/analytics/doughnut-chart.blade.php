{{--
    Doughnut / Pie chart.
    Usage: <x-analytics.doughnut-chart id="distChart" :labels="$labels" :data="$data" :colors="$colors" />
--}}
@props([
    'id',
    'title' => null,
    'labels' => [],
    'data' => [],
    'colors' => ['#198754', '#FFD700', '#0d5c36', '#dc3545', '#0dcaf0'],
    'type' => 'doughnut',
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
        type: '{{ $type }}',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($data),
                backgroundColor: @json($colors),
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
})();
</script>
@endpush
