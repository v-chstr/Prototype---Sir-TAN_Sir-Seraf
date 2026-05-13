@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Evaluation Categories')

@section('content')
<div class="row g-4">
    <div class="col-12 d-flex justify-content-end mb-2">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-spup btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Add Category
        </a>
    </div>

    @foreach(['standard' => 'Standards Categories', 'office' => 'Office Categories'] as $type => $label)
    <div class="col-12">
        <div class="card {{ !$loop->last ? 'mb-4' : '' }}">
            <div class="card-header bg-white">
                <h5 class="mb-0">{{ $label }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Questions</th>
                                <th>Evaluations</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories->where('type', $type) as $category)
                                <tr>
                                    <td>
                                        <i class="{{ $category->icon ?? 'bi bi-star' }} me-2" style="color: var(--spup-primary);"></i>
                                        {{ $category->name }}
                                    </td>
                                    <td>{{ Str::limit($category->description, 50) }}</td>
                                    <td><span class="badge bg-secondary">{{ $category->criteria_count }}</span></td>
                                    <td><span class="badge bg-info">{{ $category->evaluations_count }}</span></td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.toggle', $category->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $category->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi {{ $category->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No {{ strtolower($label) }} found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
