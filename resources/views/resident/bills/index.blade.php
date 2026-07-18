@extends('layouts.dashboard')

@section('title', 'Bills & Payments - Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Bills & Payments</h1>
        <p class="db-subtitle">View your rent, service charges, QR payment links, and payment status.</p>
    </div>
</div>

<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Bill ID</th>
                <th>Bill Period</th>
                <th>Category</th>
                <th>Amount Due</th>
                <th>Due Date</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bills as $bill)
                @php($transaction = $bill->latestPaymentTransaction)
                <tr>
                    <td style="font-weight: 700;">{{ $bill->bill_number }}</td>
                    <td style="font-weight: 600;">{{ $bill->billing_month?->format('F Y') }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($bill->type)) }}</td>
                    <td style="font-weight: 700;"><span class="money"><x-taka />{{ number_format((float) $bill->amount, 2) }}</span></td>
                    <td>{{ $bill->due_date?->format('M d, Y') }}</td>
                    <td><span class="badge badge-{{ $bill->status === 'paid' ? 'paid' : ($bill->status === 'pending_verification' ? 'pending-verification' : 'unpaid') }}">{{ str_replace('_', ' ', $bill->status) }}</span></td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem;">
                            <a href="{{ route('resident.bills.show', $bill) }}" class="btn btn-outline btn-sm">{{ $bill->status === 'paid' ? 'View Invoice' : 'View Details' }}</a>
                            @if ($bill->status !== 'paid' && $transaction)
                                <a href="{{ route('payments.show', $transaction->payment_token) }}" class="btn btn-primary btn-sm">Pay Now</a>
                            @elseif ($bill->status !== 'paid')
                                <a href="{{ route('resident.bills.show', $bill) }}" class="btn btn-primary btn-sm">Generate QR</a>
                            @else
                                <span class="text-muted text-xs font-semibold" style="align-self: center; padding: 0 0.5rem;">Paid</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">No bills generated yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($bills->hasPages())
        <div class="table-pagination" style="margin-top: 1rem;">{{ $bills->links() }}</div>
    @endif
</div>
@endsection
