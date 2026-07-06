@extends('layouts.dashboard')

@section('title', 'Amenity Reservations Queue — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Amenity Reservations Queue</h1>
    <p class="db-subtitle">Approve or reject resident booking requests for community spaces and track booking deposits.</p>
</div>

<!-- Bookings Queue Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Spaces</option>
                <option value="hall">Community Hall</option>
                <option value="bbq">Rooftop BBQ Grill</option>
            </select>
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Statuses</option>
                <option value="pending">Pending Approval</option>
                <option value="approved">Approved</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Booking Ref</th>
                <th>Resident / Flat</th>
                <th>Selected Space</th>
                <th>Reserved Timings</th>
                <th>Dues Amount</th>
                <th>Payment</th>
                <th>Status</th>
                <th style="text-align: right;">Approval Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">#BK-3870</td>
                <td style="font-weight: 600;">John Doe<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Flat 3B</div></td>
                <td>Rooftop BBQ Grill Station</td>
                <td>July 09, 2026<div style="font-size: 0.75rem; color: var(--text-muted);">06:00 PM - 10:00 PM</div></td>
                <td style="font-weight: 600;">৳ 1,500</td>
                <td><span class="badge badge-unpaid">unpaid</span></td>
                <td><span class="badge badge-pending-verification" style="color: var(--color-pending); background-color: var(--bg-pending);">pending</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <button type="button" class="btn btn-primary btn-sm" onclick="alert('Booking approved.');">Approve</button>
                        <button type="button" class="btn btn-outline btn-sm" onclick="showConfirmModal('Reject Booking Request?', 'Reject Rooftop BBQ reservation?', function(){ alert('Booking rejected.'); }, true)">Reject</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#BK-4091</td>
                <td style="font-weight: 600;">John Doe<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Flat 3B</div></td>
                <td>Community Hall (Tower 1)</td>
                <td>July 12, 2026<div style="font-size: 0.75rem; color: var(--text-muted);">04:00 PM - 09:00 PM</div></td>
                <td style="font-weight: 600;">৳ 5,000</td>
                <td><span class="badge badge-paid">paid</span></td>
                <td><span class="badge badge-approved">approved</span></td>
                <td style="text-align: right;">
                    <span class="text-muted text-xs">Locked</span>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>2</strong> requests</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
        </div>
    </div>
</div>
@endsection
