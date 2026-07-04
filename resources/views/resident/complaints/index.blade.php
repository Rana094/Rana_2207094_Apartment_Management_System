@extends('layouts.dashboard')

@section('title', 'My Complaints — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Maintenance Complaints</h1>
        <p class="db-subtitle">Report maintenance issues in your flat or common areas and track resolution progress.</p>
    </div>
    
    <a href="{{ url('/resident/complaints/create') }}" class="btn btn-primary">File New Complaint</a>
</div>

<!-- Complaints List Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Categories</option>
                <option value="plumbing">Plumbing</option>
                <option value="electrical">Electrical</option>
                <option value="carpentry">Carpentry</option>
                <option value="other">Other/General</option>
            </select>
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed / Resolved</option>
            </select>
        </div>
    </div>

    <table class="db-table">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Subject / Issue</th>
                <th>Category</th>
                <th>Filed Date</th>
                <th>Urgency</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">#T-2033</td>
                <td style="font-weight: 600;">Bathroom pipe leakage in master washroom<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Water dripping from under-sink joint causing flooring damage.</div></td>
                <td>Plumbing</td>
                <td>July 02, 2026</td>
                <td><span class="badge badge-rejected" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">high</span></td>
                <td><span class="badge badge-in-progress">in progress</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/resident/complaints/2033') }}" class="btn btn-outline btn-sm">View Status</a>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#T-1804</td>
                <td style="font-weight: 600;">Intercom handset static noise<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Cannot hear visitor voice clearly from the front guard room.</div></td>
                <td>Electrical</td>
                <td>June 18, 2026</td>
                <td><span class="badge badge-pending" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">medium</span></td>
                <td><span class="badge badge-resolved">resolved</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/resident/complaints/1804') }}" class="btn btn-outline btn-sm">View History</a>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>2</strong> active tickets</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
        </div>
    </div>
</div>
@endsection
