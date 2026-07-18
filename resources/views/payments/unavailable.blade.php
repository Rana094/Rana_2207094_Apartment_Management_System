@extends('layouts.public')

@section('title', 'Payment Unavailable - Nestora')

@push('head')
    <meta http-equiv="refresh" content="7;url={{ route('resident.dashboard') }}">
@endpush

@section('content')
<section class="section-padding" style="background:#f8fafc; min-height:80vh;">
    <div class="container" style="max-width:680px;">
        <div class="card" style="text-align:center; padding:3rem;">
            <div style="width:4rem; height:4rem; border-radius:999px; background:#fee2e2; color:#b91c1c; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                <x-icon name="warning" alt="" size="2rem" />
            </div>

            <h1 style="font-size:2rem; margin-bottom:.5rem;">Payment Page Not Available</h1>
            <p style="color:var(--text-secondary); margin-bottom:1.5rem;">
                {{ session('status', 'You are not allowed to access this payment page because this payment token is no longer active.') }}
            </p>

            <div style="background:var(--bg-main); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1.25rem; text-align:left; margin-bottom:2rem;">
                <div style="display:flex; justify-content:space-between; gap:1rem; margin-bottom:.75rem;"><span class="text-muted">Bill</span><strong>{{ $bill->bill_number }}</strong></div>
                <div style="display:flex; justify-content:space-between; gap:1rem; margin-bottom:.75rem;"><span class="text-muted">Transaction</span><strong>{{ $transaction->transaction_number }}</strong></div>
                <div style="display:flex; justify-content:space-between; gap:1rem;"><span class="text-muted">Status</span><strong>{{ ucfirst($transaction->status) }}</strong></div>
            </div>

            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:var(--radius-md); color:#1d4ed8; padding:1rem; margin-bottom:1rem;">
                You will be redirected to your dashboard in <strong id="redirect-countdown">7</strong> seconds.
            </div>

            <a href="{{ route('resident.dashboard') }}" class="btn btn-primary" style="justify-content:center;">Go to Dashboard</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        (() => {
            const redirectUrl = @json(route('resident.dashboard'));
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
