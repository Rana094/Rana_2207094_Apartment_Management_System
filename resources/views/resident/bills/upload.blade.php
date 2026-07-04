@extends('layouts.dashboard')

@section('title', 'Upload Payment Proof — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/resident/bills') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Bills List
    </a>
    <h1 class="db-title">Submit Payment Proof</h1>
    <p class="db-subtitle">Upload transaction screenshots or deposit slips to verify your dues.</p>
</div>

<div style="max-width: 680px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">Transaction Proof Form</h3>
        
        <!-- Redirects back to bills with mock success alert -->
        <form action="{{ url('/resident/bills') }}" method="GET" enctype="multipart/form-data">
            <!-- Hidden session flag to trigger success message in bills page -->
            <input type="hidden" name="payment_submitted" value="1">
            
            <div class="grid grid-2">
                <!-- Bill reference -->
                <div class="form-group">
                    <label for="bill-ref" class="form-label">Bill Reference</label>
                    <input type="text" id="bill-ref" class="form-control" value="July 2026 Service Charge (#B-9872)" readonly style="background-color: var(--bg-main); font-weight: 600;">
                </div>
                
                <!-- Amount to pay -->
                <div class="form-group">
                    <label for="amount-paid" class="form-label">Amount Paid (৳)</label>
                    <input type="number" id="amount-paid" name="amount" class="form-control" value="4500" required>
                </div>
            </div>

            <div class="grid grid-2">
                <!-- Payment Method -->
                <div class="form-group">
                    <label for="pay-method" class="form-label">Payment Method</label>
                    <select id="pay-method" name="method" class="form-control form-select" required>
                        <option value="bank" selected>Bank Transfer (EFT/NPSB)</option>
                        <option value="bkash">bKash Mobile Wallet</option>
                        <option value="nagad">Nagad Mobile Wallet</option>
                        <option value="cash">Cash to Office Manager</option>
                    </select>
                </div>

                <!-- Transaction ID / Reference -->
                <div class="form-group">
                    <label for="trx-id" class="form-label">Transaction ID / Ref</label>
                    <input type="text" id="trx-id" name="transaction_id" class="form-control" placeholder="e.g. TXN987216A" required>
                </div>
            </div>

            <div class="form-group">
                <label for="pay-date" class="form-label">Payment Date</label>
                <input type="date" id="pay-date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <!-- Custom receipt uploader -->
            <div class="form-group">
                <label class="form-label">Upload Transaction Receipt Slip</label>
                <div class="file-upload-wrapper" id="file-drop-area" style="padding: 2rem;">
                    <input type="file" id="pay-receipt" name="receipt_file" class="file-upload-input" accept=".pdf,.png,.jpg,.jpeg" required>
                    <div class="file-upload-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 2.25rem; height: 2.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15M9 12l3 3m0 0l3-3m-3 3V2.25" />
                        </svg>
                        <span style="font-weight: 700; font-size: 0.95rem; margin-top: 0.25rem;">Click to browse receipt screenshot</span>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">PDF, JPG or PNG formats allowed (Max. 3MB)</span>
                        <span id="file-chosen-name" style="font-size: 0.8rem; color: var(--primary-color); font-weight: 700; display: none; margin-top: 0.5rem;"></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="pay-notes" class="form-label">Optional Comments</label>
                <textarea id="pay-notes" name="notes" class="form-control" rows="3" placeholder="Provide extra deposit remarks here..."></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <a href="{{ url('/resident/bills') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Submit Proof for Verification</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('pay-receipt');
        const fileNameSpan = document.getElementById('file-chosen-name');
        
        if (fileInput && fileNameSpan) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameSpan.textContent = 'Selected File: ' + this.files[0].name;
                    fileNameSpan.style.display = 'block';
                } else {
                    fileNameSpan.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
