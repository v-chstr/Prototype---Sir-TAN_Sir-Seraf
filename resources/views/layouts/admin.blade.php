<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - SPUP Evaluation</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --spup-primary: #800000;
            --spup-secondary: #FFD700;
            --spup-dark: #4a0000;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--spup-primary) 0%, var(--spup-dark) 100%);
            padding-top: 20px;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar-brand h4 {
            color: var(--spup-secondary);
            margin: 0;
            font-weight: bold;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.7);
        }

        .nav-section {
            padding: 10px 20px;
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: var(--spup-secondary);
        }

        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: var(--spup-secondary);
            border-left-color: var(--spup-secondary);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-wrapper {
            padding: 30px;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .chart-container {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .table-container {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .btn-spup {
            background: linear-gradient(135deg, var(--spup-primary) 0%, var(--spup-dark) 100%);
            color: #fff;
            border: none;
        }

        .btn-spup:hover {
            background: linear-gradient(135deg, var(--spup-dark) 0%, var(--spup-primary) 100%);
            color: var(--spup-secondary);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-mortarboard-fill me-2"></i>SPUP</h4>
            <small>Admin Panel</small>
        </div>

        <div class="nav-section">Main</div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </nav>

        <div class="nav-section">Management</div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.evaluations*') ? 'active' : '' }}" href="{{ route('admin.evaluations') }}">
                <i class="bi bi-clipboard-data"></i> Evaluations
            </a>
            <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                <i class="bi bi-people"></i> Users
            </a>
            <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                <i class="bi bi-tags"></i> Categories
            </a>
            <a class="nav-link {{ request()->routeIs('admin.messages*') ? 'active' : '' }}" href="{{ route('admin.messages') }}">
                <i class="bi bi-envelope"></i> Messages
                @php
                    $unreadCount = \App\Models\ContactMessage::unread()->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge bg-danger ms-auto">{{ $unreadCount }}</span>
                @endif
            </a>
        </nav>

        <div class="nav-section">Reports</div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Generate Reports
            </a>
            <a class="nav-link {{ request()->routeIs('admin.summary*') ? 'active' : '' }}" href="{{ route('admin.summary') }}">
                <i class="bi bi-journal-text"></i> Summary Report
            </a>
        </nav>

        <div class="nav-section">Navigation</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="bi bi-house"></i> Back to Site
            </a>
            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-navbar">
            <div>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</span>
                <span class="badge bg-success">Admin</span>
            </div>
        </div>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
