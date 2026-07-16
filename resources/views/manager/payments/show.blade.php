@extends('layouts.dashboard')

@section('title', 'Verify Payment - Nestora')

@section('content')
<div class="db-header"><a href="{{ route('manager.payments.index') }}" style="font-size:.85rem;font-weight:600;">Back to Verification Queue</a><h1 class="db-title">Verify Transaction</h1></div>

<div class="grid grid-3" style="align-items:start;">
    <div class="card" style="grid-column:span 2;">
        <div class="grid grid-2">
            <div class="form-group"><label class="form-label">Resident</label><input class="form-control" value="{{ $paymentProof->user?->name ?? '-' }}" readonly></div>
            <div class="form-group"><label class="form-label">Bill</label><input class="form-control" value="{{ $paymentProof->bill?->bill_number ?? '-' }}" readonly></div>
            <div class="form-group"><label class="form-label">Transaction Reference</label><input class="form-control" value="{{ $paymentProof->transaction_reference ?? '-' }}" readonly></div>
            <div class="form-group"><label class="form-label">Amount</label><div class="form-control money"><x-taka />{{ number_format((float) ($paymentProof->amount ?? 0), 2) }}</div></div>
        </div>
        <div style="display:flex;gap:1rem;margin-top:1rem;">
            <form method="POST" action="{{ route('manager.payments.verify', $paymentProof) }}" style="flex:2;">@csrf<input type="hidden" name="status" value="approved"><button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Verify & Mark Paid</button></form>
            <form id="reject-payment-form" method="POST" action="{{ route('manager.payments.verify', $paymentProof) }}" style="flex:1;">@csrf<input type="hidden" name="status" value="rejected"><button type="button" class="btn btn-danger" style="width:100%;justify-content:center;" onclick="showConfirmModal('Reject payment proof?', 'The linked bill will remain unpaid.', function(){ document.getElementById('reject-payment-form').submit(); }, true)">Reject Proof</button></form>
        </div>
    </div>
    <div class="card" style="text-align:center;">
        <h3 style="margin-bottom:1rem;">Receipt Attachment</h3>
        <p class="text-muted">Secure resident payment proof</p>
        <a href="{{ $paymentProof->secureUrl() }}" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">Download Attachment</a>
    </div>
</div>
@endsection
