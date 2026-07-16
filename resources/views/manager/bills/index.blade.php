@extends('layouts.dashboard')

@section('title', 'Billing Ledger - Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
    <div><h1 class="db-title">Billing Ledger & Invoices</h1><p class="db-subtitle">Generated dues and resident payment status.</p></div>
    <a href="{{ route('manager.bills.generate') }}" class="btn btn-primary">Generate New Bills</a>
</div>
@if (session('status')) <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div> @endif

<div class="table-responsive">
    <table class="db-table">
        <thead><tr><th>Invoice</th><th>Flat</th><th>Resident</th><th>Category</th><th>Period</th><th>Amount</th><th>Status</th><th style="text-align:right;">Action</th></tr></thead>
        <tbody>
            @forelse ($bills as $bill)
                @php($proof = $bill->paymentProofs->sortByDesc('created_at')->first())
                <tr>
                    <td style="font-weight:700;">{{ $bill->bill_number }}</td>
                    <td>{{ $bill->flat?->flat_number ?? '-' }}</td>
                    <td>{{ $bill->resident?->name ?? '-' }}</td>
                    <td>{{ str_replace('_', ' ', $bill->type) }}</td>
                    <td>{{ $bill->billing_month?->format('M Y') }}</td>
                    <td style="font-weight:700;"><span class="money"><x-taka />{{ number_format((float) $bill->amount, 2) }}</span></td>
                    <td><span class="badge badge-{{ $bill->status === 'paid' ? 'paid' : 'unpaid' }}">{{ str_replace('_', ' ', $bill->status) }}</span></td>
                    <td style="text-align:right;">
                        @if ($proof)
                            <a href="{{ route('manager.payments.show', $proof) }}" class="btn btn-outline btn-sm">Review Proof</a>
                        @else
                            <span class="text-muted text-xs">No proof submitted</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;padding:2rem;">No bills generated.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="table-pagination"><div class="pagination-info">{{ $bills->total() }} invoices</div><div class="pagination-btns">@if($bills->previousPageUrl())<a href="{{ $bills->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>@endif @if($bills->nextPageUrl())<a href="{{ $bills->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>@endif</div></div>
</div>
@endsection
