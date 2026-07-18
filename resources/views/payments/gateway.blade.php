@extends('layouts.public')

@section('title', 'Nestora Pay - Secure Payment')

@section('content')
<section class="section-padding" style="background:#f8fafc; min-height:80vh;">
    <div class="container" style="max-width: 880px;">
        <div style="text-align:center; margin-bottom:2rem;">
            <h1 style="font-size:2rem; margin-bottom:.5rem;">Nestora Pay</h1>
            <p style="color:var(--text-secondary);">Secure demo gateway for apartment rent and service charge payments.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <div class="grid grid-2" style="align-items:start;">
            <div class="card">
                <h3 style="margin-bottom:1.25rem;">Payment Summary</h3>

                <div style="display:flex; flex-direction:column; gap:1rem; font-size:.95rem;">
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <span class="text-muted">Bill Number</span>
                        <strong>{{ $bill->bill_number }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <span class="text-muted">Resident</span>
                        <strong>{{ $resident->name }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <span class="text-muted">Flat</span>
                        <strong>{{ $bill->flat?->flat_number ?? '-' }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <span class="text-muted">Billing Month</span>
                        <strong>{{ $bill->billing_month?->format('F Y') }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <span class="text-muted">Due Date</span>
                        <strong>{{ $bill->due_date?->format('M d, Y') }}</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; gap:1rem; border-top:1px solid var(--border-color); padding-top:1rem; font-size:1.25rem;">
                        <span style="font-weight:800;">Payable Amount</span>
                        <strong class="money" style="color:var(--primary-color);"><x-taka />{{ number_format((float) $transaction->amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-bottom:1rem;">Confirm Payment</h3>
                <div style="background:var(--bg-main); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1rem; margin-bottom:1rem;">
                    <div style="font-size:.8rem; color:var(--text-muted); margin-bottom:.25rem;">Secure Transaction</div>
                    <strong>{{ $transaction->transaction_number }}</strong>
                </div>

                @if ($transaction->status === 'paid' || $bill->status === 'paid')
                    <div class="alert alert-success">This bill is already paid.</div>
                    <a href="{{ route('resident.bills.show', $bill) }}" class="btn btn-primary" style="width:100%; justify-content:center;">Back to Invoice</a>
                @elseif ($transaction->expires_at && $transaction->expires_at->isPast())
                    <div class="alert alert-danger">This payment session has expired. Reopen the bill page to generate a fresh QR session.</div>
                    <a href="{{ route('resident.bills.show', $bill) }}" class="btn btn-outline" style="width:100%; justify-content:center;">Back to Invoice</a>
                @else
                    <p style="font-size:.9rem; color:var(--text-secondary); line-height:1.6; margin-bottom:1.5rem;">
                        This is a local demo gateway. The amount is locked from the database, and confirming will mark this bill as paid for both resident and manager dashboards.
                    </p>

                    <form method="POST" action="{{ route('payments.confirm', $transaction->payment_token) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; font-size:1rem; padding:.9rem 1.25rem;">
                            Pay & Confirm <span class="money"><x-taka />{{ number_format((float) $transaction->amount, 2) }}</span>
                        </button>
                    </form>

                    <a href="{{ route('resident.bills.show', $bill) }}" class="btn btn-outline" style="width:100%; justify-content:center; margin-top:.75rem;">Cancel</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        window.addEventListener('pageshow', (event) => {
            const navigation = performance.getEntriesByType('navigation')[0];

            if (event.persisted || navigation?.type === 'back_forward') {
                window.location.reload();
            }
        });
    </script>
@endpush
