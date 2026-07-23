<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Nestora') — Smart Apartment Management Made Simple</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- Custom Vanilla CSS Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
    @php
        $publicUser = auth()->user();
        $portalUrl = $publicUser
            ? ($publicUser->isApproved()
                ? route($publicUser->dashboardRouteName())
                : route('approval.pending'))
            : null;
    @endphp

    <!-- Public Header / Navbar -->
    <header class="pub-header">
        <div class="container pub-nav">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="logo">
                Nestora<span>.</span>
            </a>

            <!-- Nav Links -->
            <ul class="nav-links">
                <li>
                    <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                </li>
                <li>
                    <a href="{{ url('/about') }}" class="nav-link {{ Request::is('about') ? 'active' : '' }}">About</a>
                </li>
                <li>
                    <a href="{{ url('/contact') }}" class="nav-link {{ Request::is('contact') ? 'active' : '' }}">Contact</a>
                </li>
            </ul>

            <!-- Actions / Auth Button -->
            <div class="nav-actions">
                @auth
                    <a href="{{ $portalUrl }}" class="btn btn-primary btn-sm">My Portal</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline-flex;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Log Out</button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="btn btn-outline btn-sm">Log In</a>
                    <a href="{{ url('/register') }}" class="btn btn-primary btn-sm">Resident Sign Up</a>
                @endauth
                
                <!-- Mobile Navigation Toggle Button -->
                <button class="mobile-toggle" id="mobile-toggle-btn" aria-label="Toggle Navigation Menu">
                </button>
            </div>
        </div>
        
        <!-- Mobile Dropdown Navigation Menu -->
        <div id="mobile-menu" class="mobile-menu" style="display: none; background-color: var(--bg-header); border-top: 1px solid var(--border-color); padding: 1rem 1.5rem; position: absolute; width: 100%; left: 0; box-shadow: var(--shadow-md);">
            <ul class="flex flex-col gap-3" style="list-style: none;">
                <li><a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ url('/about') }}" class="nav-link {{ Request::is('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ url('/contact') }}" class="nav-link {{ Request::is('contact') ? 'active' : '' }}">Contact</a></li>
                <li style="border-top: 1px solid var(--border-color); padding-top: 0.5rem; display: flex; gap: 0.5rem;">
                    @auth
                        <a href="{{ $portalUrl }}" class="btn btn-primary btn-sm" style="flex: 1;">My Portal</a>
                        <form method="POST" action="{{ route('logout') }}" style="flex: 1; display: flex;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm" style="width: 100%;">Log Out</button>
                        </form>
                    @else
                        <a href="{{ url('/login') }}" class="btn btn-outline btn-sm" style="flex: 1;">Log In</a>
                        <a href="{{ url('/register') }}" class="btn btn-primary btn-sm" style="flex: 1;">Sign Up</a>
                    @endauth
                </li>
            </ul>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="pub-footer">
        <div class="container">
            <div class="pub-footer-grid">
                <div>
                    <h4 class="logo" style="color: #ffffff; font-size: 1.5rem; margin-bottom: 1rem;">
                        Nestora<span>.</span>
                    </h4>
                    <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1rem;">
                        Nestora is a comprehensive smart apartment and society management platform designed to simplify daily operations, improve communication, and secure access for residents and staff alike.
                    </p>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Features</h4>
                    <ul>
                        <li><a href="#">Billing & Payments</a></li>
                        <li><a href="#">Visitor Management</a></li>
                        <li><a href="#">Maintenance Orders</a></li>
                        <li><a href="#">Facility Booking</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Contact Info</h4>
                    <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <strong>Address:</strong> 12/A, Dhanmondi, Dhaka, Bangladesh
                    </p>
                    <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <strong>Email:</strong> support@nestora.com
                    </p>
                    <p style="color: #94a3b8; font-size: 0.9rem;">
                        <strong>Phone:</strong> +880 1234 567890
                    </p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p style="font-size: 0.85rem; margin-bottom: 0;">&copy; {{ date('Y') }} Nestora. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" style="font-size: 0.85rem; color: #94a3b8;">Privacy Policy</a>
                    <a href="#" style="font-size: 0.85rem; color: #94a3b8;">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive script for mobile menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('mobile-toggle-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            if (toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', function() {
                    if (mobileMenu.style.display === 'none') {
                        mobileMenu.style.display = 'block';
                    } else {
                        mobileMenu.style.display = 'none';
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
