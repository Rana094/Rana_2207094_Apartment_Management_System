@extends('layouts.dashboard')

@section('title', 'Society Complaints Registry — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Maintenance Complaints Registry</h1>
    <p class="db-subtitle">Monitor maintenance tickets filed by residents and delegate them to repair technicians.</p>
</div>

<!-- Complaints Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Categories</option>
                <option value="plumbing">Plumbing</option>
                <option value="electrical">Electrical</option>
                <option value="carpentry">Carpentry</option>
            </select>
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Statuses</option>
                <option value="pending">Pending Assignment</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Unit / Resident</th>
                <th>Subject & Category</th>
                <th>Date Filed</th>
                <th>Urgency</th>
                <th>Assigned Technician</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">#T-2033</td>
                <td style="font-weight: 600;">Flat 3B<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">John Doe</div></td>
                <td>
                    <div style="font-weight: 700;">Bathroom pipe leakage</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Plumbing Category</div>
                </td>
                <td>July 02, 2026</td>
                <td><span class="badge badge-rejected" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">high</span></td>
                <td style="font-weight: 600;">Ali Khan (Plumber)</td>
                <td><span class="badge badge-in-progress">in progress</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/manager/complaints/2033/assign') }}" class="btn btn-outline btn-sm">Reassign Staff</a>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#T-1804</td>
                <td style="font-weight: 600;">Flat 3B<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">John Doe</div></td>
                <td>
                    <div style="font-weight: 700;">Intercom handset static noise</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Electrical Category</div>
                </td>
                <td>June 18, 2026</td>
                <td><span class="badge badge-pending" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">medium</span></td>
                <td style="font-weight: 600;">Hasan Kabir (Electrician)</td>
                <td><span class="badge badge-resolved">resolved</span></td>
                <td style="text-align: right;">
                    <span class="text-muted text-xs">Completed</span>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>2</strong> complaints</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
        </div>
    </div>
</div>
@endsection
