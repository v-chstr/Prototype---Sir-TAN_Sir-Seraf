@extends('layouts.admin')

@section('title', 'Summary Report')
@section('page-title', 'Summary Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5><i class="bi bi-journal-text me-2"></i>Comprehensive Summary Report</h5>
    <a href="{{ route('admin.reports.export-summary') }}" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-2"></i>Export to Excel
    </a>
</div>

@foreach($summary as $item)
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <span class="badge {{ $item['type'] === 'standard' ? 'bg-primary' : 'bg-info' }} me-2">
                        {{ ucfirst($item['type']) }}
                    </span>
                    {{ $item['category'] }}
                </h5>
            </div>
            <div class="text-end">
                <span class="badge bg-secondary">{{ $item['total_evaluations'] }} Evaluations</span>
                <span class="badge bg-{{ $item['overall_avg'] >= 4 ? 'success' : ($item['overall_avg'] >= 3 ? 'warning' : 'danger') }} fs-6 ms-2">
                    {{ $item['overall_avg'] }}/5
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50%">Criteria/Question</th>
                            <th class="text-center">Avg Rating</th>
                            <th class="text-center">Responses</th>
                            <th style="width: 150px">Performance</th>
                            <th class="text-center">Label</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item['criteria_stats'] as $stat)
                            <tr>
                                <td>{{ $stat['question'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $stat['avg_rating'] >= 4 ? 'success' : ($stat['avg_rating'] >= 3 ? 'warning' : 'danger') }}">
                                        {{ $stat['avg_rating'] }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $stat['response_count'] }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $stat['avg_rating'] >= 4 ? 'success' : ($stat['avg_rating'] >= 3 ? 'warning' : 'danger') }}" 
                                             style="width: {{ ($stat['avg_rating']/5)*100 }}%">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($stat['avg_rating'] >= 4.5)
                                        <span class="badge bg-success">Excellent</span>
                                    @elseif($stat['avg_rating'] >= 3.5)
                                        <span class="badge bg-primary">Very Good</span>
                                    @elseif($stat['avg_rating'] >= 2.5)
                                        <span class="badge bg-warning">Good</span>
                                    @elseif($stat['avg_rating'] >= 1.5)
                                        <span class="badge bg-secondary">Fair</span>
                                    @else
                                        <span class="badge bg-danger">Poor</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endforeach

<div class="mt-4">
    <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Reports
    </a>
</div>
@endsection
