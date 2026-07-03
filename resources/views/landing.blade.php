@extends('layouts.public')

@section('title', 'Nestora — Smart Apartment Management Made Simple')

@section('content')
<style>
    /* Unique Landing Styles */
    .hero-btn-group {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 3.5rem;
    }
    .section-title h2 {
        font-size: 2.25rem;
        margin-bottom: 0.75rem;
        position: relative;
        display: inline-block;
    }
    .section-title h2::after {
        content: '';
        display: block;
        width: 3rem;
        height: 4px;
        background-color: var(--primary-color);
        margin: 0.75rem auto 0 auto;
        border-radius: var(--radius-full);
    }
    .section-title p {
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* Features Grid */
    .feature-card {
        text-align: left;
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 2rem;
        transition: var(--transition-normal);
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-light);
    }
    .feature-icon-wrapper {
        background-color: var(--primary-light);
        color: var(--primary-color);
        width: 3.25rem;
        height: 3.25rem;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }
    .feature-icon-wrapper svg {
        width: 1.75rem;
        height: 1.75rem;
    }
    .feature-card h3 {
        font-size: 1.2rem;
        margin-bottom: 0.75rem;
        font-weight: 700;
    }
    .feature-card p {
        font-size: 0.925rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 0;
        flex-grow: 1;
    }
    
    /* Roles Section */
    .roles-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 992px) {
        .roles-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    .role-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: var(--transition-normal);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .role-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background-color: var(--text-muted);
    }
    .role-card.role-resident::before { background-color: var(--primary-color); }
    .role-card.role-manager::before { background-color: var(--secondary-color); }
    .role-card.role-security::before { background-color: #f59e0b; }
    .role-card.role-maintenance::before { background-color: #8b5cf6; }

    .role-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .role-name {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .role-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }
    .role-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .role-list li {
        font-size: 0.875rem;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .role-list li svg {
        width: 1rem;
        height: 1rem;
        color: var(--secondary-color);
        flex-shrink: 0;
    }
    
    /* Operations Section */
    .ops-section {
        background-color: #ffffff;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }
    .ops-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 3rem;
        align-items: center;
    }
    @media (min-width: 768px) {
        .ops-row {
            grid-template-columns: 1fr 1fr;
        }
    }
    .ops-badge {
        display: inline-block;
        background-color: var(--secondary-light);
        color: var(--secondary-color);
        padding: 0.35rem 0.85rem;
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: var(--radius-full);
        margin-bottom: 1rem;
        text-transform: uppercase;
    }
    .ops-features {
        list-style: none;
        margin-top: 1.5rem;
    }
    .ops-features li {
        margin-bottom: 1rem;
        display: flex;
        gap: 0.75rem;
        font-size: 1rem;
        color: var(--text-secondary);
    }
    .ops-features li svg {
        width: 1.25rem;
        height: 1.25rem;
        color: var(--primary-color);
        margin-top: 0.15rem;
        flex-shrink: 0;
    }
    
    /* CTA Block */
    .cta-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #0d9488 100%);
        border-radius: var(--radius-lg);
        color: #ffffff;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: var(--shadow-lg);
    }
    .cta-banner h2 {
        color: #ffffff;
        font-size: 2.25rem;
        margin-bottom: 1rem;
    }
    .cta-banner p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.15rem;
        max-width: 600px;
        margin: 0 auto 2rem auto;
    }
</style>

<!-- 1. Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <h1 class="font-bold">Smart Apartment Management <span style="color: var(--primary-color);">Made Simple</span></h1>
        <p class="lead">Nestora streamlines your apartment operations, secures your community premises, simplifies payments, and optimizes maintenance tasks — all from a single modern dashboard.</p>
        <div class="hero-btn-group">
            <a href="{{ url('/register') }}" class="btn btn-primary btn-lg">Register as Resident</a>
            <a href="{{ url('/login') }}" class="btn btn-outline btn-lg" style="background: #ffffff;">Access Portal</a>
        </div>
    </div>
</section>

<!-- 2. Feature Cards Section -->
<section class="section-padding bg-soft">
    <div class="container">
        <div class="section-title">
            <h2>Key Operations & Features</h2>
            <p>Every tool your apartment association and residents need to stay organized, connected, and operating efficiently.</p>
        </div>

        <div class="grid grid-3">
            <!-- 1. Resident Management -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.089 18H9.75c-2.028 0-3.957-.424-5.707-1.184A4.125 4.125 0 0 1 7.29 11.533V9.75a4.5 4.5 0 1 1 9 0V11.53c0 .878.232 1.704.64 2.42" />
                    </svg>
                </div>
                <h3>Resident Directory</h3>
                <p>Maintain accurate digital records of all residents, owners, and tenants. Enable managers to quickly approve new member signups.</p>
            </div>

            <!-- 2. Flat Management -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18" />
                    </svg>
                </div>
                <h3>Flat Management</h3>
                <p>Track flat availability, occupancy statuses (vacant or occupied), and link residents to specific flats for cleaner bookkeeping.</p>
            </div>

            <!-- 3. Visitor Tracking -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.746 3.746 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
                <h3>Visitor Tracking</h3>
                <p>Pre-approve visitors and track real-time security check-ins/check-outs at gates. Instantly notify residents upon arrival.</p>
            </div>

            <!-- 4. Billing & Payments -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                </div>
                <h3>Billing & Payments</h3>
                <p>Generate service charges, utility bills, and dues. Allow residents to upload payment receipts and check off accounts instantly.</p>
            </div>

            <!-- 5. Maintenance Orders -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l-4.64-4.64-.535-.535a2.197 2.197 0 1 1 3.107-3.107l.535.535 4.64 4.64m-3.107 3.107 3.107-3.107M2 17.25a2.25 2.25 0 1 1 4.5 0 2.25 2.25 0 0 1-4.5 0Z" />
                    </svg>
                </div>
                <h3>Maintenance Orders</h3>
                <p>File repair requests, assign work orders to internal staff, monitor resolution timelines, and verify repairs with completion proof.</p>
            </div>

            <!-- 6. Facility Booking -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                </div>
                <h3>Facility Booking</h3>
                <p>Coordinate reservations for shared spaces like the community hall, gym, pool, or rooftop garden, avoiding double-bookings.</p>
            </div>

            <!-- 7. Emergency Requests -->
            <div class="feature-card">
                <div class="feature-icon-wrapper" style="background-color: var(--bg-emergency); color: var(--color-emergency);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.68-.69-1.89-.69-2.58 0L5.04 18.6a2.29 2.29 0 0 0 0 3.24c.9.9 2.34.9 3.24 0l2.76-2.76c.68-.69.68-1.89 0-2.58Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.14 11.94c.24-.3.49-.62.74-.95a5.3 5.3 0 0 0-7.85-6.84L9.14 7.04c-.3.25-.63.5-.95.74m10.95 4.16a2.29 2.29 0 0 1 0 3.24l-2.76 2.76c-.69.68-1.89.68-2.58 0L10.94 15m8.2-3.06a5.3 5.3 0 0 0-7.85-6.84L9.14 7.04" />
                    </svg>
                </div>
                <h3>Emergency Alerts</h3>
                <p>Broadcast critical security, fire, or water supply alerts. Provide a one-click panic button for residents to notify the gate guard.</p>
            </div>

            <!-- 8. Document Management -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <h3>Document Directory</h3>
                <p>Safely upload, store, and review building rules, bylaws, lease agreements, NID copies, and verification documents.</p>
            </div>

            <!-- 9. Financial Reports -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                    </svg>
                </div>
                <h3>Financial Analytics</h3>
                <p>Deliver clear reports on monthly income, service charge collection rates, pending dues, and building expenses to the management board.</p>
            </div>

            <!-- 10. Polls & Voting -->
            <div class="feature-card" style="grid-column: span 1; justify-self: center; width: 100%;">
                <div class="feature-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h3.75a2.25 2.25 0 0 1 2.25 2.25v15a2.25 2.25 0 0 1-2.25 2.25h-3.75a2.25 2.25 0 0 1-2.25-2.25v-15a2.25 2.25 0 0 1 2.25-2.25Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6h6m-6 3h6m-6 3h6m-6 3h6" />
                    </svg>
                </div>
                <h3>Polls & Voting</h3>
                <p>Gather opinions and conduct votes on society decisions, building upgrades, or executive committee elections in a secure way.</p>
            </div>
        </div>
    </div>
</section>

<!-- 3. Role-based System Section -->
<section class="section-padding" style="background-color: var(--bg-main);">
    <div class="container">
        <div class="section-title">
            <h2>Designed for the Whole Community</h2>
            <p>Dedicated access portals with custom dashboards tailored to the specific needs of each role in the building.</p>
        </div>

        <div class="roles-grid">
            <!-- Resident Card -->
            <div class="role-card role-resident">
                <h3 class="role-name" style="color: var(--primary-color);">1. Resident</h3>
                <p class="role-desc">For homeowners and tenants living in the society.</p>
                <ul class="role-list">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Pay dues & upload proof
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Log support tickets
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Approve visitor access
                    </li>
                </ul>
            </div>

            <!-- Manager Card -->
            <div class="role-card role-manager">
                <h3 class="role-name" style="color: var(--secondary-color);">2. Building Manager</h3>
                <p class="role-desc">For housing board directors and facility administrators.</p>
                <ul class="role-list">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Approve user accounts
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Generate billing cycles
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Assign work tasks to staff
                    </li>
                </ul>
            </div>

            <!-- Security Guard Card -->
            <div class="role-card role-security">
                <h3 class="role-name" style="color: #d97706;">3. Security Guard</h3>
                <p class="role-desc">For security personnel at entrance gates.</p>
                <ul class="role-list">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Check-in/check-out logs
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Incident registration
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Trigger security alarms
                    </li>
                </ul>
            </div>

            <!-- Maintenance Staff Card -->
            <div class="role-card role-maintenance">
                <h3 class="role-name" style="color: #6d28d9;">4. Maintenance Staff</h3>
                <p class="role-desc">For technicians, electricians, plumbers, and cleaning crews.</p>
                <ul class="role-list">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Track assigned work
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Update progress notes
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Upload completion photos
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- 4. Apartment Operations Section -->
<section class="section-padding ops-section">
    <div class="container">
        <div class="section-title">
            <h2>Robust Apartment Operations</h2>
            <p>Experience hassle-free management through digitized core routines.</p>
        </div>

        <div class="ops-row">
            <!-- Column 1: Text & Features -->
            <div>
                <span class="ops-badge">Billing & Maintenance</span>
                <h3 style="font-size: 1.75rem; margin-bottom: 1rem;">Simple Invoicing and Quick Complaint Resolutions</h3>
                <p>Never lose track of monthly dues or run around finding a local electrician. Nestora links complaints directly to our skilled staff, and handles transparent bookkeeping for your peace of mind.</p>
                
                <ul class="ops-features">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span><strong>Automated Invoices:</strong> Receive your utility and maintenance bills at the start of each month.</span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span><strong>Direct Status Updates:</strong> Watch complaints move from "Pending" to "In Progress" and finally "Completed" in real-time.</span>
                    </li>
                </ul>
            </div>
            
            <!-- Column 2: Text & Features -->
            <div>
                <span class="ops-badge" style="background-color: #fee2e2; color: #dc2626;">Visitor & Gate Security</span>
                <h3 style="font-size: 1.75rem; margin-bottom: 1rem;">Keep Unapproved Strangers Out of Your Community</h3>
                <p>Improve perimeter safety. Security guards verify incoming visitors by searching code credentials created by residents beforehand, keeping logs tight and residents updated instantly.</p>
                
                <ul class="ops-features">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: #dc2626;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span><strong>Instant Notifications:</strong> Get a smartphone alert the second a guard registers a guest at the gate.</span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="color: #dc2626;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span><strong>Panic/Emergency Alert:</strong> Guards receive immediate sound alarms if a resident triggers a panic request.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- 5. Call to Action Banner -->
<section class="section-padding" style="background-color: var(--bg-main);">
    <div class="container">
        <div class="cta-banner">
            <h2>Ready to Transform Your Apartment Operations?</h2>
            <p>Join hundreds of modern societies using Nestora to build safer, more connected, and highly organized neighborhoods.</p>
            <div class="hero-btn-group">
                <a href="{{ url('/register') }}" class="btn btn-secondary btn-lg" style="background-color: #ffffff; color: var(--secondary-color);">Create Resident Account</a>
                <a href="{{ url('/login') }}" class="btn btn-outline btn-lg" style="color: #ffffff; border-color: rgba(255,255,255,0.4); background: rgba(255,255,255,0.12);">Sign In to Nestora</a>
            </div>
        </div>
    </div>
</section>
@endsection
