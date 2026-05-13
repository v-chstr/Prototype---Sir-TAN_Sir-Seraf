@extends('layouts.admin')

@section('title', 'Evaluations')
@section('page-title', 'Manage Evaluations')

@section('content')
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">All Evaluations</h5>
        <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-spup">
            <i class="bi bi-download me-1"></i>Export Data
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                        {{ $role->display_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" placeholder="From Date" 
                   value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" placeholder="To Date" 
                   value="{{ request('date_to') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-spup me-2">
                <i class="bi bi-filter me-1"></i>Filter
            </button>
            <a href="{{ route('admin.evaluations') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Avg Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $evaluation)
                    <tr>
                        <td>#{{ $evaluation->id }}</td>
                        <td>{{ $evaluation->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ \App\Helpers\AnonymizeHelper::anonymizeUser($evaluation->user->id ?? $evaluation->id) }}</td>
                        <td>{{ $evaluation->user->role->display_name ?? 'N/A' }}</td>
                        <td>{{ $evaluation->category->name ?? 'Unknown' }}</td>
                        <td>
                            <span class="badge {{ $evaluation->category->type === 'standard' ? 'bg-primary' : 'bg-info' }}">
                                {{ ucfirst($evaluation->category->type ?? 'N/A') }}
                            </span>
                        </td>
                        <td>
                            @php $avgRating = $evaluation->average_rating; @endphp
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $avgRating >= 4 ? 'success' : ($avgRating >= 3 ? 'warning' : 'danger') }} me-2">
                                    {{ number_format($avgRating, 1) }}
                                </span>
                                <small class="text-muted">/ 5</small>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.evaluations.show', $evaluation->id) }}" 
                               class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            No evaluations found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $evaluations->withQueryString()->links() }}
    </div>
</div>
@endsection
