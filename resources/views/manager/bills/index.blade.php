@extends('layouts.dashboard')

@section('title', 'Society Bills Ledger — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Billing Ledger & Invoices</h1>
        <p class="db-subtitle">List of all generated monthly billing records, utilities, and collections ledger.</p>
    </div>
    
    <a href="{{ url('/manager/bills/generate') }}" class="btn btn-primary">Generate New Bills</a>
</div>

<!-- Bills Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <input type="text" class="form-control" placeholder="Search by unit or name..." style="max-width: 250px;">
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Categories</option>
                <option value="service">Service Charge</option>
                <option value="electricity">Electricity</option>
                <option value="gas">Gas supply</option>
            </select>
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Statuses</option>
                <option value="unpaid">Unpaid</option>
                <option value="pending">Pending verification</option>
                <option value="paid">Paid</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Target Unit</th>
                <th>Resident Name</th>
                <th>Bill Category</th>
                <th>Period</th>
                <th>Amount</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">#B-9872</td>
                <td style="font-weight: 600;">Flat 3B</td>
                <td>John Doe</td>
                <td>Service Charge</td>
                <td>July 2026</td>
                <td style="font-weight: 700;">৳ 4,500</td>
                <td><span class="badge badge-unpaid">unpaid</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/manager/payments') }}" class="btn btn-outline btn-sm">Record Cash</a>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#B-9540</td>
                <td style="font-weight: 600;">Flat 3B</td>
                <td>John Doe</td>
                <td>Utility (Electricity)</td>
                <td>June 2026</td>
                <td style="font-weight: 700;">৳ 2,120</td>
                <td><span class="badge badge-pending-verification" style="background-color: #fef3c7; color: #d97706;">pending verification</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/manager/payments/1') }}" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem;">Verify Proof</a>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#B-9022</td>
                <td style="font-weight: 600;">Flat 3B</td>
                <td>John Doe</td>
                <td>Service Charge</td>
                <td>June 2026</td>
                <td style="font-weight: 700;">৳ 4,500</td>
                <td><span class="badge badge-paid">paid</span></td>
                <td style="text-align: right;">
                    <span class="text-muted text-xs">Locked</span>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>3</strong> of <strong>240</strong> invoice logs</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
