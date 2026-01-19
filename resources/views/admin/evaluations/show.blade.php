@extends('layouts.admin')

@section('title', 'Evaluation Details')
@section('page-title', 'Evaluation Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Evaluation Responses</h5>
            </div>
            <div class="card-body">
                @foreach($evaluation->responses as $response)
                    <div class="p-3 mb-3 rounded" style="background: #f8f9fa;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $response->criteria->question ?? 'Unknown Question' }}</strong>
                            </div>
                            <span class="badge bg-{{ $response->rating >= 4 ? 'success' : ($response->rating >= 3 ? 'warning' : 'danger') }} fs-6">
                                {{ $response->rating }}/5
                            </span>
                        </div>
                        @if($response->comment)
                            <p class="text-muted small mt-2 mb-0">
                                <i class="bi bi-chat-dots me-1"></i>{{ $response->comment }}
                            </p>
                        @endif
                    </div>
                @endforeach

                @if($evaluation->overall_comment)
                    <div class="mt-4 p-3 rounded" style="background: #e3f2fd;">
                        <h6><i class="bi bi-chat-square-text me-2"></i>Overall Comment</h6>
                        <p class="mb-0">{{ $evaluation->overall_comment }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Evaluation Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>ID:</th>
                        <td>#{{ $evaluation->id }}</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $evaluation->created_at->format('M d, Y H:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td>{{ $evaluation->category->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td>
                            <span class="badge {{ $evaluation->category->type === 'standard' ? 'bg-primary' : 'bg-info' }}">
                                {{ ucfirst($evaluation->category->type ?? 'N/A') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Academic Year:</th>
                        <td>{{ $evaluation->academic_year ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Semester:</th>
                        <td>{{ $evaluation->semester ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Average Rating:</th>
                        <td>
                            <span class="badge bg-{{ $evaluation->average_rating >= 4 ? 'success' : ($evaluation->average_rating >= 3 ? 'warning' : 'danger') }} fs-6">
                                {{ number_format($evaluation->average_rating, 2) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Evaluator Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Name:</th>
                        <td>{{ $evaluation->user->name ?? 'Anonymous' }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $evaluation->user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Role:</th>
                        <td>{{ $evaluation->user->role->display_name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.evaluations') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i>Back to Evaluations
            </a>
        </div>
    </div>
</div>
@endsection
