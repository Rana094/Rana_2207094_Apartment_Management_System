@extends('layouts.dashboard')

@section('title', 'Manager Dashboard — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Building Management Command Center</h1>
    <p class="db-subtitle">Quick overview of society occupancies, bill collections, maintenance rosters, and resident approvals.</p>
</div>

<!-- Operational Stat Cards Grid -->
<div class="grid grid-4" style="margin-bottom: 2rem;">
    <!-- Approved Residents -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Total Residents</span>
            <span class="stat-val" style="font-size: 1.6rem;">142 Members</span>
            <span style="font-size: 0.75rem; color: var(--color-approved); font-weight: 600; margin-top: 0.25rem;">+4 registered this week</span>
        </div>
        <div class="stat-icon primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.115a8.31 8.31 0 00-.733-2.232M8.25 10.5a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM1.575 18.622a.75.75 0 01.4-.952 9.355 9.355 0 018.525 0 .75.75 0 01.4.952c-.67 1.93-2.232 3.378-4.66 3.378s-3.99-1.448-4.66-3.378z" />
            </svg>
        </div>
    </div>

    <!-- Occupancy Ratio -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Occupancy Rate</span>
            <span class="stat-val" style="font-size: 1.6rem;">92.5%</span>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">111 of 120 Units Filled</span>
        </div>
        <div class="stat-icon secondary" style="background-color: var(--secondary-light); color: var(--secondary-color);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21" />
            </svg>
        </div>
    </div>

    <!-- Collections -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Monthly Collections</span>
            <span class="stat-val" style="font-size: 1.6rem;">৳ 4,80,000</span>
            <span style="font-size: 0.75rem; color: var(--color-approved); font-weight: 600; margin-top: 0.25rem;">82% target achieved</span>
        </div>
        <div class="stat-icon success" style="background-color: var(--bg-approved); color: var(--color-approved);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.265-.113a3.374 3.374 0 004.97-3.07C14.235 8.4 12 8.4 12 8.4V6m0 12h.008v.008H12V18z" />
            </svg>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Pending Approvals</span>
            <span class="stat-val" style="font-size: 1.6rem;">3 Requests</span>
            <span style="font-size: 0.75rem; color: var(--color-pending); font-weight: 600; margin-top: 0.25rem;">2 signups, 1 payment proof</span>
        </div>
        <div class="stat-icon warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>
    </div>
</div>

<!-- Main Layout Columns -->
<div class="grid grid-3" style="align-items: start;">
    
    <!-- Column 1 & 2: Urgent Requests & Logs -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Pending Resident Verification Alerts -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem;">Pending Signups Verification</h3>
                <a href="{{ url('/manager/resident-approvals') }}" style="font-size: 0.85rem; font-weight: 600;">Manage All</a>
            </div>
            
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Resident</th>
                        <th>Requested Unit</th>
                        <th>Type</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="font-weight: 700;">Rahman Alvi</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">rahman@example.com</div>
                        </td>
                        <td>Building A, Flat 5B</td>
                        <td><span class="badge badge-unpaid" style="background-color: var(--secondary-light); color: var(--secondary-color);">Tenant</span></td>
                        <td style="text-align: right;">
                            <a href="{{ url('/manager/resident-approvals') }}" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem;">Review Files</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="font-weight: 700;">Sumaiya Karim</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">sumaiya@example.com</div>
                        </td>
                        <td>Building B, Flat 2A</td>
                        <td><span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d;">Owner</span></td>
                        <td style="text-align: right;">
                            <a href="{{ url('/manager/resident-approvals') }}" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem;">Review Files</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- High-Priority Maintenance Complaints -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem;">Urgent Work Orders Needed</h3>
                <a href="{{ url('/manager/complaints') }}" style="font-size: 0.85rem; font-weight: 600;">View Tickets</a>
            </div>
            
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Unit</th>
                        <th>Issue Reported</th>
                        <th>Filed Date</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 700;">Flat 3B</td>
                        <td>
                            <div style="font-weight: 700;">Bathroom pipe leakage</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Dripping joint underneath master basin.</div>
                        </td>
                        <td>July 02, 2026</td>
                        <td style="text-align: right;">
                            <a href="{{ url('/manager/complaints/2033/assign') }}" class="btn btn-outline btn-sm">Assign Staff</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Column 3: Quick Tools & Society broadcast -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Quick Action Tools panel -->
        <div class="card" style="padding: 1.5rem; background-color: var(--primary-light); border-color: rgba(79, 70, 229, 0.15);">
            <h3 style="font-size: 1.15rem; color: var(--primary-color); margin-bottom: 1.25rem;">Management Tools</h3>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="{{ url('/manager/bills/generate') }}" class="btn btn-primary" style="justify-content: center; font-size: 0.85rem;">
                    Generate Monthly Bills
                </a>
                <a href="{{ url('/manager/notices') }}" class="btn btn-outline" style="justify-content: center; font-size: 0.85rem; background-color: white;">
                    Broadcast Announcement
                </a>
                <a href="{{ url('/manager/polls/create') }}" class="btn btn-outline" style="justify-content: center; font-size: 0.85rem; background-color: white;">
                    Create Referendum Poll
                </a>
                <a href="{{ url('/manager/flats/create') }}" class="btn btn-outline" style="justify-content: center; font-size: 0.85rem; background-color: white;">
                    Register New Unit
                </a>
            </div>
        </div>

        <!-- Society Status overview -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Society Systems Status</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.85rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="text-muted">Standby Generator:</span>
                    <span class="badge badge-approved" style="font-size: 0.7rem;">active / healthy</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="text-muted">Water Lift Pump:</span>
                    <span class="badge badge-approved" style="font-size: 0.7rem;">active / healthy</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="text-muted">Basement CCTV Matrix:</span>
                    <span class="badge badge-pending-verification" style="font-size: 0.7rem; color: #d97706; background-color: #fef3c7;">2 cameras offline</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 0.75rem;">
                    <span class="text-muted">Security Guards Duty:</span>
                    <strong style="color: var(--text-primary);">4 Guards on duty</strong>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
