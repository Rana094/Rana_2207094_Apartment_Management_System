@php
    // Auto-detect role from path if not explicitly provided
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
            $role = 'resident'; // Default fallback
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
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — Nestora</title>

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
                <svg class="logo-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M2.25 9l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 9M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Nestora<span>.</span>
            </a>
        </div>

        <!-- Sidebar Navigation Menu links -->
        <ul class="db-sidebar-menu">
            @if($role === 'resident')
                <!-- RESIDENT MENU -->
                <li class="db-menu-label">Resident Hub</li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident') }}" class="db-menu-link {{ Request::is('resident') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/flat') }}" class="db-menu-link {{ Request::is('resident/flat*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18M18.75 3v18M9.75 7.5h.008v.008H9.75V7.5zm0 3.75h.008v.008H9.75v-.008zm0 3.75h.008v.008H9.75v-.008zm3.75-7.5h.008v.008h-.008V7.5zm0 3.75h.008v.008h-.008v-.008zm0 3.75h.008v.008h-.008v-.008zm3.75-7.5h.008v.008H17.25V7.5zm0 3.75h.008v.008H17.25v-.008zm0 3.75h.008v.008H17.25v-.008z" />
                        </svg>
                        My Flat
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/bills') }}" class="db-menu-link {{ Request::is('resident/bills*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 00 2.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5Z" />
                        </svg>
                        Bills & Payments
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/complaints') }}" class="db-menu-link {{ Request::is('resident/complaints*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        Maintenance Complaints
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/visitors') }}" class="db-menu-link {{ Request::is('resident/visitors*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                        Visitor Requests
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/bookings') }}" class="db-menu-link {{ Request::is('resident/bookings*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                        Facility Booking
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/polls') }}" class="db-menu-link {{ Request::is('resident/polls*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h3.75a2.25 2.25 0 0 1 2.25 2.25v15a2.25 2.25 0 0 1-2.25 2.25h-3.75a2.25 2.25 0 0 1-2.25-2.25v-15a2.25 2.25 0 0 1 2.25-2.25Z" />
                        </svg>
                        Polls & Voting
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/emergency') }}" class="db-menu-link {{ Request::is('resident/emergency*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="color: var(--color-emergency);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.68-.69-1.89-.69-2.58 0L5.04 18.6a2.29 2.29 0 0 0 0 3.24c.9.9 2.34.9 3.24 0l2.76-2.76c.68-.69.68-1.89 0-2.58Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.14 11.94c.24-.3.49-.62.74-.95a5.3 5.3 0 0 0-7.85-6.84L9.14 7.04c-.3.25-.63.5-.95.74m10.95 4.16a2.29 2.29 0 0 1 0 3.24l-2.76 2.76c-.69.68-1.89.68-2.58 0L10.94 15m8.2-3.06a5.3 5.3 0 0 0-7.85-6.84L9.14 7.04" />
                        </svg>
                        Emergency Request
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/documents') }}" class="db-menu-link {{ Request::is('resident/documents*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        My Documents
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/resident/move-out') }}" class="db-menu-link {{ Request::is('resident/move-out*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Move-Out Request
                    </a>
                </li>
                <li class="db-menu-item" style="border-top: 1px solid #1e293b; padding-top: 0.5rem; margin-top: 0.5rem;">
                    <a href="{{ url('/resident/profile') }}" class="db-menu-link {{ Request::is('resident/profile*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Profile
                    </a>
                </li>

            @elseif($role === 'manager')
                <!-- MANAGER MENU -->
                <li class="db-menu-label">Management Hub</li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager') }}" class="db-menu-link {{ Request::is('manager') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/approvals') }}" class="db-menu-link {{ Request::is('manager/approvals*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Resident Approvals
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/residents') }}" class="db-menu-link {{ Request::is('manager/residents*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 18H9.75c-2.028 0-3.957-.424-5.707-1.184A4.125 4.125 0 017.29 11.533V9.75a4.5 4.5 0 119 0V11.53c0 .878.232 1.704.64 2.42z" />
                        </svg>
                        Residents
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/flats') }}" class="db-menu-link {{ Request::is('manager/flats*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18" />
                        </svg>
                        Flats
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/records') }}" class="db-menu-link {{ Request::is('manager/records*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.33l-7.5-5-7.5 5V21h15z" />
                        </svg>
                        Owner/Tenant Records
                    </a>
                </li>
                
                <li class="db-menu-label">Finances</li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/billing') }}" class="db-menu-link {{ Request::is('manager/billing*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.251.11a3.378 3.378 0 004.5-1.085 4.89 4.89 0 00-.007-6.194 3.378 3.378 0 00-4.507-1.038l-.25.11M18 8.117a8.961 8.961 0 010 7.766M6 8.117a8.961 8.961 0 000 7.766" />
                        </svg>
                        Billing
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/payments') }}" class="db-menu-link {{ Request::is('manager/payments*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                        </svg>
                        Payments
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/reports') }}" class="db-menu-link {{ Request::is('manager/reports*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                        </svg>
                        Financial Reports
                    </a>
                </li>
                
                <li class="db-menu-label">Operations</li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/complaints') }}" class="db-menu-link {{ Request::is('manager/complaints*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.03V3m0 3a9 9 0 11-9 9 9 9 0 019-9z" />
                        </svg>
                        Complaints
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/work-orders') }}" class="db-menu-link {{ Request::is('manager/work-orders*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l5.877 5.877A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l-4.64-4.64-.535-.535a2.197 2.197 0 113.107-3.107l.535.535 4.64 4.64m-3.107 3.107l3.107-3.107M2 17.25a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0z" />
                        </svg>
                        Work Orders
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/staff') }}" class="db-menu-link {{ Request::is('manager/staff*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.97 5.97 0 00-.75-2.906m-.179-1.973a5 5 0 00-3.328-4.47m-.023-.01a4.247 4.247 0 001.137-3.203a4.25 4.25 0 00-6.505-3.475m-.022.012a4.247 4.247 0 00-1.138 3.203a4.25 4.25 0 005.662 5.072m.002.012a4.25 4.25 0 00-.002-8.25" />
                        </svg>
                        Staff Management
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/visitors') }}" class="db-menu-link {{ Request::is('manager/visitors*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 18H9.75c-2.028 0-3.957-.424-5.707-1.184A4.125 4.125 0 017.29 11.533V9.75a4.5 4.5 0 119 0V11.53c0 .878.232 1.704.64 2.42" />
                        </svg>
                        Visitor Management
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/bookings') }}" class="db-menu-link {{ Request::is('manager/bookings*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        Facility Bookings
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/polls') }}" class="db-menu-link {{ Request::is('manager/polls*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h3.75a2.25 2.25 0 0 1 2.25 2.25v15a2.25 2.25 0 0 1-2.25 2.25h-3.75a2.25 2.25 0 0 1-2.25-2.25v-15a2.25 2.25 0 0 1 2.25-2.25Z" />
                        </svg>
                        Polls & Voting
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/emergency') }}" class="db-menu-link {{ Request::is('manager/emergency*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.68-.69-1.89-.69-2.58 0L5.04 18.6a2.29 2.29 0 0 0 0 3.24c.9.9 2.34.9 3.24 0l2.76-2.76c.68-.69.68-1.89 0-2.58Z" />
                        </svg>
                        Emergency Requests
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/documents') }}" class="db-menu-link {{ Request::is('manager/documents*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Documents
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/manager/notices') }}" class="db-menu-link {{ Request::is('manager/notices*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a9.04 9.04 0 01-2.857 1.825m0 0a9.04 9.04 0 01-2.857-1.825m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                        </svg>
                        Notices
                    </a>
                </li>
                <li class="db-menu-item" style="border-top: 1px solid #1e293b; padding-top: 0.5rem; margin-top: 0.5rem;">
                    <a href="{{ url('/manager/settings') }}" class="db-menu-link {{ Request::is('manager/settings*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869l.214-1.28z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                </li>

            @elseif($role === 'security')
                <!-- SECURITY GUARD MENU -->
                <li class="db-menu-label">Security Desk</li>
                <li class="db-menu-item">
                    <a href="{{ url('/security') }}" class="db-menu-link {{ Request::is('security') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l3 3m0 0l6-6M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/security/check-in') }}" class="db-menu-link {{ Request::is('security/check-in*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 00 2.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Visitor Check-In
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/security/check-out') }}" class="db-menu-link {{ Request::is('security/check-out*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 00 2.25-2.25V15m3 0l3-3m0 0l-3-3" />
                        </svg>
                        Visitor Check-Out
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/security/logs') }}" class="db-menu-link {{ Request::is('security/logs*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        Visitor Logs
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/security/alerts') }}" class="db-menu-link {{ Request::is('security/alerts*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="color: var(--color-emergency);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a9.04 9.04 0 01-2.857 1.825m0 0a9.04 9.04 0 01-2.857-1.825m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                        </svg>
                        Emergency Alerts
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/security/incidents') }}" class="db-menu-link {{ Request::is('security/incidents*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.03V3m0 3a9 9 0 11-9 9 9 9 0 019-9zm0 12.75h.008v.008H12v-.008z" />
                        </svg>
                        Security Incidents
                    </a>
                </li>
                <li class="db-menu-item" style="border-top: 1px solid #1e293b; padding-top: 0.5rem; margin-top: 0.5rem;">
                    <a href="{{ url('/security/profile') }}" class="db-menu-link {{ Request::is('security/profile*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Profile
                    </a>
                </li>

            @elseif($role === 'staff')
                <!-- MAINTENANCE STAFF MENU -->
                <li class="db-menu-label">Technician Desk</li>
                <li class="db-menu-item">
                    <a href="{{ url('/maintenance') }}" class="db-menu-link {{ Request::is('maintenance') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/maintenance/work-orders') }}" class="db-menu-link {{ Request::is('maintenance/work-orders*') && !Request::is('maintenance/work-orders/in-progress') && !Request::is('maintenance/work-orders/completed') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l5.877 5.877A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l-4.64-4.64-.535-.535a2.197 2.197 0 113.107-3.107l.535.535 4.64 4.64m-3.107 3.107l3.107-3.107M2 17.25a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0z" />
                        </svg>
                        Assigned Work Orders
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/maintenance/work-orders/in-progress') }}" class="db-menu-link {{ Request::is('maintenance/work-orders/in-progress') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="color: var(--color-in-progress);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        In Progress
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/maintenance/work-orders/completed') }}" class="db-menu-link {{ Request::is('maintenance/work-orders/completed') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="color: var(--color-completed);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Completed Work
                    </a>
                </li>
                <li class="db-menu-item">
                    <a href="{{ url('/maintenance/notes') }}" class="db-menu-link {{ Request::is('maintenance/notes*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        Repair Notes
                    </a>
                </li>
                <li class="db-menu-item" style="border-top: 1px solid #1e293b; padding-top: 0.5rem; margin-top: 0.5rem;">
                    <a href="{{ url('/maintenance/profile') }}" class="db-menu-link {{ Request::is('maintenance/profile*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Profile
                    </a>
                </li>
            @endif
        </ul>

        <!-- Sidebar Footer / Logout option -->
        <div class="db-sidebar-footer">
            <div class="db-sidebar-user">
                <div class="db-sidebar-avatar">
                    {{ strtoupper(substr($role, 0, 2)) }}
                </div>
                <div style="overflow: hidden; flex-grow: 1;">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #ffffff; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                        @if($role === 'resident') John Doe @elseif($role === 'manager') Admin Manager @elseif($role === 'security') Officer Kabir @else Technician Ali @endif
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b;">
                        {{ $roleName }}
                    </div>
                </div>
                <!-- Mini Log out link -->
                <a href="{{ url('/login') }}" title="Sign Out" style="color: #64748b; display: flex; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 00 2.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </a>
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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                    </svg>
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
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a9.04 9.04 0 01-2.857 1.825m0 0a9.04 9.04 0 01-2.857-1.825m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                        </svg>
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
                        <a href="#" style="text-align: center; display: block; padding: 0.75rem; font-size: 0.8rem; font-weight: 600;">View All Notifications</a>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown-wrapper">
                    <button class="dropdown-trigger" id="profile-dropdown-btn" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.5rem; border-radius: var(--radius-md);">
                        <div class="db-sidebar-avatar" style="width: 1.75rem; height: 1.75rem; font-size: 0.75rem;">
                            {{ strtoupper(substr($role, 0, 2)) }}
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <!-- Mock Profile Menu -->
                    <div class="dropdown-menu" id="profile-dropdown-menu">
                        <div class="dropdown-header" style="padding-bottom: 0.25rem;">Logged in as</div>
                        <div style="font-size: 0.8rem; padding: 0 1rem 0.5rem 1rem; font-weight: 600; color: var(--text-secondary); border-bottom: 1px solid var(--border-color);">
                            {{ $role === 'resident' ? 'resident@nestora.com' : ($role === 'manager' ? 'manager@nestora.com' : ($role === 'security' ? 'security@nestora.com' : 'staff@nestora.com')) }}
                        </div>
                        <a href="{{ url($role . '/profile') }}" class="dropdown-item">My Profile Settings</a>
                        <a href="{{ url('/') }}" class="dropdown-item">View Public Site</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('/login') }}" class="dropdown-item" style="color: var(--color-rejected);">Log Out</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="db-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
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

        // 3. Reusable Confirmation Modal Helper
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
