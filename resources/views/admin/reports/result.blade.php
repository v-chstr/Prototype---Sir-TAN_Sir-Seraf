@extends('layouts.admin')

@section('title', 'Report Results')
@section('page-title', 'Report Results')

@section('content')
<!-- Statistics Summary -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['total_evaluations'] }}</h3>
                <small class="text-muted">Total Evaluations</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['total_responses'] }}</h3>
                <small class="text-muted">Total Responses</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['average_rating'] }}/5</h3>
                <small class="text-muted">Overall Average Rating</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="chart-container">
            <h5 class="mb-4">Evaluations by Category</h5>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <h5 class="mb-4">Criteria Performance</h5>
            <canvas id="criteriaChart"></canvas>
        </div>
    </div>
</div>

<!-- Criteria Statistics Table -->
<div class="table-container mb-4">
    <h5 class="mb-4"><i class="bi bi-list-ul me-2"></i>Criteria Performance</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Question</th>
                    <th>Avg Rating</th>
                    <th>Responses</th>
                    <th>Performance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($criteriaStats as $stat)
                    <tr>
                        <td>{{ $stat['category'] }}</td>
                        <td>{{ Str::limit($stat['question'], 50) }}</td>
                        <td>
                            <span class="badge bg-{{ $stat['avg_rating'] >= 4 ? 'success' : ($stat['avg_rating'] >= 3 ? 'warning' : 'danger') }}">
                                {{ $stat['avg_rating'] }}
                            </span>
                        </td>
                        <td>{{ $stat['total_responses'] }}</td>
                        <td>
                            <div class="progress" style="width: 100px;">
                                <div class="progress-bar bg-{{ $stat['avg_rating'] >= 4 ? 'success' : ($stat['avg_rating'] >= 3 ? 'warning' : 'danger') }}" 
                                     style="width: {{ ($stat['avg_rating']/5)*100 }}%"></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Detailed Evaluations -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-table me-2"></i>Detailed Evaluations</h5>
        <form action="{{ route('admin.reports.generate') }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="report_type" value="{{ $request->report_type }}">
            <input type="hidden" name="category_id" value="{{ $request->category_id }}">
            <input type="hidden" name="date_from" value="{{ $request->date_from }}">
            <input type="hidden" name="date_to" value="{{ $request->date_to }}">
            <input type="hidden" name="format" value="excel">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i>Export to Excel
            </button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Category</th>
                    <th>Avg Rating</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations->take(50) as $evaluation)
                    <tr>
                        <td>{{ $evaluation->created_at->format('M d, Y') }}</td>
                        <td>{{ $evaluation->user->name ?? 'Anonymous' }}</td>
                        <td>{{ $evaluation->category->name ?? 'Unknown' }}</td>
                        <td>
                            <span class="badge bg-{{ $evaluation->average_rating >= 4 ? 'success' : ($evaluation->average_rating >= 3 ? 'warning' : 'danger') }}">
                                {{ number_format($evaluation->average_rating, 1) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($evaluation->overall_comment, 50) ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No evaluations found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Reports
    </a>
</div>
@endsection

@push('scripts')
<script>
    // Category Chart
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: @json($categoryStats->pluck('name')),
            datasets: [{
                data: @json($categoryStats->pluck('count')),
                backgroundColor: ['#800000', '#FFD700', '#4a0000', '#28a745', '#17a2b8', '#6c757d', '#dc3545', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Criteria Chart
    new Chart(document.getElementById('criteriaChart'), {
        type: 'bar',
        data: {
            labels: @json($criteriaStats->take(10)->pluck('question')->map(fn($q) => substr($q, 0, 30) . '...')),
            datasets: [{
                label: 'Average Rating',
                data: @json($criteriaStats->take(10)->pluck('avg_rating')),
                backgroundColor: '#800000',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    max: 5
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
