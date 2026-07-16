@extends('layouts.dashboard')

@section('title', 'Payment Verification - Nestora')

@section('content')
<div class="db-header"><h1 class="db-title">Payment Verification Queue</h1><p class="db-subtitle">Review proof uploaded by residents and update linked bills.</p></div>
@if (session('status')) <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div> @endif
<div class="table-responsive">
    <table class="db-table">
        <thead><tr><th>Resident</th><th>Bill</th><th>Transaction</th><th>Submitted</th><th>Amount</th><th>Status</th><th style="text-align:right;">Action</th></tr></thead>
        <tbody>
            @forelse ($paymentProofs as $proof)
                <tr>
                    <td>{{ $proof->user?->name ?? '-' }}</td>
                    <td>{{ $proof->bill?->bill_number ?? '-' }}</td>
                    <td>{{ $proof->transaction_reference ?? '-' }}</td>
                    <td>{{ $proof->submitted_at?->format('M d, Y H:i') ?? $proof->created_at?->format('M d, Y H:i') }}</td>
                    <td><span class="money"><x-taka />{{ number_format((float) ($proof->amount ?? 0), 2) }}</span></td>
                    <td><span class="badge badge-{{ $proof->status === 'approved' ? 'approved' : 'pending-verification' }}">{{ $proof->status }}</span></td>
                    <td style="text-align:right;"><a href="{{ route('manager.payments.show', $proof) }}" class="btn btn-primary btn-sm">Review</a></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;padding:2rem;">No payment proofs submitted.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="table-pagination"><div class="pagination-info">{{ $paymentProofs->total() }} submissions</div><div class="pagination-btns">@if($paymentProofs->previousPageUrl())<a href="{{ $paymentProofs->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>@endif @if($paymentProofs->nextPageUrl())<a href="{{ $paymentProofs->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>@endif</div></div>
</div>
@endsection
