<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
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

        /* DARK THEME VARIABLES */
        [data-bs-theme="dark"] {
            --bg-gradient: radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.12) 0px, transparent 50%),
                           radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.1) 0px, transparent 50%),
                           linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #090d16 100%);
            --topbar-bg: rgba(15, 23, 42, 0.75);
            --topbar-border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --dash-card-bg: rgba(30, 41, 59, 0.75);
            --dash-card-border: rgba(255, 255, 255, 0.12);
            --dash-card-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            --table-border: rgba(255, 255, 255, 0.06);
            --hamburger-color: #ffffff;
        }

        /* LIGHT THEME VARIABLES */
        [data-bs-theme="light"] {
            --bg-gradient: #f4f6f9;
            --topbar-bg: #ffffff;
            --topbar-border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --dash-card-bg: #ffffff;
            --dash-card-border: #e2e8f0;
            --dash-card-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            --table-border: #f1f5f9;
            --hamburger-color: #0f172a;
        }

        body { 
            background: var(--bg-gradient) !important;
            background-attachment: fixed !important;
            color: var(--text-main);
            min-height: 100vh;
            transition: background 0.3s ease, color 0.3s ease;
        }

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
        .sidebar-header .brand-tagline { font-size: .75rem; color: #94a3b8; font-weight: 500; margin-top: .25rem; }

        /* Sleek & Professional Profile Badge Area */
        .sidebar-profile {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: .85rem;
        }
        .sidebar-profile img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.1); }
        .sidebar-profile .name { font-size: .88rem; font-weight: 600; color: #fff; line-height: 1.2; margin-bottom: 3px; }
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 12px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .role-badge.admin {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.25);
        }
        .role-badge .status-dot {
            width: 5px;
            height: 5px;
            background-color: #34d399;
            border-radius: 50%;
            box-shadow: 0 0 6px #34d399;
        }

        .sidebar-nav { flex: 1; padding: .75rem 0; }
        .sidebar-nav .nav-link {
            color: #b7bdcc;
            padding: .65rem 1.25rem;
            font-size: .9rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
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
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        /* Neumorphic Switcher Styling */
        .theme-switch-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.25);
            padding: 6px 12px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .theme-switch-label { font-size: 0.75rem; font-weight: 600; color: #94a3b8; }
        
        .custom-theme-switch {
            width: 58px; height: 28px;
            background: #cbd5e1;
            border-radius: 20px;
            position: relative;
            cursor: pointer;
            padding: 3px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }
        [data-bs-theme="dark"] .custom-theme-switch {
            background: #334155;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        .custom-theme-switch .switch-handle {
            width: 22px; height: 22px;
            background: #ffffff;
            border-radius: 50%;
            position: absolute;
            top: 3px; left: 3px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2;
        }
        [data-bs-theme="dark"] .custom-theme-switch .switch-handle { transform: translateX(30px); }
        .custom-theme-switch .icon-sun, .custom-theme-switch .icon-moon { width: 14px; height: 14px; z-index: 1; }
        .custom-theme-switch .icon-sun { margin-left: 4px; color: #f59e0b; }
        .custom-theme-switch .icon-moon { margin-right: 4px; color: #64748b; }
        [data-bs-theme="dark"] .custom-theme-switch .icon-moon { color: #818cf8; }

        .btn-logout-custom {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
            transition: all 0.2s ease;
        }
        .btn-logout-custom:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
        }

        .sidebar-backdrop { display: none; }
        .main-content { transition: margin-left .3s ease; }

        .topbar {
            background: var(--topbar-bg);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--topbar-border);
            padding: .85rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.3s ease, border-color 0.3s ease;
        }
        .topbar .hamburger-btn {
            background: none; border: none;
            font-size: 1.4rem; line-height: 1;
            color: var(--hamburger-color);
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
                display: block; position: fixed; inset: 0;
                background: rgba(0,0,0,.5); z-index: 1035;
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
                <div class="brand-tagline">Your Loan, Our Priority</div>
            </div>

            <div class="sidebar-profile">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}">
                <div>
                    <div class="name">{{ auth()->user()->name }}</div>
                    <div class="role-badge {{ auth()->user()->isAdmin() ? 'admin' : '' }}">
                        <span class="status-dot"></span>
                        {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                    </div>
                </div>
            </div>

            <div class="sidebar-nav">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Dashboard</span>
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.loans.index') }}" class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <span>Loan Management</span>
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <span>Collections</span>
                    </a>
                @else
                    <a href="{{ route('loans.index') }}" class="nav-link {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        <span>My Loans</span>
                    </a>
                    <a href="{{ route('payment-schedule.index') }}" class="nav-link {{ request()->routeIs('payment-schedule.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span>Payment Schedule</span>
                    </a>
                @endif

                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>My Profile</span>
                </a>

                <a href="{{ route('notifications.index') }}" class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span>Notifications</span>
                    </div>
                    @php $unread = auth()->user()->appNotifications()->where('is_read', false)->count(); @endphp
                    @if ($unread > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unread }}</span>
                    @endif
                </a>

                @if (auth()->user()->isAdmin())
                    <div class="nav-section-label">Administration</div>
                    
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Borrower Management</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        <span>Reports</span>
                    </a>

                    <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <span>Audit Logs</span>
                    </a>

                    <a href="{{ route('admin.security-events.index') }}" class="nav-link {{ request()->routeIs('admin.security-events.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>Security Events</span>
                    </a>

                    <a href="{{ route('admin.settings.penalties') }}" class="nav-link {{ request()->routeIs('admin.settings.penalties*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        <span>Penalty Management</span>
                    </a>

                    <!-- Pinalitan mula 'System Settings' patungong 'Settings' -->
                    <a href="{{ route('admin.settings.system') }}" class="nav-link {{ request()->routeIs('admin.settings.system*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span>Settings</span>
                    </a>
                @endif
            </div>

            <div class="sidebar-footer">
                <div class="theme-switch-container">
                    <span class="theme-switch-label" id="themeModeText">Dark Mode</span>
                    <div class="custom-theme-switch" id="themeToggleSwitch">
                        <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <div class="switch-handle" id="switchHandle"></div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                </form>
                <!-- Logout button na may icon at pinagandang styling -->
                <button type="button" class="btn btn-logout-custom btn-sm w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </button>
            </div>
        </nav>

        <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to log out of your account?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="logout-form" class="btn btn-danger">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <div class="main-content">
        @auth
            <div class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="hamburger-btn" id="sidebarToggle" type="button" aria-label="Toggle sidebar">☰</button>
                    <span class="fw-semibold">@yield('title', 'PautangPro')</span>
                </div>
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

        const themeToggleSwitch = document.getElementById('themeToggleSwitch');
        const themeModeText = document.getElementById('themeModeText');
        const switchHandle = document.getElementById('switchHandle');
        const htmlElement = document.documentElement;

        function updateHandleIcon(theme) {
            if (theme === 'light') {
                switchHandle.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>`;
            } else {
                switchHandle.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>`;
            }
        }

        function applyTheme(theme) {
            htmlElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('pautangpro_theme', theme);
            if (themeModeText) {
                themeModeText.textContent = theme === 'light' ? 'Light Mode' : 'Dark Mode';
            }
            updateHandleIcon(theme);
        }

        const savedTheme = localStorage.getItem('pautangpro_theme') || 'dark';
        applyTheme(savedTheme);

        if (themeToggleSwitch) {
            themeToggleSwitch.addEventListener('click', () => {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                applyTheme(newTheme);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>