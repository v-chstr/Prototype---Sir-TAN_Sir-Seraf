@extends('layouts.app')

@section('title', 'Home - SPUP Evaluation System')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-fade-in">
                <h1 class="display-4 fw-bold mb-4">SPUP Customer Feedback & Evaluation System</h1>
                <p class="lead mb-4">Your feedback matters! Help us improve our services and academic excellence through meaningful evaluations.</p>
                <div class="d-flex gap-3 flex-wrap">
                    @auth
                        <a href="{{ route('standards') }}" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-clipboard-check me-2"></i>Start Evaluation
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-person-plus me-2"></i>Register Now
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center">
                <i class="bi bi-clipboard-data" style="font-size: 15rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">What You Can Evaluate</h2>
            <p class="text-muted">Choose from our comprehensive evaluation categories</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 category-card">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <h3 class="card-title">Standards</h3>
                        <p class="card-text text-muted">Evaluate administrative leadership, learning environment, and campus facilities</p>
                        <ul class="list-unstyled text-start mt-3">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Administration Leaders</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Learning Environment</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Facilities</li>
                        </ul>
                        <a href="{{ route('standards') }}" class="btn btn-spup mt-3">
                            <i class="bi bi-arrow-right me-2"></i>Evaluate Standards
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 category-card">
                    <div class="card-body text-center">
                        <div class="category-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <h3 class="card-title">Offices</h3>
                        <p class="card-text text-muted">Rate the quality of service from various university offices</p>
                        <ul class="list-unstyled text-start mt-3">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Healthcare Services</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>ICT Services</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Canteen, Registrar, OSA</li>
                        </ul>
                        <a href="{{ route('offices') }}" class="btn btn-spup mt-3">
                            <i class="bi bi-arrow-right me-2"></i>Evaluate Offices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How It Works</h2>
            <p class="text-muted">Simple steps to provide your valuable feedback</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <span class="h3 mb-0" style="color: var(--spup-primary);">1</span>
                    </div>
                    <h5>Register/Login</h5>
                    <p class="text-muted small">Create an account or login with your credentials</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <span class="h3 mb-0" style="color: var(--spup-primary);">2</span>
                    </div>
                    <h5>Choose Category</h5>
                    <p class="text-muted small">Select Standards or Offices to evaluate</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <span class="h3 mb-0" style="color: var(--spup-primary);">3</span>
                    </div>
                    <h5>Rate & Comment</h5>
                    <p class="text-muted small">Provide your ratings and optional comments</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <span class="h3 mb-0" style="color: var(--spup-primary);">4</span>
                    </div>
                    <h5>Submit</h5>
                    <p class="text-muted small">Your feedback helps us improve!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, var(--spup-primary) 0%, var(--spup-dark) 100%);">
    <div class="container text-center text-white">
        <h2 class="fw-bold mb-3">Ready to Make a Difference?</h2>
        <p class="lead mb-4">Your feedback is valuable in shaping a better learning experience for everyone.</p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-5">
                <i class="bi bi-person-plus me-2"></i>Get Started Today
            </a>
        @else
            <a href="{{ route('standards') }}" class="btn btn-warning btn-lg px-5">
                <i class="bi bi-clipboard-check me-2"></i>Start Evaluating
            </a>
        @endguest
    </div>
</section>
@endsection
