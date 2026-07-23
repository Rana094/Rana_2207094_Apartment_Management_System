@extends('layouts.dashboard')

@section('title', 'Generate Bills - Nestora')

@section('content')
<div class="db-header">
    <a href="{{ route('manager.bills.index') }}" style="font-size: 0.85rem; font-weight: 600;">Back to Billing Ledger</a>
    <h1 class="db-title">Generate Dues & Invoices</h1>
    <p class="db-subtitle">Issue a bill to all approved residents or target one occupied flat.</p>
</div>

<div style="max-width: 760px; margin: 0 auto;">
    <div class="card">
        @if ($errors->any()) <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ $errors->first() }}</div> @endif

        {{-- StoreBillRequest validates this form before ManagerPortalController creates bill records and payment transactions. --}}
        <form action="{{ route('manager.bills.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="remember-label">
                    <input type="checkbox" id="bulk-billing-chk" name="bulk_billing" value="1" class="form-checkbox" @checked(old('bulk_billing', true))>
                    Bill every approved resident
                </label>
            </div>

            <div id="single-target-select-container" class="form-group" style="display: none;">
                <label for="bill-target-flat" class="form-label">Target Flat</label>
                <select id="bill-target-flat" name="target_flat_id" class="form-control form-select">
                    <option value="">Choose flat</option>
                    @foreach ($flats as $flat)
                        @php($resident = $flat->residentProfiles->first()?->user)
                        <option value="{{ $flat->id }}" @selected(old('target_flat_id') == $flat->id)>{{ $flat->flat_number }}{{ $resident ? ' - '.$resident->name : '' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="bill-category" class="form-label">Category</label>
                    <select id="bill-category" name="category" class="form-control form-select" required>
                        @foreach (['monthly_service_charge' => 'Monthly Service Charge', 'electricity' => 'Electricity', 'gas' => 'Gas', 'water' => 'Water', 'other' => 'Other'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="bill-period" class="form-label">Billing Period</label>
                    <input type="month" id="bill-period" name="period" class="form-control" value="{{ old('period', date('Y-m')) }}" required>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="bill-duedate" class="form-label">Due Date</label>
                    <input type="date" id="bill-duedate" name="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                </div>
                <div class="form-group">
                    <label for="bill-amount" class="form-label">Amount <span class="money"><x-taka /></span></label>
                    <input type="number" id="bill-amount" name="amount" class="form-control" min="0" step="0.01" value="{{ old('amount') }}" required>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('manager.bills.index') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Generate & Publish</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('bulk-billing-chk');
    const target = document.getElementById('single-target-select-container');
    // Shows the single-flat selector only when the manager is not generating bills for every resident.
    const sync = () => target.style.display = checkbox.checked ? 'none' : 'block';
    checkbox.addEventListener('change', sync);
    sync();
});
</script>
@endsection
