@extends('layouts.app')

@section('title', 'Thank You - SPUP Evaluation System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg text-center animate-fade-in">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill display-1 text-success"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Thank You!</h2>
                    <p class="text-muted mb-4">
                        Your evaluation has been successfully submitted. 
                        We greatly appreciate your feedback as it helps us improve our services and academic excellence.
                    </p>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('home') }}" class="btn btn-spup">
                            <i class="bi bi-house me-2"></i>Back to Home
                        </a>
                        <a href="{{ route('standards') }}" class="btn btn-outline-spup">
                            <i class="bi bi-clipboard-check me-2"></i>Submit Another Evaluation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
