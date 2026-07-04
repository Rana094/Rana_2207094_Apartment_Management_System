@extends('layouts.dashboard')

@section('title', 'My Facility Bookings — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Facility Bookings</h1>
        <p class="db-subtitle">Book community facilities (Community Hall, Gym, Pool) and view your active reservation history.</p>
    </div>
    
    <a href="{{ url('/resident/bookings/create') }}" class="btn btn-primary">Book a Facility</a>
</div>

<!-- Bookings List Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Facilities</option>
                <option value="hall">Community Hall</option>
                <option value="gym">Fitness Gym</option>
                <option value="bbq">Rooftop BBQ Area</option>
            </select>
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Statuses</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending Approval</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    <table class="db-table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Selected Facility</th>
                <th>Reserved Date</th>
                <th>Time Timings</th>
                <th>Booking Fee</th>
                <th>Payment Status</th>
                <th>Approval Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">#BK-4091</td>
                <td style="font-weight: 600;">Community Hall (Tower 1)<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Family Birthday Event</div></td>
                <td>July 12, 2026</td>
                <td>04:00 PM - 09:00 PM</td>
                <td style="font-weight: 600;">৳ 5,000</td>
                <td><span class="badge badge-paid">paid</span></td>
                <td><span class="badge badge-approved">approved</span></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="alert('Viewing booking receipt voucher...');">Receipt</button>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#BK-3870</td>
                <td style="font-weight: 600;">Rooftop BBQ Grill Station</td>
                <td>July 09, 2026</td>
                <td>06:00 PM - 10:00 PM</td>
                <td style="font-weight: 600;">৳ 1,500</td>
                <td><span class="badge badge-unpaid">unpaid</span></td>
                <td><span class="badge badge-pending-verification" style="color: var(--color-pending); background-color: var(--bg-pending);">pending</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <button type="button" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem;" onclick="showConfirmModal('Cancel Booking?', 'Cancel rooftop BBQ booking request #BK-3870? Booking fees will be cleared.', function(){ alert('Booking cancelled.'); }, true)">Cancel</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>2</strong> reservations</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
        </div>
    </div>
</div>
@endsection
