<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PautangPro')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #10182b;
            --sidebar-bg-light: #1a2340;
            --brand-green: #1f9d5a;
        }
        body { background-color: #f4f6f9; }

        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: #e8eaf0;
            overflow-y: auto;
            z-index: 1040;
            transition: transform .3s ease;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 1.25rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-header img { max-width: 64px; margin-bottom: .5rem; }
        .sidebar-header .brand-name { font-weight: 700; font-size: 1.15rem; color: #fff; }
        .sidebar-header .brand-tagline { font-size: .7rem; color: #8b93a7; line-height: 1.3; margin-top: .25rem; }

        .sidebar-profile {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar-profile img { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; }
        .sidebar-profile .name { font-size: .9rem; font-weight: 600; color: #fff; }

        .sidebar-nav { flex: 1; padding: .75rem 0; }
        .sidebar-nav .nav-link {
            color: #b7bdcc;
            padding: .65rem 1.25rem;
            font-size: .9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link:hover { background: var(--sidebar-bg-light); color: #fff; }
        .sidebar-nav .nav-link.active {
            background: var(--sidebar-bg-light);
            color: #fff;
            border-left-color: var(--brand-green);
            font-weight: 600;
        }
        .sidebar-nav .nav-section-label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7284;
            padding: .75rem 1.25rem .25rem;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-footer form button {
            width: 100%;
        }

        .sidebar-backdrop { display: none; }

        .main-content { transition: margin-left .3s ease; }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: .75rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .topbar .hamburger-btn {
            background: none;
            border: none;
            font-size: 1.4rem;
            line-height: 1;
            color: #10182b;
        }

        @media (min-width: 992px) {
            .sidebar { transform: translateX(0); }
            .main-content { margin-left: var(--sidebar-width); }
            body.sidebar-toggled .sidebar { transform: translateX(-100%); }
            body.sidebar-toggled .main-content { margin-left: 0; }
        }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            body.sidebar-toggled .sidebar { transform: translateX(0); }
            body.sidebar-toggled .sidebar-backdrop {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.5);
                z-index: 1035;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @auth
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <nav class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="PautangPro logo">
                <div class="brand-name">PautangPro</div>
                <div class="brand-tagline">Simple Loans. Secure Transactions.<br>Better Management.</div>
            </div>

            <div class="sidebar-profile">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}">
                <div>
                    <div class="name">{{ auth()->user()->name }}</div>
                    <span class="badge bg-{{ auth()->user()->isAdmin() ? 'success' : 'secondary' }}">
                        {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                    </span>
                </div>
            </div>

            <div class="sidebar-nav">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.loans.index') }}" class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">
                        Loan Management
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        Collections
                    </a>
                @else
                    <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                        My Loans
                    </a>
                    <a href="{{ route('payment-schedule.index') }}" class="nav-link {{ request()->routeIs('payment-schedule.*') ? 'active' : '' }}">
                        Payment Schedule
                    </a>
                @endif

                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    My Profile
                </a>

                <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <span>Notifications</span>
                    @php $unread = auth()->user()->appNotifications()->where('is_read', false)->count(); @endphp
                    @if ($unread > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unread }}</span>
                    @endif
                </a>

                @if (auth()->user()->isAdmin())
                    <div class="nav-section-label">Administration</div>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        User Management
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        Reports
                    </a>
                    <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                        Audit Logs
                    </a>
                    <a href="{{ route('admin.security-events.index') }}" class="nav-link {{ request()->routeIs('admin.security-events.*') ? 'active' : '' }}">
                        Security Events
                    </a>
                @endif
            </div>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
                </form>
            </div>
        </nav>
    @endauth

    <div class="main-content">
        @auth
            <div class="topbar">
                <button class="hamburger-btn" id="sidebarToggle" type="button" aria-label="Toggle sidebar">☰</button>
                <span class="fw-semibold text-muted">@yield('title', 'PautangPro')</span>
            </div>
        @endauth

        <main class="container-fluid py-4 px-4">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => document.body.classList.toggle('sidebar-toggled'));
        }
        if (backdrop) {
            backdrop.addEventListener('click', () => document.body.classList.remove('sidebar-toggled'));
        }
    </script>
    @stack('scripts')
</body>
</html>
