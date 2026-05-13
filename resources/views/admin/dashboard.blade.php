@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1">Total Users</small>
                <h3 class="mb-0 fw-semibold">{{ number_format($stats['total_users']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1">Total Evaluations</small>
                <h3 class="mb-0 fw-semibold">{{ number_format($stats['total_evaluations']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1">Categories</small>
                <h3 class="mb-0 fw-semibold">{{ number_format($stats['total_categories']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body py-3">
                <small class="text-muted d-block mb-1">Unread Messages</small>
                <h3 class="mb-0 fw-semibold">{{ number_format($stats['unread_messages']) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="chart-container">
            <h5 class="mb-4">Monthly Evaluations ({{ date('Y') }})</h5>
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-container">
            <h5 class="mb-4">User Distribution</h5>
            <canvas id="userDistributionChart" height="200"></canvas>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="chart-container">
            <h5 class="mb-4">Category Ratings</h5>
            <canvas id="categoryRatingChart" height="200"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="chart-container">
            <h5 class="mb-4">Evaluations by Category</h5>
            <canvas id="categoryCountChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Recent Evaluations Table -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Recent Evaluations</h5>
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
                        <td>{{ \App\Helpers\AnonymizeHelper::anonymizeUser($evaluation->user->id ?? $evaluation->id) }}</td>
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
                borderRadius: 2
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
                borderRadius: 2
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
