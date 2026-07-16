@extends('layouts.dashboard')

@section('title', 'Book a Facility - Nestora')

@section('content')
<div class="db-header">
    <a href="{{ route('resident.bookings.index') }}" style="display: inline-block; margin-bottom: .5rem;">&larr; Back to Bookings</a>
    <h1 class="db-title">Book a Shared Facility</h1>
    <p class="db-subtitle">Submit a reservation request for manager approval.</p>
</div>

<div style="max-width: 720px; margin: 0 auto;">
    <div class="card">
        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('resident.bookings.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="booking-facility" class="form-label">Facility</label>
                <select id="booking-facility" name="facility_id" class="form-control form-select" required>
                    <option value="">Select a facility</option>
                    @foreach ($facilities as $facility)
                        <option value="{{ $facility->id }}" data-fee="{{ $facility->booking_fee }}" @selected((string) old('facility_id') === (string) $facility->id)>
                            {{ $facility->name }} - {{ number_format((float) $facility->booking_fee, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-3">
                <div class="form-group">
                    <label for="booking-date" class="form-label">Booking Date</label>
                    <input type="date" id="booking-date" name="booking_date" class="form-control" min="{{ now()->toDateString() }}" value="{{ old('booking_date', now()->addDays(3)->toDateString()) }}" required>
                </div>
                <div class="form-group">
                    <label for="booking-start" class="form-label">Start Time</label>
                    <input type="time" id="booking-start" name="start_time" class="form-control" value="{{ old('start_time', '16:00') }}" required>
                </div>
                <div class="form-group">
                    <label for="booking-end" class="form-label">End Time</label>
                    <input type="time" id="booking-end" name="end_time" class="form-control" value="{{ old('end_time', '21:00') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="booking-purpose" class="form-label">Purpose</label>
                <textarea id="booking-purpose" name="purpose" class="form-control" rows="4" maxlength="255" placeholder="Describe the event or reason for booking.">{{ old('purpose') }}</textarea>
            </div>

            <div style="background: var(--bg-main); border: 1px solid var(--border-color); padding: 1rem; margin-bottom: 1.5rem;">
                <span>Facility fee:</span>
                <strong id="selected-facility-fee" class="money" style="float: right;"><x-taka />0.00</strong>
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('resident.bookings.index') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Submit Booking Request</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('booking-facility');
    const fee = document.getElementById('selected-facility-fee');
    const takaIcon = @json(asset('icons/taka.png'));
    const syncFee = function () {
        const option = select.options[select.selectedIndex];
        const amount = Number(option?.dataset.fee || 0);
        fee.innerHTML = '<img src="' + takaIcon + '" alt="BDT" class="taka-icon">' + amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };
    select.addEventListener('change', syncFee);
    syncFee();
});
</script>
@endsection
