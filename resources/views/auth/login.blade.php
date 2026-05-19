@extends('layouts.app')

@section('title', 'Login - SPUP Evaluation System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg animate-fade-in">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle display-4" style="color: var(--spup-primary);"></i>
                        <h3 class="mt-3 fw-bold">Welcome Back</h3>
                        <p class="text-muted">Login to your account</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="Enter your email" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Enter your password" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-spup w-100 py-2 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>

                        <p class="text-center text-muted mb-0">
                            Don't have an account? <a href="{{ route('register') }}" style="color: var(--spup-primary);">Register here</a>
                        </p>
                    </form>

                    @if(config('app.env') !== 'production')
                    <hr class="my-3">
                    <p class="text-center text-muted small mb-2">Dev Quick Login</p>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="devLogin('chester.tambis.admin@gmail.com', 'password')">
                            Admin
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="devLogin('chester.tambis.user@gmail.com', 'password')">
                            User
                        </button>
                    </div>
                    <script>
                        function devLogin(email, pass) {
                            document.getElementById('email').value = email;
                            document.getElementById('password').value = pass;
                            document.getElementById('email').focus();
                        }
                    </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // If this page is restored from the browser's back-forward cache after
    // the user has logged in, force a reload so the server can redirect
    // them away from the login screen.
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.getEntriesByType('navigation')[0]?.type === 'back_forward')) {
            window.location.reload();
        }
    });
</script>
@endsection
