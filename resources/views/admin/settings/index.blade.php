@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-semibold mb-1">Academic Period Settings</h2>
            <p class="text-muted mb-0 small">Control the active academic year and semester for evaluation cycles.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card" style="border:1px solid #e8e8e8; border-radius:4px;">
                <div class="card-body">
                    <div class="text-muted small mb-2">Currently Active Period</div>
                    @if($activePeriod)
                        <div class="fw-semibold fs-4 mb-1">A.Y. {{ $activePeriod->academic_year }}</div>
                        <div class="fs-5 mb-3">{{ $activePeriod->semester }}</div>
                        <div class="text-muted small mb-4">
                            Opened: {{ $activePeriod->started_at?->format('M d, Y h:i A') ?? '—' }}
                        </div>

                        <div class="border-top pt-3">
                            <div class="text-muted small mb-2">Next Period (on transition)</div>
                            <div class="fw-semibold">A.Y. {{ $nextPreview['academic_year'] }} · {{ $nextPreview['semester'] }}</div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transitionModal">
                                <i class="bi bi-arrow-right-circle me-1"></i> Close Period &amp; Open Next
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#closeModal">
                                Close Period Only
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No active academic period. Users cannot submit evaluations until one is opened.
                        </div>

                        <form method="POST" action="{{ route('admin.settings.open-initial') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small text-muted">Academic Year</label>
                                <input type="text" name="academic_year" class="form-control" placeholder="2025-2026" pattern="\d{4}-\d{4}" value="{{ $nextPreview['academic_year'] }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Semester</label>
                                <select name="semester" class="form-select" required>
                                    <option value="First Semester">First Semester</option>
                                    <option value="Second Semester">Second Semester</option>
                                    <option value="Summer">Summer</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-play-circle me-1"></i> Open Initial Period
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card" style="border:1px solid #e8e8e8; border-radius:4px;">
                <div class="card-body">
                    <div class="text-muted small mb-3">How the cycle works</div>
                    <ol class="small mb-0" style="padding-left:1.1rem;">
                        <li class="mb-2">First Semester → Second Semester (same year)</li>
                        <li class="mb-2">Second Semester → Summer (same year)</li>
                        <li class="mb-2">Summer → First Semester (year incremented by 1)</li>
                        <li class="mb-2">Users may evaluate each category once per active period.</li>
                        <li>Old evaluations are preserved for historical reporting.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4" style="border:1px solid #e8e8e8; border-radius:4px;">
        <div class="card-body">
            <div class="fw-semibold mb-3">Period History</div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Started</th>
                            <th>Ended</th>
                            <th class="text-end">Submitted Evaluations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $period)
                            <tr>
                                <td>{{ $period->academic_year }}</td>
                                <td>{{ $period->semester }}</td>
                                <td>
                                    @if($period->is_active)
                                        <span class="badge bg-success" style="border-radius:2px;">Active</span>
                                    @else
                                        <span class="badge bg-secondary" style="border-radius:2px;">Closed</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $period->started_at?->format('M d, Y') ?? '—' }}</td>
                                <td class="small text-muted">{{ $period->ended_at?->format('M d, Y') ?? '—' }}</td>
                                <td class="text-end fw-semibold">{{ $period->evaluation_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted small py-4">No periods recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($activePeriod)
<div class="modal fade" id="transitionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:4px;">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Period Transition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">This will close the current period and open the next one:</p>
                <ul class="small mb-3">
                    <li><strong>Closing:</strong> A.Y. {{ $activePeriod->academic_year }} · {{ $activePeriod->semester }}</li>
                    <li><strong>Opening:</strong> A.Y. {{ $nextPreview['academic_year'] }} · {{ $nextPreview['semester'] }}</li>
                </ul>
                <div class="alert alert-warning small mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    This action is irreversible. All users will be able to evaluate categories again in the new period.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.settings.transition') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Confirm Transition</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:4px;">
            <div class="modal-header">
                <h5 class="modal-title">Close Active Period</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">This will close <strong>A.Y. {{ $activePeriod->academic_year }} · {{ $activePeriod->semester }}</strong> without opening a new one. Users will be unable to evaluate until a new period is opened.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.settings.close') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Close Period</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
