@extends('layouts.dashboard')

@section('title', 'Bills & Payments — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Bills & Payments</h1>
        <p class="db-subtitle">View your monthly billing records, service charge details, and upload receipts.</p>
    </div>
</div>

<!-- Bills Table Card -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Bill Types</option>
                <option value="service">Service Charge</option>
                <option value="utility">Utility Bill</option>
            </select>
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Statuses</option>
                <option value="unpaid">Unpaid</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending Verification</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Bill ID</th>
                <th>Bill Period</th>
                <th>Category</th>
                <th>Amount Dues</th>
                <th>Due Date</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">#B-9872</td>
                <td style="font-weight: 600;">July 2026</td>
                <td>Service Charge</td>
                <td style="font-weight: 700;">৳ 4,500</td>
                <td>July 10, 2026</td>
                <td><span class="badge badge-unpaid">unpaid</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <a href="{{ url('/resident/bills/9872') }}" class="btn btn-outline btn-sm">View Details</a>
                        <a href="{{ url('/resident/bills/9872/upload') }}" class="btn btn-primary btn-sm">Pay Now</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#B-9540</td>
                <td style="font-weight: 600;">June 2026</td>
                <td>Utility (Electricity)</td>
                <td style="font-weight: 700;">৳ 2,120</td>
                <td>June 15, 2026</td>
                <td><span class="badge badge-pending-verification">pending verification</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <a href="{{ url('/resident/bills/9540') }}" class="btn btn-outline btn-sm">View Details</a>
                        <span class="text-muted text-xs font-semibold" style="align-self: center; padding: 0 0.5rem;">Verifying...</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#B-9022</td>
                <td style="font-weight: 600;">June 2026</td>
                <td>Service Charge</td>
                <td style="font-weight: 700;">৳ 4,500</td>
                <td>June 10, 2026</td>
                <td><span class="badge badge-paid">paid</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/resident/bills/9022') }}" class="btn btn-outline btn-sm">View Invoice</a>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#B-8801</td>
                <td style="font-weight: 600;">May 2026</td>
                <td>Service Charge</td>
                <td style="font-weight: 700;">৳ 4,500</td>
                <td>May 10, 2026</td>
                <td><span class="badge badge-paid">paid</span></td>
                <td style="text-align: right;">
                    <a href="{{ url('/resident/bills/8801') }}" class="btn btn-outline btn-sm">View Invoice</a>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>4</strong> bills of <strong>12</strong> total records</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
