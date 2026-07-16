@extends('layouts.dashboard')

@section('title', 'Visitor Logs Registry — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Visitor Logs Directory</h1>
        <p class="db-subtitle">Complete historical log of all visitor entries, exits, and pre-approved gate clearances.</p>
    </div>
    
    <a href="{{ url('/security/checkin') }}" class="btn btn-primary">Register Walk-In Visitor</a>
</div>

<!-- Logs Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <input type="text" class="form-control" placeholder="Search by name, flat, phone..." style="max-width: 250px;">
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Categories</option>
                <option value="guest">Guest</option>
                <option value="delivery">Delivery</option>
                <option value="service">Service</option>
            </select>
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Statuses</option>
                <option value="inside">Currently Inside</option>
                <option value="completed">Checked Out</option>
                <option value="scheduled">Scheduled</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Visitor Applicant</th>
                <th>Destination Unit</th>
                <th>Category</th>
                <th>Pass Code</th>
                <th>Check-In Timestamps</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 600;">
                    Farhan Alvi
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">+880 1812 998877</div>
                </td>
                <td style="font-weight: 600;">Flat 3B — ullas</td>
                <td>Delivery (Foodpanda)</td>
                <td><code style="background-color: var(--bg-main); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm); font-weight: 700;">N-5509</code></td>
                <td>
                    <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600;">In: Today, 10:12 AM<br>Out: —</span>
                </td>
                <td><span class="badge badge-unpaid" style="background-color: var(--secondary-light); color: var(--secondary-color);">inside</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/security/checkout?passcode=N-5509') }}" class="btn btn-outline btn-sm">Check-Out</a>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">
                    Ahmad Sufian
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">+880 1723 445566</div>
                </td>
                <td style="font-weight: 600;">Flat 3B — ullas</td>
                <td>Guest (Relative)</td>
                <td><code style="background-color: var(--bg-main); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm); font-weight: 700;">N-2311</code></td>
                <td>
                    <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600;">In: Yesterday, 10:12 AM<br>Out: Yesterday, 02:40 PM</span>
                </td>
                <td><span class="badge badge-completed">checked out</span></td>
                <td style="text-align: right;">
                    <span class="text-muted text-xs">Closed</span>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>2</strong> records of <strong>320</strong> total visitor entries</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
