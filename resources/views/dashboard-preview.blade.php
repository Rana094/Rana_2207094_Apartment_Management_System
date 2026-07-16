@extends('layouts.dashboard')

@section('title', 'Nestora UI Kit & Dashboard Layout Preview')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">UI Kit and Layout Component Preview</h1>
        <p class="db-subtitle">Welcome to the central design system testbed. Preview layouts, widgets, badges, tables, and modal dialogues.</p>
    </div>
    
    <!-- Quick Role Switcher for preview purposes -->
    <div style="display: flex; gap: 0.5rem; background-color: #ffffff; border: 1px solid var(--border-color); padding: 0.5rem; border-radius: var(--radius-md); align-items: center; box-shadow: var(--shadow-sm);">
        <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); margin-right: 0.5rem;">Sidebar Preview:</span>
        <a href="?role=resident" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; {{ Request::get('role', 'resident') === 'resident' ? 'background-color: var(--primary-light); border-color: var(--primary-color); color: var(--primary-color);' : '' }}">Resident</a>
        <a href="?role=manager" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; {{ Request::get('role') === 'manager' ? 'background-color: var(--primary-light); border-color: var(--primary-color); color: var(--primary-color);' : '' }}">Manager</a>
        <a href="?role=security" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; {{ Request::get('role') === 'security' ? 'background-color: var(--primary-light); border-color: var(--primary-color); color: var(--primary-color);' : '' }}">Security</a>
        <a href="?role=staff" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; {{ Request::get('role') === 'staff' ? 'background-color: var(--primary-light); border-color: var(--primary-color); color: var(--primary-color);' : '' }}">Staff</a>
    </div>
</div>

<!-- 1. Stats Widget Cards Grid -->
<div class="grid grid-4" style="margin-bottom: 2rem;">
    <!-- Card 1 -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Total Residents</span>
            <span class="stat-val">1,240</span>
            <span class="stat-trend up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 0.875rem; height: 0.875rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                12% this month
            </span>
        </div>
        <div class="stat-icon primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 18H9.75c-2.028 0-3.957-.424-5.707-1.184A4.125 4.125 0 017.29 11.533V9.75a4.5 4.5 0 119 0V11.53c0 .878.232 1.704.64 2.42" />
            </svg>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Occupancy Rate</span>
            <span class="stat-val">94.2%</span>
            <span class="stat-trend up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 0.875rem; height: 0.875rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                3.1% this week
            </span>
        </div>
        <div class="stat-icon secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18" />
            </svg>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Open Complaints</span>
            <span class="stat-val">18</span>
            <span class="stat-trend down" style="color: var(--color-approved);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 0.875rem; height: 0.875rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 4.5l-15 15m0 0h11.25m-11.25 0V8.25" /></svg>
                -4 since yesterday
            </span>
        </div>
        <div class="stat-icon warning">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.03V3m0 3a9 9 0 11-9 9 9 9 0 019-9z" />
            </svg>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="stat-card">
        <div class="stat-card-left">
            <span class="stat-label-text">Pending Dues</span>
            <span class="stat-val money"><x-taka />45,600</span>
            <span class="stat-trend down" style="color: var(--color-rejected);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 0.875rem; height: 0.875rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                8% increase
            </span>
        </div>
        <div class="stat-icon danger">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 00 2.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5Z" />
            </svg>
        </div>
    </div>
</div>

<!-- 2. Badges & Interactive Modals Section -->
<div class="grid grid-2" style="margin-bottom: 2rem;">
    <!-- Badges Showcase Card -->
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Status Badge Catalog</h3>
        <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem;">Standardized indicators used across tables, tickets, invoices, and approvals.</p>
        
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-content: center;">
            <span class="badge badge-pending">pending</span>
            <span class="badge badge-approved">approved</span>
            <span class="badge badge-rejected">rejected</span>
            <span class="badge badge-paid">paid</span>
            <span class="badge badge-unpaid">unpaid</span>
            <span class="badge badge-pending-verification">pending verification</span>
            <span class="badge badge-overdue">overdue</span>
            <span class="badge badge-in-progress">in progress</span>
            <span class="badge badge-completed">completed</span>
            <span class="badge badge-emergency">emergency</span>
            <span class="badge badge-resolved">resolved</span>
        </div>
    </div>

    <!-- Modals & Alerts Showcase Card -->
    <div class="card" style="display: flex; flex-col; justify-content: space-between;">
        <div>
            <h3 style="margin-bottom: 1rem;">Interactive Modals & Utilities</h3>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem;">Use standard JavaScript modal configurations to prevent accidental deletion and process actions securely.</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('login') }}" class="btn btn-danger" style="flex: 1; font-size: 0.85rem;">
                Open Live Portal
            </a>
            <a href="{{ route('login') }}" class="btn btn-primary" style="flex: 1; font-size: 0.85rem;">
                Review Live Actions
            </a>
        </div>
    </div>
</div>

<!-- 3. Alert Design Examples -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1.25rem;">Standard Alert Banners</h3>
    
    <div class="alert alert-success" style="margin-bottom: 1rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div><strong>Success:</strong> Payment proof uploaded successfully. Resident account has been marked as Pending Verification.</div>
    </div>

    <div class="alert alert-info" style="margin-bottom: 1rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>
        <div><strong>Info Alert:</strong> Your flat lease agreement expires in 14 days. Please contact manager for renewal.</div>
    </div>

    <div class="alert alert-warning" style="margin-bottom: 1rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
        <div><strong>Warning:</strong> Generator maintenance scheduled. Lift operations will be suspended today between 2:00 PM and 4:00 PM.</div>
    </div>

    <div class="alert alert-danger" style="margin-bottom: 0;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div><strong>Critical Alert:</strong> Fire alarm sensor failure detected in Block C, 5th Floor. Technician has been dispatched.</div>
    </div>
</div>

<!-- 4. Standard Table UI Component -->
<div class="table-responsive" style="margin-bottom: 2rem;">
    <!-- Table Header Toolbar -->
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <input type="text" class="form-control" placeholder="Search residents by name, flat, phone..." style="flex: 2;">
            <select class="form-control form-select" style="flex: 1;">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="table-toolbar-right">
            <button type="button" class="btn btn-outline btn-sm">Export CSV</button>
            <button type="button" class="btn btn-primary btn-sm">Add New Resident</button>
        </div>
    </div>
    
    <!-- Table markup -->
    <table class="db-table">
        <thead>
            <tr>
                <th>Resident Info</th>
                <th>Flat / Unit</th>
                <th>Phone Number</th>
                <th>Resident Type</th>
                <th>Account Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 600;">Kabir Hossain<div style="font-weight: normal; font-size: 0.8rem; color: var(--text-muted);">kabir@example.com</div></td>
                <td>Building A, Flat 3B</td>
                <td>+880 1711 987654</td>
                <td>Owner</td>
                <td><span class="badge badge-approved">approved</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" title="Edit details">Edit</a>
                        <a href="{{ route('login') }}" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem;" title="Deactivate account">Block</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Tasfia Rahman<div style="font-weight: normal; font-size: 0.8rem; color: var(--text-muted);">tasfia@example.com</div></td>
                <td>Building B, Flat 12A</td>
                <td>+880 1823 456789</td>
                <td>Tenant</td>
                <td><span class="badge badge-pending-verification">pending verification</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem;" title="Approve resident">Approve</a>
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" title="Verify documents">Docs</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Farhan Chowdhury<div style="font-weight: normal; font-size: 0.8rem; color: var(--text-muted);">farhan@example.com</div></td>
                <td>Building A, Flat 7C</td>
                <td>+880 1912 345678</td>
                <td>Tenant</td>
                <td><span class="badge badge-pending">pending</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;">Edit</a>
                        <a href="{{ route('login') }}" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem;">Reject</a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- Table Footer / Pagination -->
    <div class="table-pagination">
        <div class="pagination-info">
            Showing <strong>1</strong> to <strong>3</strong> of <strong>24</strong> residents
        </div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
