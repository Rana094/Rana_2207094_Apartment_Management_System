@extends('layouts.dashboard')

@section('title', 'Registered Residents — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Registered Residents</h1>
    <p class="db-subtitle">Database directory of all approved residents, owners, tenants, and their contact information.</p>
</div>

<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <input type="text" class="form-control" placeholder="Search by name, flat, phone..." style="max-width: 250px;">
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Resident Types</option>
                <option value="owner">Owners</option>
                <option value="tenant">Tenants</option>
            </select>
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Statuses</option>
                <option value="approved">Active / Approved</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Resident Full Name</th>
                <th>Assigned Unit</th>
                <th>Category</th>
                <th>Contact Phone</th>
                <th>Account Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 600;">
                    John Doe
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">john@example.com</div>
                </td>
                <td style="font-weight: 600;">Flat 3B</td>
                <td><span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d;">owner</span></td>
                <td>+880 1711 223344</td>
                <td><span class="badge badge-approved">active</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <a href="{{ url('/manager/residents/1') }}" class="btn btn-outline btn-sm">View Profile</a>
                        <button type="button" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem;" onclick="showConfirmModal('Suspend User?', 'Are you sure you want to suspend John Doe? They will be locked out of the resident app.', function(){ alert('Resident account suspended.'); }, true)">Suspend</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">
                    Karim Alvi
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">karim@example.com</div>
                </td>
                <td style="font-weight: 600;">Flat 5A</td>
                <td><span class="badge badge-unpaid" style="background-color: var(--secondary-light); color: var(--secondary-color);">tenant</span></td>
                <td>+880 1812 998877</td>
                <td><span class="badge badge-approved">active</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <a href="{{ url('/manager/residents/2') }}" class="btn btn-outline btn-sm">View Profile</a>
                        <button type="button" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem;" onclick="showConfirmModal('Suspend User?', 'Are you sure you want to suspend Karim Alvi?', function(){ alert('Resident account suspended.'); }, true)">Suspend</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>2</strong> of <strong>111</strong> total residents</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
