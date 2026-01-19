@extends('layouts.admin')

@section('title', 'Generate Reports')
@section('page-title', 'Generate Reports')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-bar-graph me-2"></i>Report Generator</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.generate') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select name="report_type" class="form-select" required>
                            <option value="detailed">Detailed Evaluations Report</option>
                            <option value="summary">Summary Report</option>
                            <option value="category">Category-Specific Report</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category (Optional)</label>
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} ({{ ucfirst($category->type) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Output Format</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="view" id="formatView" checked>
                                <label class="form-check-label" for="formatView">
                                    <i class="bi bi-eye me-1"></i>View on Screen
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="excel" id="formatExcel">
                                <label class="form-check-label" for="formatExcel">
                                    <i class="bi bi-file-earmark-excel me-1"></i>Export to Excel
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-spup w-100">
                        <i class="bi bi-gear me-2"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Exports</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.summary') }}" class="btn btn-outline-primary">
                        <i class="bi bi-journal-text me-2"></i>View Summary Report
                    </a>
                    <a href="{{ route('admin.reports.export-summary') }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel me-2"></i>Export Full Summary (Excel)
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Report Types</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong><i class="bi bi-list-check text-primary me-2"></i>Detailed Report</strong>
                    <p class="small text-muted mb-0">Contains all individual evaluation records with user information, ratings, and comments.</p>
                </div>
                <hr>
                <div class="mb-3">
                    <strong><i class="bi bi-bar-chart text-success me-2"></i>Summary Report</strong>
                    <p class="small text-muted mb-0">Aggregated statistics showing average ratings per category and criteria.</p>
                </div>
                <hr>
                <div>
                    <strong><i class="bi bi-filter text-warning me-2"></i>Category Report</strong>
                    <p class="small text-muted mb-0">Focused report on a specific evaluation category.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
