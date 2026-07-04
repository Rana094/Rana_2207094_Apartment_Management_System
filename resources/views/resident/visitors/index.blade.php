@extends('layouts.dashboard')

@section('title', 'Visitor Passes — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Visitor Passes</h1>
        <p class="db-subtitle">Pre-approve guests, delivery riders, and home service providers to simplify entrance gate checks.</p>
    </div>
    
    <a href="{{ url('/resident/visitors/create') }}" class="btn btn-primary">Create Visitor Pass</a>
</div>

<!-- Visitor Passes Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Visitor Types</option>
                <option value="guest">Guest / Personal</option>
                <option value="delivery">Delivery Rider</option>
                <option value="service">Home Service</option>
            </select>
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Statuses</option>
                <option value="scheduled">Scheduled</option>
                <option value="inside">Currently Inside</option>
                <option value="completed">Checked Out</option>
                <option value="expired">Expired</option>
            </select>
        </div>
    </div>

    <table class="db-table">
        <thead>
            <tr>
                <th>Pass Code</th>
                <th>Visitor Name</th>
                <th>Visitor Category</th>
                <th>Phone Number</th>
                <th>Scheduled Date</th>
                <th>Check-in/out</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code style="background-color: var(--primary-light); color: var(--primary-color); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.85rem;">N-5509</code></td>
                <td style="font-weight: 600;">Farhan Alvi</td>
                <td>Delivery Rider (Foodpanda)</td>
                <td>+880 1812 998877</td>
                <td>July 5, 2026</td>
                <td class="text-muted">—</td>
                <td><span class="badge badge-pending-verification" style="color: #2563eb; background-color: #dbeafe;">scheduled</span></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="showConfirmModal('Cancel Pass?', 'Cancel this visitor pass code? Guard will no longer allow entry for N-5509.', function(){ alert('Pass code cancelled.'); }, true)">Cancel Pass</button>
                </td>
            </tr>
            <tr>
                <td><code style="background-color: var(--border-color); color: var(--text-primary); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.85rem;">N-2311</code></td>
                <td style="font-weight: 600;">Ahmad Sufian</td>
                <td>Guest (Relative)</td>
                <td>+880 1723 445566</td>
                <td>July 4, 2026</td>
                <td><span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600;">In: 10:12 AM<br>Out: 02:40 PM</span></td>
                <td><span class="badge badge-completed">checked out</span></td>
                <td style="text-align: right;">
                    <span class="text-muted text-xs">Closed</span>
                </td>
            </tr>
            <tr>
                <td><code style="background-color: var(--border-color); color: var(--text-primary); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.85rem;">N-1902</code></td>
                <td style="font-weight: 600;">Electrician Asad</td>
                <td>Home Service Staff</td>
                <td>+880 1511 887766</td>
                <td>July 1, 2026</td>
                <td><span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600;">In: 03:00 PM<br>Out: 04:30 PM</span></td>
                <td><span class="badge badge-approved" style="background-color: var(--bg-resolved); color: var(--color-resolved);">resolved</span></td>
                <td style="text-align: right;">
                    <span class="text-muted text-xs">Closed</span>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>3</strong> records of <strong>25</strong> total visitors</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
