@php
    // Auto-detect role from path if not explicitly provided by the controller/view.
    if (!isset($role)) {
        if (Request::is('resident*')) {
            $role = 'resident';
        } elseif (Request::is('manager*')) {
            $role = 'manager';
        } elseif (Request::is('security*')) {
            $role = 'security';
        } elseif (Request::is('maintenance*')) {
            $role = 'staff';
        } else {
            $role = 'resident'; // Default fallback for shared preview/legacy pages.
        }
    }

    // Define role-specific human-readable titles & badges
    $roleName = 'Resident';
    $badgeClass = 'badge-approved';
    if ($role === 'manager') {
        $roleName = 'Building Manager';
        $badgeClass = 'badge-pending-verification';
    } elseif ($role === 'security') {
        $roleName = 'Gate Security';
        $badgeClass = 'badge-pending';
    } elseif ($role === 'staff') {
        $roleName = 'Maintenance Staff';
        $badgeClass = 'badge-in-progress';
    }

    $authUser = auth()->user();
    $displayName = $authUser?->name ?? $roleName;
    $displayEmail = $authUser?->email ?? '';
    $initials = collect(preg_split('/\s+/', trim($displayName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Nestora</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- Assets (Custom Vanilla CSS in public folder) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="db-wrapper">
    <!-- 1. SIDEBAR NAVIGATION -->
    <aside class="db-sidebar" id="dashboard-sidebar">
        <!-- Sidebar Header -->
        <div class="db-sidebar-header">
            <a href="{{ url('/') }}" class="db-sidebar-logo">
                Nestora<span>.</span>
            </a>
        </div>

        <!-- Sidebar Navigation Menu links -->
        <ul class="db-sidebar-menu">
            @if($role === 'resident')
                {{-- These route links map directly to the resident route group in routes/web.php. --}}
                <!-- RESIDENT MENU -->
                <li class="db-menu-label">Resident Hub</li>
                <li class="db-menu-item">
                    <a href="{{ route('resident.dashboard') }}" class="db-menu-link {{ Request::routeIs('resident.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/flat') }}" class="db-menu-link {{ Request::is('resident/flat*') ? 'active' : '' }}">
                        My Flat
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/bills') }}" class="db-menu-link {{ Request::is('resident/bills*') ? 'active' : '' }}">
                        Bills & Payments
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/complaints') }}" class="db-menu-link {{ Request::is('resident/complaints*') ? 'active' : '' }}">
                        Maintenance Complaints
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/visitors') }}" class="db-menu-link {{ Request::is('resident/visitors*') ? 'active' : '' }}">
                        Visitor Requests
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/bookings') }}" class="db-menu-link {{ Request::is('resident/bookings*') ? 'active' : '' }}">
                        Facility Booking
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/emergency') }}" class="db-menu-link {{ Request::is('resident/emergency*') ? 'active' : '' }}">
                        Emergency Request
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/documents') }}" class="db-menu-link {{ Request::is('resident/documents*') ? 'active' : '' }}">
                        My Documents
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/move-out') }}" class="db-menu-link {{ Request::is('resident/move-out*') ? 'active' : '' }}">
                        Move-Out Request
                    </a>
                </li>
                <li class="db-menu-item" style="border-top: 1px solid #1e293b; padding-top: 0.5rem; margin-top: 0.5rem;">
                    <a href="{{ url('/resident/profile') }}" class="db-menu-link {{ Request::is('resident/profile*') ? 'active' : '' }}">
                        Profile
                    </a>
                </li>

            @elseif($role === 'manager')
                {{-- Manager menu links are protected by role:manager middleware. --}}
                <!-- MANAGER MENU -->
                <li class="db-menu-label">Management Hub</li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.dashboard') }}" class="db-menu-link {{ Request::routeIs('manager.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.resident-approvals.index') }}" class="db-menu-link {{ Request::routeIs('manager.resident-approvals.*') ? 'active' : '' }}">
                        Resident Approvals
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.residents.index') }}" class="db-menu-link {{ Request::routeIs('manager.residents.*') ? 'active' : '' }}">
                        Residents
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.flats.index') }}" class="db-menu-link {{ Request::routeIs('manager.flats.*') ? 'active' : '' }}">
                        Flats
                    </a>
                </li>
                <li class="db-menu-label">Finances</li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.bills.index') }}" class="db-menu-link {{ Request::routeIs('manager.bills.*') ? 'active' : '' }}">
                        Billing
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.payments.index') }}" class="db-menu-link {{ Request::routeIs('manager.payments.*') ? 'active' : '' }}">
                        Payments
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.reports.financial') }}" class="db-menu-link {{ Request::routeIs('manager.reports.*') ? 'active' : '' }}">
                        Financial Reports
                    </a>
                </li>
                
                <li class="db-menu-label">Operations</li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.complaints.index') }}" class="db-menu-link {{ Request::routeIs('manager.complaints.*') ? 'active' : '' }}">
                        Complaints &amp; Work Orders
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.staff') }}" class="db-menu-link {{ Request::routeIs('manager.staff*') ? 'active' : '' }}">
                        Staff Management
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.bookings.index') }}" class="db-menu-link {{ Request::routeIs('manager.bookings.*') ? 'active' : '' }}">
                        Facility Bookings
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.emergencies.index') }}" class="db-menu-link {{ Request::routeIs('manager.emergencies.*') ? 'active' : '' }}">
                        Emergency Requests
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.documents.index') }}" class="db-menu-link {{ Request::routeIs('manager.documents.*') ? 'active' : '' }}">
                        Registration Documents
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('manager.notices.index') }}" class="db-menu-link {{ Request::routeIs('manager.notices.*') ? 'active' : '' }}">
                        Notices
                    </a>
                </li>
            @elseif($role === 'security')
                {{-- Security menu links are protected by role:security middleware. --}}
                <!-- SECURITY GUARD MENU -->
                <li class="db-menu-label">Security Desk</li>
                <li class="db-menu-item">
                    <a href="{{ route('security.dashboard') }}" class="db-menu-link {{ Request::routeIs('security.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('security.checkin') }}" class="db-menu-link {{ Request::routeIs('security.checkin*') ? 'active' : '' }}">
                        Visitor Check-In
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('security.checkout') }}" class="db-menu-link {{ Request::routeIs('security.checkout*') ? 'active' : '' }}">
                        Visitor Check-Out
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('security.logs') }}" class="db-menu-link {{ Request::routeIs('security.logs') ? 'active' : '' }}">
                        Visitor Logs
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('security.emergency') }}" class="db-menu-link {{ Request::routeIs('security.emergency*') ? 'active' : '' }}">
                        Emergency Alerts
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('security.incidents') }}" class="db-menu-link {{ Request::routeIs('security.incidents*') ? 'active' : '' }}">
                        Security Incidents
                    </a>
                </li>
            @elseif($role === 'staff')
                {{-- Maintenance menu links are protected by role:staff middleware. --}}
                <!-- MAINTENANCE STAFF MENU -->
                <li class="db-menu-label">Technician Desk</li>
                <li class="db-menu-item">
                    <a href="{{ route('maintenance.dashboard') }}" class="db-menu-link {{ Request::routeIs('maintenance.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('maintenance.work-orders') }}" class="db-menu-link {{ Request::routeIs('maintenance.work-orders') ? 'active' : '' }}">
                        Assigned Work Orders
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('maintenance.work-orders.in-progress') }}" class="db-menu-link {{ Request::routeIs('maintenance.work-orders.in-progress') ? 'active' : '' }}">
                        In Progress
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('maintenance.work-orders.completed') }}" class="db-menu-link {{ Request::routeIs('maintenance.work-orders.completed') ? 'active' : '' }}">
                        Completed Work
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ route('maintenance.notes') }}" class="db-menu-link {{ Request::routeIs('maintenance.notes') ? 'active' : '' }}">
                        Repair Notes
                    </a>
                </li>
                <li class="db-menu-item" style="border-top: 1px solid #1e293b; padding-top: 0.5rem; margin-top: 0.5rem;">
                    <a href="{{ route('maintenance.profile') }}" class="db-menu-link {{ Request::routeIs('maintenance.profile*') ? 'active' : '' }}">
                        Profile
                    </a>
                </li>
            @endif
        </ul>

        <!-- Sidebar Footer / Logout option -->
        <div class="db-sidebar-footer">
            <div class="db-sidebar-user">
                <div class="db-sidebar-avatar">
                    {{ $initials ?: strtoupper(substr($role, 0, 2)) }}
                </div>
                <div style="overflow: hidden; flex-grow: 1;">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #ffffff; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                        {{ $displayName }}
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b;">
                        {{ $roleName }}
                    </div>
                </div>
                <!-- Mini Log out link -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Sign Out" style="color: #64748b; display: flex; background: none; border: 0; padding: 0; cursor: pointer;">
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- 2. MAIN CONTENT AREA -->
    <div class="db-main">
        <!-- Top Navbar -->
        <header class="db-navbar">
            <div class="db-nav-left">
                <!-- Hamburger menu for mobile -->
                <button class="db-sidebar-toggle" id="sidebar-toggle-btn" aria-label="Toggle Navigation Sidebar">
                </button>
                
                <!-- Portal Badge -->
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span class="badge {{ $badgeClass }}" style="font-size: 0.8rem; padding: 0.3rem 0.875rem;">
                        {{ $roleName }} Portal
                    </span>
                </div>
            </div>

            <div class="db-nav-right">
                <!-- Notifications Dropdown -->
                <div class="dropdown-wrapper">
                    <button class="dropdown-trigger" id="notif-dropdown-btn" aria-label="View notifications">
                        <x-icon name="notification" alt="" size="1.5rem" />
                        <span class="badge-dot"></span>
                    </button>
                    <!-- Mock Notifications Menu -->
                    <div class="dropdown-menu notification-menu" id="notif-dropdown-menu">
                        <div class="dropdown-header">Recent Alerts & Notifications</div>
                        <div class="dropdown-item" style="border-bottom: 1px solid var(--border-color); flex-direction: column; align-items: flex-start; gap: 0.25rem;">
                            <div style="display: flex; justify-content: space-between; width: 100%; font-weight: 600; font-size: 0.8rem;">
                                <span style="color: var(--color-emergency);">[EMERGENCY ALERT]</span>
                                <span style="color: var(--text-muted); font-weight: normal;">Just now</span>
                            </div>
                            <p style="font-size: 0.775rem; margin-bottom: 0; line-height: 1.4; color: var(--text-secondary);">Water main maintenance has been scheduled for tomorrow 10:00 AM.</p>
                        </div>
                        <div class="dropdown-item" style="border-bottom: 1px solid var(--border-color); flex-direction: column; align-items: flex-start; gap: 0.25rem;">
                            <div style="display: flex; justify-content: space-between; width: 100%; font-weight: 600; font-size: 0.8rem;">
                                <span>Visitor Checked In</span>
                                <span style="color: var(--text-muted); font-weight: normal;">1 hr ago</span>
                            </div>
                            <p style="font-size: 0.775rem; margin-bottom: 0; line-height: 1.4; color: var(--text-secondary);">Your guest "Ahmad Sufian" has checked in at Main Gate.</p>
                        </div>
                        {{-- Full notification data comes from NotificationController@index. --}}
                        <a href="{{ route('notifications.index') }}" style="text-align: center; display: block; padding: 0.75rem; font-size: 0.8rem; font-weight: 600;">View All Notifications</a>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown-wrapper">
                    <button class="dropdown-trigger profile-trigger" id="profile-dropdown-btn" aria-label="Open profile menu">
                        <x-icon name="profile" alt="" size="1.5rem" class="profile-trigger-icon" />
                    </button>
                    <!-- Mock Profile Menu -->
                    <div class="dropdown-menu" id="profile-dropdown-menu">
                        <div class="dropdown-header" style="padding-bottom: 0.25rem;">Logged in as</div>
                        <div style="font-size: 0.8rem; padding: 0 1rem 0.5rem 1rem; font-weight: 600; color: var(--text-secondary); border-bottom: 1px solid var(--border-color);">
                            {{ $displayEmail }}
                        </div>
                        <a href="{{ $role === 'resident' ? route('resident.profile') : ($role === 'staff' ? route('maintenance.profile') : route($role.'.dashboard')) }}" class="dropdown-item">My Portal</a>
                        <a href="{{ url('/') }}" class="dropdown-item">Public Home</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width: 100%; border: 0; background: none; color: var(--color-rejected); cursor: pointer;">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="db-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Reusable Modals (Confirmation modal markup) -->
<div class="modal-overlay" id="confirm-modal-overlay">
    <div class="modal-container">
        <h2 class="modal-title" id="confirm-modal-title">Are you sure?</h2>
        <p id="confirm-modal-desc" style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.5rem;">
            This action cannot be undone. Please confirm you want to proceed.
        </p>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline btn-sm" id="confirm-modal-cancel">Cancel</button>
            <button type="button" class="btn btn-danger btn-sm" id="confirm-modal-action">Confirm</button>
        </div>
    </div>
</div>

<!-- Reusable JS Scripting for Dashboard layout toggles -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Mobile Sidebar Toggle
        const toggleBtn = document.getElementById('sidebar-toggle-btn');
        const sidebar = document.getElementById('dashboard-sidebar');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('active');
            });
            // Close sidebar when clicking on main body (mobile view)
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    if (sidebar.classList.contains('active') && !sidebar.contains(e.target) && e.target !== toggleBtn) {
                        sidebar.classList.remove('active');
                    }
                }
            });
        }

        // 2. Dropdown Menus Toggles
        function setupDropdown(triggerId, menuId) {
            const trigger = document.getElementById(triggerId);
            const menu = document.getElementById(menuId);

            if (trigger && menu) {
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Close other dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.remove('active');
                    });
                    menu.classList.toggle('active');
                });
            }
        }

        setupDropdown('notif-dropdown-btn', 'notif-dropdown-menu');
        setupDropdown('profile-dropdown-btn', 'profile-dropdown-menu');

        // Close dropdowns on window click
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                m.classList.remove('active');
            });
        });

        // 3. Reusable Confirmation Modal Helper used by pages before submitting destructive backend actions.
        window.showConfirmModal = function(title, desc, confirmCallback, isDanger = true) {
            const overlay = document.getElementById('confirm-modal-overlay');
            const titleElem = document.getElementById('confirm-modal-title');
            const descElem = document.getElementById('confirm-modal-desc');
            const cancelBtn = document.getElementById('confirm-modal-cancel');
            const actionBtn = document.getElementById('confirm-modal-action');

            if (overlay && titleElem && descElem && cancelBtn && actionBtn) {
                titleElem.textContent = title;
                descElem.textContent = desc;
                
                // Color customization for confirm button
                if (isDanger) {
                    actionBtn.className = 'btn btn-danger btn-sm';
                } else {
                    actionBtn.className = 'btn btn-primary btn-sm';
                }

                overlay.classList.add('active');

                // Cleanup previous events
                const newActionBtn = actionBtn.cloneNode(true);
                actionBtn.parentNode.replaceChild(newActionBtn, actionBtn);
                
                newActionBtn.addEventListener('click', function() {
                    overlay.classList.remove('active');
                    if (confirmCallback) confirmCallback();
                });

                cancelBtn.onclick = function() {
                    overlay.classList.remove('active');
                };
                overlay.onclick = function(e) {
                    if (e.target === overlay) {
                        overlay.classList.remove('active');
                    }
                };
            }
        };
    });
</script>
</body>
</html>
