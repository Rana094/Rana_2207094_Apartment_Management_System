@extends('layouts.dashboard')

@section('title', 'Financial Reports — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Financial Performance Reports</h1>
        <p class="db-subtitle">Detailed records of society fund inflows, outstanding balances, and cost center breakdowns.</p>
    </div>
    
    <button type="button" class="btn btn-outline btn-sm" onclick="window.print();">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1rem; height: 1rem;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.82l-.24 4.54m0 0l-3-3m3 3h10.5m-1.5-12.75h-7.5c-.621 0-1.125.504-1.125 1.125v10.125a1.125 1.125 0 001.125 1.125h9.75a1.125 1.125 0 001.125-1.125V8.25m-3-5.25v5.25" />
        </svg>
        Print Report
    </button>
</div>

<!-- Financial Summary Widgets -->
<div class="grid grid-3" style="margin-bottom: 2rem;">
    <!-- Collections -->
    <div class="stat-card" style="border-left: 4px solid var(--color-approved);">
        <div class="stat-card-left">
            <span class="stat-label-text">Total Funds Collected</span>
            <span class="stat-val" style="font-size: 1.75rem; color: var(--color-approved);">৳ 4,80,000</span>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">This Billing Cycle</span>
        </div>
    </div>

    <!-- Outstanding -->
    <div class="stat-card" style="border-left: 4px solid var(--color-rejected);">
        <div class="stat-card-left">
            <span class="stat-label-text">Total Outstanding Balances</span>
            <span class="stat-val" style="font-size: 1.75rem; color: var(--color-rejected);">৳ 60,000</span>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Unpaid Resident Dues</span>
        </div>
    </div>

    <!-- Pending Verification -->
    <div class="stat-card" style="border-left: 4px solid var(--color-pending);">
        <div class="stat-card-left">
            <span class="stat-label-text">Pending Verification</span>
            <span class="stat-val" style="font-size: 1.75rem; color: var(--color-pending);">৳ 2,120</span>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">1 Uploaded Proof Pending</span>
        </div>
    </div>
</div>

<!-- Cost Center analysis Table -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Billing Breakdown by Category</h3>
    
    <table class="db-table" style="font-size: 0.875rem;">
        <thead>
            <tr>
                <th>Billing Category</th>
                <th style="text-align: right;">Total Invoiced</th>
                <th style="text-align: right;">Total Collected</th>
                <th style="text-align: right;">Outstanding</th>
                <th style="text-align: right;">Collection Rate</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 600;">Monthly Flat Service Charge</td>
                <td style="text-align: right;">৳ 3,30,000</td>
                <td style="text-align: right; color: var(--color-approved); font-weight: 600;">৳ 3,00,000</td>
                <td style="text-align: right; color: var(--color-rejected); font-weight: 600;">৳ 30,000</td>
                <td style="text-align: right; font-weight: 700; color: var(--primary-color);">90.9%</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Standby Generator Surcharge</td>
                <td style="text-align: right;">৳ 1,10,000</td>
                <td style="text-align: right; color: var(--color-approved); font-weight: 600;">৳ 1,00,000</td>
                <td style="text-align: right; color: var(--color-rejected); font-weight: 600;">৳ 10,000</td>
                <td style="text-align: right; font-weight: 700; color: var(--primary-color);">90.9%</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Utility Share (Water & Gas)</td>
                <td style="text-align: right;">৳ 1,00,000</td>
                <td style="text-align: right; color: var(--color-approved); font-weight: 600;">৳ 80,000</td>
                <td style="text-align: right; color: var(--color-rejected); font-weight: 600;">৳ 20,000</td>
                <td style="text-align: right; font-weight: 700; color: var(--primary-color);">80.0%</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Recent Transactions Log -->
<div class="card">
    <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Recent Verified Transactions</h3>
    
    <table class="db-table" style="font-size: 0.875rem;">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Unit / Resident</th>
                <th>Payment Mode</th>
                <th>Transaction Key</th>
                <th>Verified Date</th>
                <th style="text-align: right;">Verified Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">#B-9022</td>
                <td style="font-weight: 600;">Flat 3B — John Doe</td>
                <td>bKash Mobile Money</td>
                <td><code style="background-color: var(--bg-main); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm);">BKASH9022TX</code></td>
                <td>June 09, 2026</td>
                <td style="text-align: right; font-weight: 700; color: var(--color-approved);">৳ 4,500</td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#B-8801</td>
                <td style="font-weight: 600;">Flat 3B — John Doe</td>
                <td>Bank Transfer</td>
                <td><code style="background-color: var(--bg-main); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm);">EFT8801BB3</code></td>
                <td>May 08, 2026</td>
                <td style="text-align: right; font-weight: 700; color: var(--color-approved);">৳ 4,500</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
