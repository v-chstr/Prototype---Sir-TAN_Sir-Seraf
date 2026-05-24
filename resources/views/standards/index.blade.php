@extends('layouts.app')

@section('title', 'Standards Evaluation - SPUP')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5 animate-fade-in">
        <h2 class="fw-bold"><i class="bi bi-award me-2" style="color: var(--spup-primary);"></i>Standards Evaluation</h2>
        <p class="text-muted">Evaluate the university's administrative excellence, learning environment, and facilities</p>
        @if($activePeriod)
            <div class="d-inline-block px-3 py-1 mt-2" style="background:#e8f5e9; color:#0d5c36; border-radius:4px; font-size:0.85rem;">
                A.Y. {{ $activePeriod->academic_year }} &middot; {{ $activePeriod->semester }}
            </div>
        @else
            <div class="d-inline-block px-3 py-1 mt-2" style="background:#fff3cd; color:#664d03; border-radius:4px; font-size:0.85rem;">
                <i class="bi bi-exclamation-triangle me-1"></i> Evaluation period not yet open
            </div>
        @endif
    </div>

    @if(session('already_evaluated'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('already_evaluated') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 justify-content-center">
        @forelse($categories as $category)
            <div class="col-md-4">
                <div class="card h-100 category-card animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="card-body text-center p-4">
                        <div class="category-icon">
                            <i class="{{ $category->icon ?? 'bi bi-star' }}"></i>
                        </div>
                        <h4 class="card-title fw-bold">{{ $category->name }}</h4>
                        <p class="card-text text-muted">{{ $category->description }}</p>
                        <span class="badge bg-secondary mb-3">{{ $category->criteria->count() }} Questions</span>
                        <div class="d-grid">
                            @auth
                                @if(!$activePeriod)
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-lock me-2"></i>Evaluation period not yet open
                                    </button>
                                @elseif($evaluatedIds->contains($category->id))
                                    <button class="btn btn-success" disabled>
                                        <i class="bi bi-check-circle me-2"></i>Already Evaluated
                                    </button>
                                @else
                                    <a href="{{ route('evaluation.show', $category->id) }}" class="btn btn-spup">
                                        <i class="bi bi-clipboard-check me-2"></i>Evaluate Now
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-spup">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Evaluate
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No evaluation categories available at the moment.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
