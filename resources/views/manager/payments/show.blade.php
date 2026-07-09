@extends('layouts.dashboard')

@section('title', 'Verify Payment #B-9540 — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/manager/payments') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Verification Queue
    </a>
    <h1 class="db-title">Verify Transaction Ledger</h1>
    <p class="db-subtitle">Cross-examine bank transfer receipt details and update invoice ledger status.</p>
</div>

<div class="grid grid-3" style="align-items: start;">
    
    <!-- Left Column: Transaction Details Form (Span 2) -->
    <div class="card" style="grid-column: span 2;">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.2rem;">Submitted Proof Details</h3>
        
        <form action="{{ url('/manager/payments') }}" method="GET" id="verify-form">
            <!-- Hidden verification flag -->
            <input type="hidden" name="verified" value="1">
            
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Resident Account</label>
                    <input type="text" class="form-control" value="John Doe (Flat 3B)" readonly style="background-color: var(--bg-main);">
                </div>
                <div class="form-group">
                    <label class="form-label">Linked Dues Item</label>
                    <input type="text" class="form-control" value="June 2026 Utility - Electricity (#B-9540)" readonly style="background-color: var(--bg-main);">
                </div>
            </div>

            <div class="grid grid-3">
                <div class="form-group">
                    <label class="form-label">Method</label>
                    <input type="text" class="form-control" value="Bank Transfer (EFT)" readonly style="background-color: var(--bg-main);">
                </div>
                <div class="form-group">
                    <label class="form-label">Transaction ID</label>
                    <input type="text" class="form-control" value="TXN9540BB8" readonly style="background-color: var(--bg-main); font-weight: 700;">
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Payment</label>
                    <input type="text" class="form-control" value="July 04, 2026" readonly style="background-color: var(--bg-main);">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Resident Amount Paid (৳)</label>
                <input type="text" class="form-control" value="৳ 2,120" readonly style="background-color: var(--bg-main); font-weight: 700; color: var(--primary-color);">
            </div>

            <div class="form-group">
                <label class="form-label">Resident Submission Comments</label>
                <p style="background-color: var(--bg-main); padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0;">
                    Transferred from my Dhaka Bank account to the society account. Reference added in remarks: Flat 3B.
                </p>
            </div>

            <!-- Verification Action Buttons -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 1.5rem; margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">
                    Verify & Mark Invoice Paid
                </button>
                <button type="button" class="btn btn-outline" style="flex: 1; justify-content: center; border-color: var(--color-rejected); color: var(--color-rejected);" onclick="showRejectionReason()">
                    Reject Proof
                </button>
            </div>
            
            <!-- Rejection comment field shown dynamically -->
            <div id="rejection-comment-container" style="display: none; border-top: 1px solid var(--border-color); padding-top: 1.25rem; margin-top: 1.25rem;">
                <div class="form-group">
                    <label for="rej-reason" class="form-label">Reason for Rejection</label>
                    <textarea id="rej-reason" class="form-control" rows="2" placeholder="e.g. Transaction ID was not found in our bank statement, please upload again..."></textarea>
                </div>
                <div style="text-align: right;">
                    <button type="button" class="btn btn-danger btn-sm" onclick="submitRejection()">Confirm Rejection</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Right Column: Proof Attachment view window -->
    <div class="card" style="grid-column: span 1; text-align: center;">
        <h3 style="margin-bottom: 1rem; font-size: 1.1rem; text-align: left;">Receipt Attachment</h3>
        
        <div style="background-color: var(--bg-main); border: 1px dashed var(--border-color); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
            <!-- Document Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" style="width: 4rem; height: 4rem; margin: 0 auto 1rem auto; color: var(--text-muted);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">EFT_Receipt_3B.pdf</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">PDF document (1.4 MB)</div>
        </div>

        <button type="button" class="btn btn-outline btn-sm" style="width: 100%; justify-content: center;" onclick="alert('Mock: Downloading EFT receipt PDF.');">
            Download File Attachment
        </button>
    </div>

</div>

<script>
    function showRejectionReason() {
        const container = document.getElementById('rejection-comment-container');
        if (container) {
            container.style.display = 'block';
            window.scrollTo({ top: container.offsetTop, behavior: 'smooth' });
        }
    }

    function submitRejection() {
        const reason = document.getElementById('rej-reason');
        if (!reason.value) {
            alert('Please enter a rejection reason.');
            return;
        }
        alert('Payment proof rejected. Notification sent to resident.');
        window.location.href = "{{ url('/manager/payments') }}";
    }
</script>
@endsection
