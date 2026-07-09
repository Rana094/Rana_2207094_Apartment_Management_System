@extends('layouts.dashboard')

@section('title', 'Payment Verification Queue — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Payment Verification Queue</h1>
    <p class="db-subtitle">Review bank receipts and mobile banking screenshots uploaded by residents to clear their outstanding bills.</p>
</div>

<!-- Queue List Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Payment Methods</option>
                <option value="bank">Bank Transfer</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Applicant / Unit</th>
                <th>Bill Reference</th>
                <th>Method</th>
                <th>Transaction ID</th>
                <th>Upload Date</th>
                <th>Amount Paid</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 600;">
                    John Doe
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Flat 3B</div>
                </td>
                <td style="font-weight: 700;">#B-9540 (Electricity)</td>
                <td><span style="text-transform: capitalize;">bank</span></td>
                <td><code style="background-color: var(--bg-main); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm); font-weight: 700;">TXN9540BB8</code></td>
                <td>July 04, 2026</td>
                <td style="font-weight: 700; color: var(--primary-color);">৳ 2,120</td>
                <td><span class="badge badge-pending-verification" style="background-color: #fef3c7; color: #d97706;">pending verification</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/manager/payments/1') }}" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem;">Review & Verify</a>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>1</strong> active pending request</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
        </div>
    </div>
</div>
@endsection
