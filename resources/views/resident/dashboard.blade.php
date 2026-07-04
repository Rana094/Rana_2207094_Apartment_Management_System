@extends('layouts.dashboard')

@section('title', 'Resident Dashboard — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Welcome Back, John Doe</h1>
        <p class="db-subtitle">Here is a quick overview of your flat status, billing cycle, and recent notices.</p>
    </div>
    
    <!-- Emergency Panic Button -->
    <a href="{{ url('/resident/emergency') }}" class="btn btn-danger" style="background-color: var(--color-emergency); font-size: 0.9rem; animation: pulse 2s infinite; border: 1px solid #fda4af;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.68-.69-1.89-.69-2.58 0L5.04 18.6a2.29 2.29 0 0 0 0 3.24c.9.9 2.34.9 3.24 0l2.76-2.76c.68-.69.68-1.89 0-2.58Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.14 11.94c.24-.3.49-.62.74-.95a5.3 5.3 0 0 0-7.85-6.84L9.14 7.04c-.3.25-.63.5-.95.74m10.95 4.16a2.29 2.29 0 0 1 0 3.24l-2.76 2.76c-.69.68-1.89.68-2.58 0L10.94 15" />
        </svg>
        Trigger Emergency Request
    </a>
</div>

<!-- Key Stat Cards Grid -->
<div class="grid grid-4" style="margin-bottom: 2rem;">
    <!-- Assigned Flat Card -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Assigned Unit</span>
            <span class="stat-val" style="font-size: 1.5rem;">Flat 3B</span>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Building A, Tower 1</span>
        </div>
        <div class="stat-icon primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75" />
            </svg>
        </div>
    </div>

    <!-- Current Bill Card -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Current Bill</span>
            <span class="stat-val" style="font-size: 1.5rem;">৳ 4,500</span>
            <span style="font-size: 0.75rem; color: var(--color-rejected); font-weight: 600; margin-top: 0.25rem;">Due by: July 10, 2026</span>
        </div>
        <div class="stat-icon danger" style="background-color: var(--bg-rejected); color: var(--color-rejected);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6" />
            </svg>
        </div>
    </div>

    <!-- Bill Status Card -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Payment Status</span>
            <div style="margin-top: 0.5rem;">
                <span class="badge badge-unpaid">unpaid</span>
            </div>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">July Service Charge</span>
        </div>
        <div class="stat-icon warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068" />
            </svg>
        </div>
    </div>

    <!-- Open Complaints Card -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Open Complaints</span>
            <span class="stat-val" style="font-size: 1.5rem;">1</span>
            <span class="stat-trend" style="color: var(--color-in-progress);">
                <span class="badge badge-in-progress" style="font-size: 0.65rem; padding: 0.1rem 0.5rem;">in progress</span>
            </span>
        </div>
        <div class="stat-icon primary" style="background-color: var(--bg-in-progress); color: var(--color-in-progress);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.03V3m0 3" />
            </svg>
        </div>
    </div>
</div>

<!-- Main Layout Columns -->
<div class="grid grid-3" style="align-items: start;">
    <!-- Column 1 & 2: Main updates -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Active Facility Booking Widget -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem;">Upcoming Facility Bookings</h3>
                <a href="{{ url('/resident/bookings') }}" style="font-size: 0.85rem; font-weight: 600;">View All</a>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1.25rem; background-color: var(--bg-main); border: 1px dashed var(--border-color); border-radius: var(--radius-md); padding: 1.25rem;">
                <div class="stat-icon secondary" style="flex-shrink: 0; background-color: var(--secondary-light); color: var(--secondary-color);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25" />
                    </svg>
                </div>
                <div style="flex-grow: 1;">
                    <div style="font-weight: 700; font-size: 1rem; color: var(--text-primary);">Community Hall Booking</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.15rem;">
                        <strong>Date:</strong> July 12, 2026 | <strong>Time:</strong> 4:00 PM - 9:00 PM
                    </div>
                </div>
                <span class="badge badge-approved" style="flex-shrink: 0;">approved</span>
            </div>
        </div>

        <!-- Recent Visitor Requests Widget -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem;">Recent Visitor Passes</h3>
                <a href="{{ url('/resident/visitors') }}" class="btn btn-outline btn-sm">Create Visitor Pass</a>
            </div>
            
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Visitor Name</th>
                        <th>Pass Code</th>
                        <th>Scheduled Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600;">Farhan Alvi<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Courier / Delivery</div></td>
                        <td><code style="background-color: var(--bg-main); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm); font-weight: 700;">N-5509</code></td>
                        <td>July 5, 2026</td>
                        <td><span class="badge badge-approved">Approved</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Ahmad Sufian<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Guest / Personal</div></td>
                        <td><code style="background-color: var(--bg-main); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm); font-weight: 700;">N-2311</code></td>
                        <td>July 4, 2026</td>
                        <td><span class="badge badge-completed">checked out</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Column 3: Notices & Rules Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Notices Board Widget -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; color: var(--primary-color);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a9.04 9.04 0 0 1-2.857 1.825m0 0a9.04 9.04 0 0 1-2.857-1.825m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                Recent Notices
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <!-- Notice 1 -->
                <div style="border-left: 3px solid var(--color-pending); padding-left: 0.75rem;">
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-primary);">Generator Repairs Scheduled</div>
                    <p style="font-size: 0.775rem; color: var(--text-secondary); margin-bottom: 0.25rem; line-height: 1.4; margin-top: 0.15rem;">The standby building generator will undergo repairs. Elevators will run on battery backup.</p>
                    <span style="font-size: 0.7rem; color: var(--text-muted);">Posted on: July 3, 2026</span>
                </div>

                <!-- Notice 2 -->
                <div style="border-left: 3px solid var(--primary-color); padding-left: 0.75rem;">
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-primary);">Q3 General Committee Meeting</div>
                    <p style="font-size: 0.775rem; color: var(--text-secondary); margin-bottom: 0.25rem; line-height: 1.4; margin-top: 0.15rem;">Join the upcoming housing committee meeting in the community room to elect members.</p>
                    <span style="font-size: 0.7rem; color: var(--text-muted);">Posted on: June 28, 2026</span>
                </div>
            </div>
        </div>

        <!-- Quick Help Card -->
        <div class="card" style="background-color: var(--primary-light); border-color: rgba(79, 70, 229, 0.15); padding: 1.5rem;">
            <h3 style="font-size: 1.05rem; color: var(--primary-color); margin-bottom: 0.75rem;">Need Help?</h3>
            <p style="font-size: 0.825rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1rem;">
                For water leakages, elevator issues, or power issues, immediately file a maintenance complaint ticket. 
            </p>
            <a href="{{ url('/resident/complaints/create') }}" class="btn btn-primary btn-sm" style="width: 100%; justify-content: center;">
                File Complaint Ticket
            </a>
        </div>

    </div>
</div>
@endsection
