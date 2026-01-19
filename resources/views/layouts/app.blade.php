<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPUP Evaluation System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --spup-primary: #800000;
            --spup-secondary: #FFD700;
            --spup-dark: #4a0000;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, var(--spup-primary) 0%, var(--spup-dark) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--spup-secondary) !important;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--spup-secondary) !important;
        }

        .btn-spup {
            background: linear-gradient(135deg, var(--spup-primary) 0%, var(--spup-dark) 100%);
            color: #fff;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-spup:hover {
            background: linear-gradient(135deg, var(--spup-dark) 0%, var(--spup-primary) 100%);
            color: var(--spup-secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(128, 0, 0, 0.3);
        }

        .btn-outline-spup {
            border: 2px solid var(--spup-primary);
            color: var(--spup-primary);
            background: transparent;
        }

        .btn-outline-spup:hover {
            background: var(--spup-primary);
            color: #fff;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--spup-primary) 0%, var(--spup-dark) 100%);
            color: #fff;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        footer {
            background: linear-gradient(135deg, var(--spup-dark) 0%, #2a0000 100%);
            color: #fff;
            padding: 40px 0 20px;
        }

        .footer-link {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--spup-secondary);
        }

        .category-card {
            cursor: pointer;
            border-radius: 20px;
            overflow: hidden;
        }

        .category-card .card-body {
            padding: 30px;
        }

        .category-icon {
            font-size: 3rem;
            color: var(--spup-primary);
            margin-bottom: 15px;
        }

        .rating-stars {
            color: var(--spup-secondary);
        }

        .alert-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: #fff;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease forwards;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>SPUP Evaluation
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('standards*') ? 'active' : '' }}" href="{{ route('standards') }}">
                            <i class="bi bi-award me-1"></i>Standards
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('offices*') ? 'active' : '' }}" href="{{ route('offices') }}">
                            <i class="bi bi-building me-1"></i>Offices
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="bi bi-envelope me-1"></i>Contact
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i>Admin Panel
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="text-warning mb-3">
                        <i class="bi bi-mortarboard-fill me-2"></i>SPUP Evaluation System
                    </h5>
                    <p class="text-light small">
                        St. Paul University Philippines Integrated Evaluation and Feedback System - 
                        Committed to continuous improvement through valuable feedback.
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="text-warning mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                        <li><a href="{{ route('standards') }}" class="footer-link">Standards</a></li>
                        <li><a href="{{ route('offices') }}" class="footer-link">Offices</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="text-warning mb-3">Contact Information</h6>
                    <p class="small mb-1"><i class="bi bi-geo-alt me-2"></i>Tuguegarao City, Cagayan</p>
                    <p class="small mb-1"><i class="bi bi-envelope me-2"></i>info@spup.edu.ph</p>
                    <p class="small mb-1"><i class="bi bi-telephone me-2"></i>(078) 844-1872</p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center">
                <p class="small mb-0">&copy; {{ date('Y') }} St. Paul University Philippines. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
