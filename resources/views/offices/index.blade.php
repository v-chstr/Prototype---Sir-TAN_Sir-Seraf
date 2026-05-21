@extends('layouts.app')

@section('title', 'Offices Evaluation - SPUP')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5 animate-fade-in">
        <h2 class="fw-bold"><i class="bi bi-building me-2" style="color: var(--spup-primary);"></i>Offices Evaluation</h2>
        <p class="text-muted">Rate the quality of service from various university offices and service units</p>
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
                            <i class="{{ $category->icon ?? 'bi bi-building' }}"></i>
                        </div>
                        <h4 class="card-title fw-bold">{{ $category->name }}</h4>
                        <p class="card-text text-muted">{{ $category->description }}</p>
                        <span class="badge bg-secondary mb-3">{{ $category->criteria->count() }} Questions</span>
                        <div class="d-grid">
                            @auth
                                @if($evaluatedIds->contains($category->id))
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
