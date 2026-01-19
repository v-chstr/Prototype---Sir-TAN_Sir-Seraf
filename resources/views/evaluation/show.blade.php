@extends('layouts.app')

@section('title', 'Evaluate ' . $category->name . ' - SPUP')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg animate-fade-in">
                <div class="card-header text-white py-4" style="background: linear-gradient(135deg, var(--spup-primary) 0%, var(--spup-dark) 100%);">
                    <div class="text-center">
                        <i class="{{ $category->icon ?? 'bi bi-clipboard-check' }} display-6"></i>
                        <h3 class="mt-2 mb-0">{{ $category->name }}</h3>
                        <p class="mb-0 opacity-75">{{ $category->description }}</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('evaluation.store', $category->id) }}">
                        @csrf

                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Rating Scale:</strong> 
                            1 = Poor, 2 = Fair, 3 = Good, 4 = Very Good, 5 = Excellent
                        </div>

                        @foreach($category->criteria as $index => $criteria)
                            <div class="evaluation-item mb-4 p-3 rounded" style="background: #f8f9fa;">
                                <label class="form-label fw-bold">
                                    {{ $index + 1 }}. {{ $criteria->question }}
                                </label>
                                
                                <div class="rating-container d-flex gap-3 flex-wrap mt-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                   name="rating_{{ $criteria->id }}" 
                                                   id="rating_{{ $criteria->id }}_{{ $i }}" 
                                                   value="{{ $i }}" 
                                                   {{ old("rating_{$criteria->id}") == $i ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label" for="rating_{{ $criteria->id }}_{{ $i }}">
                                                <span class="badge {{ $i <= 2 ? 'bg-danger' : ($i == 3 ? 'bg-warning' : 'bg-success') }}">
                                                    {{ $i }}
                                                </span>
                                                @if($i == 1) Poor
                                                @elseif($i == 2) Fair
                                                @elseif($i == 3) Good
                                                @elseif($i == 4) Very Good
                                                @else Excellent
                                                @endif
                                            </label>
                                        </div>
                                    @endfor
                                </div>

                                <div class="mt-2">
                                    <input type="text" class="form-control form-control-sm" 
                                           name="comment_{{ $criteria->id }}" 
                                           placeholder="Optional comment..." 
                                           value="{{ old("comment_{$criteria->id}") }}">
                                </div>
                            </div>
                        @endforeach

                        <div class="mb-4">
                            <label for="overall_comment" class="form-label fw-bold">
                                <i class="bi bi-chat-dots me-2"></i>Overall Comments & Suggestions
                            </label>
                            <textarea class="form-control" id="overall_comment" name="overall_comment" 
                                      rows="4" placeholder="Share your overall experience and suggestions for improvement...">{{ old('overall_comment') }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-spup btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Submit Evaluation
                            </button>
                            <a href="{{ $category->type === 'standard' ? route('standards') : route('offices') }}" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Categories
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-input:checked {
        background-color: var(--spup-primary);
        border-color: var(--spup-primary);
    }
    .evaluation-item {
        transition: all 0.3s ease;
    }
    .evaluation-item:hover {
        background: #e9ecef !important;
    }
</style>
@endpush
