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
            --spup-primary: #198754;
            --spup-secondary: #FFD700;
            --spup-dark: #0d5c36;
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
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            box-shadow: none;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .stat-card .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .chart-container {
            background: #fff;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .table-container {
            background: #fff;
            border-radius: 4px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 2px;
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

        /* Pagination */
        .pagination-wrapper {
            padding-top: 16px;
            border-top: 1px solid #e8e8e8;
            margin-top: 16px;
        }

        .pagination-wrapper nav {
            width: 100%;
        }

        /* Bootstrap-5 paginator inner containers */
        .pagination-wrapper nav > div {
            width: 100%;
        }

        .pagination-wrapper .pagination {
            margin-bottom: 0;
        }

        /* "Showing X to Y" text */
        .pagination-wrapper nav p {
            font-size: 0.82rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        .pagination .page-link {
            border: 1px solid #e8e8e8;
            color: #444;
            font-size: 0.82rem;
            padding: 5px 11px;
            border-radius: 2px !important;
            margin: 0 1px;
            background: #fff;
            transition: background 0.15s, color 0.15s;
            box-shadow: none !important;
        }

        .pagination .page-link:hover {
            background: #f0f0f0;
            border-color: #d0d0d0;
            color: #222;
        }

        .pagination .page-item.active .page-link {
            background: var(--spup-primary);
            border-color: var(--spup-primary);
            color: #fff;
        }

        .pagination .page-item.disabled .page-link {
            background: #fafafa;
            border-color: #e8e8e8;
            color: #bbb;
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
                <button type="button" class="btn btn-sm btn-outline-success me-3" data-bs-toggle="modal" data-bs-target="#userGuideModal">
                    <i class="bi bi-question-circle me-1"></i>User Guide
                </button>
                <span class="me-3"><i class="bi bi-person-circle me-1"></i>Administrator</span>
                <span class="badge bg-success">Admin</span>
            </div>
        </div>

        <!-- User Guide Modal -->
        <div class="modal fade" id="userGuideModal" tabindex="-1" aria-labelledby="userGuideModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content" style="border-radius:4px;">
                    <div class="modal-header border-bottom" style="background:#198754;color:#fff;">
                        <h5 class="modal-title fw-semibold" id="userGuideModalLabel">
                            <i class="bi bi-book me-2"></i>SPUP Evaluation System &mdash; User Guide
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">A step-by-step guide for both <strong>end users</strong> (respondents) and <strong>administrators</strong> on how to operate the evaluation and feedback system.</p>

                        <!-- Section: For End Users -->
                        <h6 class="fw-semibold text-success mt-3 mb-2">For End Users (Students, Employees, Guests, Parents/Guardians)</h6>
                        <p class="small text-muted mb-2">This section explains how respondents use the public side of the system to submit evaluations and feedback.</p>

                        <p class="small fw-semibold mb-1">A. Registration &amp; Login</p>
                        <ol class="small mb-3">
                            <li>Visit the homepage and click <strong>Register</strong> in the top-right corner.</li>
                            <li>Select a role: <em>Student</em>, <em>Employee</em>, <em>Guest</em>, or <em>Parent/Guardian</em>.</li>
                            <li>Fill in the required information. Students must also provide <em>Gender</em>, <em>Department</em>, <em>Course</em>, <em>Year Level</em>, and <em>Section</em>.</li>
                            <li>Submit the form to create the account, then sign in via the <strong>Login</strong> page.</li>
                        </ol>

                        <p class="small fw-semibold mb-1">B. Submitting an Evaluation</p>
                        <ol class="small mb-3">
                            <li>After logging in, choose either <strong>Standards Evaluation</strong> (Administration Leaders, Learning Environment, Facilities) or <strong>Offices Evaluation</strong> (Healthcare, ICT, Canteen, Registrar, OSA) from the homepage.</li>
                            <li>Select the specific <em>Category</em> you want to evaluate.</li>
                            <li>Read each criterion and rate it using the rating scale (1 = lowest, 5 = highest).</li>
                            <li>Optionally, leave a <em>Comment</em> for additional feedback.</li>
                            <li>Click <strong>Submit Evaluation</strong>. You'll see a confirmation message once it's saved.</li>
                            <li>You can submit evaluations for multiple categories &mdash; each submission is recorded separately.</li>
                        </ol>

                        <p class="small fw-semibold mb-1">C. Sending a Contact Message</p>
                        <ol class="small mb-3">
                            <li>Open the <strong>Contact</strong> page from the public site (no login required).</li>
                            <li>Fill in your name, email, subject, and message.</li>
                            <li>Click <strong>Send Message</strong>. Admins will receive it in the Messages section and reply via email.</li>
                        </ol>

                        <p class="small fw-semibold mb-1">D. Privacy Notice</p>
                        <ul class="small mb-3">
                            <li>All user identities are <strong>anonymized</strong> on the admin side &mdash; administrators cannot see real names.</li>
                            <li>Responses are stored confidentially and used only for institutional improvement.</li>
                        </ul>

                        <hr class="my-3">

                        <h6 class="fw-semibold text-success mt-3 mb-2">For Administrators</h6>

                        <!-- Section 1 -->
                        <h6 class="fw-semibold text-success mt-3 mb-2">1. Dashboard Overview</h6>
                        <ol class="small mb-3">
                            <li>Open the <strong>Dashboard</strong> from the sidebar to view total evaluations, users, messages, and average ratings.</li>
                            <li>Review the <strong>Monthly Evaluations</strong> chart to track submission trends across the year.</li>
                            <li>The <strong>Recent Evaluations</strong> table shows the latest submissions with anonymized respondent identifiers.</li>
                        </ol>

                        <!-- Section 2 -->
                        <h6 class="fw-semibold text-success mt-3 mb-2">2. Managing Evaluations</h6>
                        <ol class="small mb-3">
                            <li>Click <strong>Evaluations</strong> in the sidebar to view all submitted evaluations.</li>
                            <li>Use the filters at the top to narrow results by <em>Category</em>, <em>Role</em>, or <em>Date Range</em>.</li>
                            <li>When <strong>Student</strong> is selected as the role, additional filters appear: <em>Gender</em>, <em>Department</em>, <em>Course</em>, and <em>Year Level</em>.</li>
                            <li>The <strong>Course</strong> dropdown only activates after a <em>Department</em> is selected.</li>
                            <li>Click the <i class="bi bi-eye"></i> <strong>View</strong> icon to see the full evaluation details, including criteria responses and comments.</li>
                            <li>Click <strong>Export Data</strong> to download the filtered evaluations as an Excel file.</li>
                        </ol>

                        <!-- Section 3 -->
                        <h6 class="fw-semibold text-success mt-3 mb-2">3. Managing Users</h6>
                        <ol class="small mb-3">
                            <li>Open <strong>Users</strong> from the sidebar to view all registered accounts.</li>
                            <li>User identities are anonymized on the frontend (e.g. <code>User-A7F3C2</code>) for confidentiality.</li>
                            <li>Filter by role to see only students, employees, guests, or parents/guardians.</li>
                        </ol>

                        <!-- Section 4 -->
                        <h6 class="fw-semibold text-success mt-3 mb-2">4. Managing Categories</h6>
                        <ol class="small mb-3">
                            <li>Open <strong>Categories</strong> from the sidebar to manage <em>Standards</em> and <em>Offices</em> evaluation categories.</li>
                            <li>Click <strong>+ Add Category</strong> to create a new evaluation category and add its criteria/questions.</li>
                            <li>Use the <i class="bi bi-pencil"></i> <strong>Edit</strong> button to update a category and its questions.</li>
                            <li>Use the <strong>toggle</strong> button to activate or deactivate a category &mdash; deactivated categories won't appear to respondents.</li>
                            <li>Use the <i class="bi bi-trash"></i> <strong>Delete</strong> button to permanently remove a category. Categories with existing evaluations cannot be deleted.</li>
                        </ol>

                        <!-- Section 5 -->
                        <h6 class="fw-semibold text-success mt-3 mb-2">5. Messages &amp; Feedback</h6>
                        <ol class="small mb-3">
                            <li>Open <strong>Messages</strong> from the sidebar to read contact messages submitted through the website.</li>
                            <li>Click a message to view its full content and reply via email.</li>
                            <li>Sender identities are anonymized on the list view.</li>
                        </ol>

                        <!-- Section 6 -->
                        <h6 class="fw-semibold text-success mt-3 mb-2">6. Generating Reports</h6>
                        <ol class="small mb-3">
                            <li>Click <strong>Generate Reports</strong> in the sidebar to create a custom report.</li>
                            <li>Select a <em>Category</em>, <em>Date Range</em>, and other filters, then click <strong>Generate</strong>.</li>
                            <li>The result page shows aggregated ratings, criteria averages, and a detailed table of evaluations.</li>
                            <li>Click <strong>Summary Report</strong> for an overall snapshot across all categories.</li>
                            <li>Use the <strong>Export</strong> button on any report to download it as an Excel file.</li>
                        </ol>

                        <!-- Section 7 -->
                        <h6 class="fw-semibold text-success mt-3 mb-2">7. Navigation Tips</h6>
                        <ul class="small mb-3">
                            <li><strong>Back to Site</strong> &mdash; returns to the public-facing website.</li>
                            <li><strong>Logout</strong> &mdash; safely ends your admin session.</li>
                            <li>All sensitive user data is anonymized for privacy &mdash; real names are never displayed on this panel.</li>
                        </ul>

                        <div class="alert alert-success small mb-0" style="border-radius:4px;">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Tip:</strong> You can reopen this guide anytime by clicking the <strong>User Guide</strong> button in the top-right corner.
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-sm btn-success" data-bs-dismiss="modal">Got it</button>
                    </div>
                </div>
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
    <script>
        // If the browser restores this page from bfcache (back/forward button after logout),
        // force a real request so the auth middleware can redirect unauthenticated users.
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
