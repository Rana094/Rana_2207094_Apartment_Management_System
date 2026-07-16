@extends('layouts.dashboard')

@section('title', 'Financial Reports - Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div><h1 class="db-title">Financial Reports</h1><p class="db-subtitle">Billing, collections, outstanding balances, and verified payments.</p></div>
    <button type="button" class="btn btn-outline btn-sm" onclick="window.print()">Print Report</button>
</div>

<div class="grid grid-4" style="margin-bottom: 2rem;">
    <div class="stat-card"><div class="stat-card-left"><span class="stat-label-text">Total Billed</span><span class="stat-val money"><x-taka />{{ number_format((float) $summary['total_billed'], 2) }}</span></div></div>
    <div class="stat-card"><div class="stat-card-left"><span class="stat-label-text">Total Collected</span><span class="stat-val money" style="color: var(--color-approved);"><x-taka />{{ number_format((float) $summary['total_paid'], 2) }}</span></div></div>
    <div class="stat-card"><div class="stat-card-left"><span class="stat-label-text">Outstanding</span><span class="stat-val money" style="color: var(--color-rejected);"><x-taka />{{ number_format((float) $summary['total_unpaid'], 2) }}</span></div></div>
    <a href="{{ route('manager.payments.index') }}" class="stat-card" style="color: inherit; text-decoration: none;"><div class="stat-card-left"><span class="stat-label-text">Proofs Pending</span><span class="stat-val">{{ $summary['pending_payment_proofs'] }}</span></div></a>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Billing by Category</h3>
    <div class="table-responsive"><table class="db-table">
        <thead><tr><th>Category</th><th style="text-align: right;">Invoiced</th><th style="text-align: right;">Collected</th><th style="text-align: right;">Outstanding</th><th style="text-align: right;">Collection Rate</th></tr></thead>
        <tbody>
        @forelse ($billingBreakdown as $row)
            @php
                $invoiced = (float) $row->invoiced;
                $collected = (float) $row->collected;
                $rate = $invoiced > 0 ? ($collected / $invoiced) * 100 : 0;
            @endphp
            <tr>
                <td><strong>{{ str_replace('_', ' ', ucfirst($row->type)) }}</strong></td>
                <td style="text-align: right;"><span class="money"><x-taka />{{ number_format($invoiced, 2) }}</span></td>
                <td style="text-align: right; color: var(--color-approved);"><span class="money"><x-taka />{{ number_format($collected, 2) }}</span></td>
                <td style="text-align: right; color: var(--color-rejected);"><span class="money"><x-taka />{{ number_format($invoiced - $collected, 2) }}</span></td>
                <td style="text-align: right;"><strong>{{ number_format($rate, 1) }}%</strong></td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No bills have been generated.</td></tr>
        @endforelse
        </tbody>
    </table></div>
</div>

<div class="card">
    <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Recent Verified Payments</h3>
    <div class="table-responsive"><table class="db-table">
        <thead><tr><th>Bill</th><th>Flat / Resident</th><th>Transaction Reference</th><th>Verified</th><th style="text-align: right;">Amount</th><th style="text-align: right;">Action</th></tr></thead>
        <tbody>
        @forelse ($recentPayments as $payment)
            <tr>
                <td><strong>{{ $payment->bill?->bill_number ?? '#'.$payment->bill_id }}</strong></td>
                <td>{{ $payment->bill?->flat?->flat_number ?? 'No flat' }} / {{ $payment->user?->name ?? 'Unknown' }}</td>
                <td>{{ $payment->transaction_reference ?: '-' }}</td>
                <td>{{ $payment->verified_at?->format('M d, Y') ?? '-' }}</td>
                <td style="text-align: right;"><strong class="money"><x-taka />{{ number_format((float) ($payment->amount ?? $payment->bill?->amount ?? 0), 2) }}</strong></td>
                <td style="text-align: right;"><a href="{{ route('manager.payments.show', $payment) }}" class="btn btn-outline btn-sm">View</a></td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align: center; color: var(--text-muted);">No verified payments found.</td></tr>
        @endforelse
        </tbody>
    </table></div>
</div>
@endsection
