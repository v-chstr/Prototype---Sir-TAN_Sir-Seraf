@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Evaluation Categories')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-award me-2"></i>Standards Categories</h5>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories->where('type', 'standard') as $category)
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Office Categories</h5>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories->where('type', 'office') as $category)
                                <tr>
                                    <td>
                                        <i class="{{ $category->icon ?? 'bi bi-building' }} me-2" style="color: var(--spup-primary);"></i>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
