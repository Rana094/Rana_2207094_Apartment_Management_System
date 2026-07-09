@extends('layouts.dashboard')

@section('title', 'Generate Bills — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/manager/dashboard') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Dashboard
    </a>
    <h1 class="db-title">Generate Dues & Invoices</h1>
    <p class="db-subtitle">Issue monthly society service charges or utility bills to specific flats or bulk-distribute to all occupied units.</p>
</div>

<div style="max-width: 760px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">Invoice Dispatch Engine</h3>
        
        <form action="{{ url('/manager/bills') }}" method="GET">
            <!-- Hidden session success flag -->
            <input type="hidden" name="bill_generated" value="1">
            
            <!-- Billing Target -->
            <div class="form-group">
                <label class="form-label">Billing Target Units</label>
                <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <label class="remember-label" style="align-items: center;">
                        <input type="checkbox" id="bulk-billing-chk" name="bulk_billing" class="form-checkbox" checked style="margin-right: 0.5rem;">
                        <strong>Bulk Billing:</strong> Apply invoice to all occupied flat units (111 units).
                    </label>
                </div>
                
                <div id="single-target-select-container" style="display: none; margin-top: 0.75rem;">
                    <label for="bill-target-flat" class="form-label" style="font-size: 0.8rem;">Select Target Unit</label>
                    <select id="bill-target-flat" name="target_flat_id" class="form-control form-select">
                        <option value="" disabled selected>Choose unit...</option>
                        <option value="1">Flat 3B — John Doe</option>
                        <option value="2">Flat 5A — Karim Alvi</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-3">
                <!-- Bill Category -->
                <div class="form-group">
                    <label for="bill-category" class="form-label">Billing Category</label>
                    <select id="bill-category" name="category" class="form-control form-select" required>
                        <option value="service" selected>Monthly Service Charge</option>
                        <option value="electricity">Utility (Electricity)</option>
                        <option value="gas">Utility (Gas Supply)</option>
                        <option value="water">Utility (Water Share)</option>
                        <option value="other">Special Levies / Event fund</option>
                    </select>
                </div>

                <!-- Billing Period -->
                <div class="form-group">
                    <label for="bill-period" class="form-label">Billing Period</label>
                    <input type="month" id="bill-period" name="period" class="form-control" value="{{ date('Y-m') }}" required>
                </div>

                <!-- Due date -->
                <div class="form-group">
                    <label for="bill-duedate" class="form-label">Due Date</label>
                    <input type="date" id="bill-duedate" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                </div>
            </div>

            <div class="grid grid-2">
                <!-- Total Amount -->
                <div class="form-group">
                    <label for="bill-amount" class="form-label">Total Amount Dues (৳)</label>
                    <input type="number" id="bill-amount" name="amount" class="form-control" placeholder="e.g. 4500" value="4500" required>
                </div>

                <!-- Late payment penalty -->
                <div class="form-group">
                    <label for="bill-penalty" class="form-label">Late Payment Fine (৳) <span class="text-muted" style="font-weight: normal;">(Optional)</span></label>
                    <input type="number" id="bill-penalty" name="penalty" class="form-control" placeholder="e.g. 500" value="200">
                </div>
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="bill-notes" class="form-label">Invoice Remarks / Description</label>
                <textarea id="bill-notes" name="notes" class="form-control" rows="3" placeholder="Itemized breakdown or payment notes to display in the invoice..."></textarea>
            </div>

            <div style="background-color: var(--bg-main); border: 1px dashed var(--border-color); padding: 1rem; border-radius: var(--radius-md); font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.5rem;">
                <strong>Warning:</strong> Clicking publish will generate new billing invoices in the active ledger, update residents' balances, and issue push notifications to the resident portal instant dashboard feeds.
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ url('/manager/dashboard') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Generate & Publish Invoices</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bulkChk = document.getElementById('bulk-billing-chk');
        const selectContainer = document.getElementById('single-target-select-container');
        
        if (bulkChk && selectContainer) {
            bulkChk.addEventListener('change', function() {
                if (this.checked) {
                    selectContainer.style.display = 'none';
                } else {
                    selectContainer.style.display = 'block';
                }
            });
        }
    });
</script>
@endsection
