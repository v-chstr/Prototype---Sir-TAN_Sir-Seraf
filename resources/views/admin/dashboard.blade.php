@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ number_format($stats['total_users']) }}</h3>
                    <small class="text-muted">Total Users</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ number_format($stats['total_evaluations']) }}</h3>
                    <small class="text-muted">Total Evaluations</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="bi bi-tags"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ number_format($stats['total_categories']) }}</h3>
                    <small class="text-muted">Categories</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger me-3">
                    <i class="bi bi-envelope"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ number_format($stats['unread_messages']) }}</h3>
                    <small class="text-muted">Unread Messages</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="chart-container">
            <h5 class="mb-4"><i class="bi bi-graph-up me-2"></i>Monthly Evaluations ({{ date('Y') }})</h5>
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-container">
            <h5 class="mb-4"><i class="bi bi-pie-chart me-2"></i>User Distribution</h5>
            <canvas id="userDistributionChart" height="200"></canvas>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="chart-container">
            <h5 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Category Ratings</h5>
            <canvas id="categoryRatingChart" height="200"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-container">
            <h5 class="mb-4"><i class="bi bi-clipboard-data me-2"></i>Evaluations by Category</h5>
            <canvas id="categoryCountChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Recent Evaluations Table -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Evaluations</h5>
        <a href="{{ route('admin.evaluations') }}" class="btn btn-sm btn-spup">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Avg Rating</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentEvaluations as $evaluation)
                    <tr>
                        <td>{{ $evaluation->created_at->format('M d, Y') }}</td>
                        <td>{{ $evaluation->user->name ?? 'Anonymous' }}</td>
                        <td>{{ $evaluation->category->name ?? 'Unknown' }}</td>
                        <td>
                            <span class="badge {{ $evaluation->category->type === 'standard' ? 'bg-primary' : 'bg-info' }}">
                                {{ ucfirst($evaluation->category->type ?? 'N/A') }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $evaluation->average_rating >= 4 ? 'success' : ($evaluation->average_rating >= 3 ? 'warning' : 'danger') }}">
                                {{ number_format($evaluation->average_rating, 1) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.evaluations.show', $evaluation->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No evaluations found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Monthly Evaluations Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Evaluations',
                data: @json(array_values($evaluationsByMonth)),
                borderColor: '#800000',
                backgroundColor: 'rgba(128, 0, 0, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // User Distribution Chart
    const userCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: @json($usersByRole->pluck('display_name')),
            datasets: [{
                data: @json($usersByRole->pluck('count')),
                backgroundColor: ['#800000', '#FFD700', '#4a0000', '#28a745', '#17a2b8']
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

    // Category Rating Chart
    const ratingCtx = document.getElementById('categoryRatingChart').getContext('2d');
    new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: @json($categoryRatings->pluck('name')),
            datasets: [{
                label: 'Average Rating',
                data: @json($categoryRatings->pluck('avg_rating')),
                backgroundColor: '#800000',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5
                }
            }
        }
    });

    // Category Count Chart
    const countCtx = document.getElementById('categoryCountChart').getContext('2d');
    new Chart(countCtx, {
        type: 'bar',
        data: {
            labels: @json($categoryStats->pluck('name')),
            datasets: [{
                label: 'Total Evaluations',
                data: @json($categoryStats->pluck('evaluations_count')),
                backgroundColor: '#FFD700',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
