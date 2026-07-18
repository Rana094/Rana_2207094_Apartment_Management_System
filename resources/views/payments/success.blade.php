@extends('layouts.public')

@section('title', 'Payment Successful - Nestora')

@push('head')
    <meta http-equiv="refresh" content="7;url={{ route('resident.bills.show', $bill) }}">
@endpush

@section('content')
<section class="section-padding" style="background:#f8fafc; min-height:80vh;">
    <div class="container" style="max-width:680px;">
        <div class="card" style="text-align:center; padding:3rem;">
            <div style="width:4rem; height:4rem; border-radius:999px; background:#dcfce7; color:#15803d; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <x-icon name="success" alt="" size="2rem" />
            </div>
            <h1 style="font-size:2rem; margin-bottom:.5rem;">Payment Successful</h1>
            <p style="color:var(--text-secondary); margin-bottom:2rem;">Your Nestora bill has been paid and the manager dashboard has been updated.</p>

            <div style="background:var(--bg-main); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; text-align:left; margin-bottom:2rem;">
                <div style="display:flex; justify-content:space-between; gap:1rem; margin-bottom:.75rem;"><span class="text-muted">Bill</span><strong>{{ $bill->bill_number }}</strong></div>
                <div style="display:flex; justify-content:space-between; gap:1rem; margin-bottom:.75rem;"><span class="text-muted">Transaction</span><strong>{{ $transaction->transaction_number }}</strong></div>
                <div style="display:flex; justify-content:space-between; gap:1rem; margin-bottom:.75rem;"><span class="text-muted">Amount</span><strong class="money"><x-taka />{{ number_format((float) $transaction->amount, 2) }}</strong></div>
                <div style="display:flex; justify-content:space-between; gap:1rem;"><span class="text-muted">Paid At</span><strong>{{ $transaction->paid_at?->format('M d, Y H:i') }}</strong></div>
            </div>

            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:var(--radius-md); color:#1d4ed8; padding:1rem; margin-bottom:1rem;">
                You will be redirected to your invoice in <strong id="redirect-countdown">7</strong> seconds.
            </div>

            <a href="{{ route('resident.bills.show', $bill) }}" class="btn btn-primary" style="justify-content:center;">Return Now</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        (() => {
            const redirectUrl = @json(route('resident.bills.show', $bill));
            const countdown = document.getElementById('redirect-countdown');
            let secondsLeft = 7;

            const timer = window.setInterval(() => {
                secondsLeft -= 1;

                if (countdown) {
                    countdown.textContent = secondsLeft.toString();
                }

                if (secondsLeft <= 0) {
                    window.clearInterval(timer);
                    window.location.assign(redirectUrl);
                }
            }, 1000);
        })();
    </script>
@endpush
