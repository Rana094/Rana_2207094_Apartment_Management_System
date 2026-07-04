@extends('layouts.dashboard')

@section('title', 'Book a Facility — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/resident/bookings') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Bookings
    </a>
    <h1 class="db-title">Book a Shared Facility</h1>
    <p class="db-subtitle">Reserve society spaces for private events, family functions, or fitness access.</p>
</div>

<div style="max-width: 720px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">Reservation Form</h3>
        
        <form action="{{ url('/resident/bookings') }}" method="GET">
            <!-- Hidden session flag to trigger success alert -->
            <input type="hidden" name="booking_submitted" value="1">
            
            <!-- Select Facility -->
            <div class="form-group">
                <label for="book-facility" class="form-label">Select Amenity / Space</label>
                <select id="book-facility" name="facility" class="form-control form-select" required>
                    <option value="" disabled selected>Select space...</option>
                    <option value="hall" data-price="5000">Community Banquet Hall (৳5,000 / shift)</option>
                    <option value="bbq" data-price="1500">Rooftop BBQ Grill Station (৳1,500 / shift)</option>
                    <option value="gym" data-price="1000">Fitness Gym Club Access (৳1,000 / month)</option>
                </select>
            </div>

            <div class="grid grid-2">
                <!-- Date -->
                <div class="form-group">
                    <label for="book-date" class="form-label">Reservation Date</label>
                    <input type="date" id="book-date" name="date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                </div>

                <!-- Shift -->
                <div class="form-group">
                    <label for="book-shift" class="form-label">Time Shift</label>
                    <select id="book-shift" name="shift" class="form-control form-select" required>
                        <option value="morning">Morning Shift (09:00 AM - 02:00 PM)</option>
                        <option value="evening" selected>Evening Shift (04:00 PM - 09:00 PM)</option>
                        <option value="fullday">Full Day Slot (09:00 AM - 10:00 PM)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="book-guests" class="form-label">Approximate Number of Guests</label>
                <input type="number" id="book-guests" name="guests" class="form-control" placeholder="e.g. 50" min="1" required>
            </div>

            <div class="form-group">
                <label for="book-purpose" class="form-label">Purpose of Reservation / Notes</label>
                <textarea id="book-purpose" name="purpose" class="form-control" rows="3" placeholder="e.g. Daughter's 10th birthday party, family dinner meetup..."></textarea>
            </div>

            <!-- Dynamic Cost Summary Card -->
            <div style="background-color: var(--bg-main); border: 1px solid var(--border-color); padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.9rem; color: var(--text-primary); margin-bottom: 0.5rem;">Reservation Fee Breakdown</h4>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.25rem;">
                    <span>Base Facility Fee:</span>
                    <span id="base-fee">৳ 0</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-secondary); border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 0.5rem;">
                    <span>Cleaners Levy:</span>
                    <span>৳ 0</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1rem; color: var(--primary-color);">
                    <span>Total Amount Dues:</span>
                    <span id="total-fee">৳ 0</span>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ url('/resident/bookings') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Submit Booking Request</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const facilitySelect = document.getElementById('book-facility');
        const baseFeeSpan = document.getElementById('base-fee');
        const totalFeeSpan = document.getElementById('total-fee');

        if (facilitySelect && baseFeeSpan && totalFeeSpan) {
            facilitySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                
                if (price) {
                    const priceVal = parseInt(price);
                    baseFeeSpan.textContent = '৳ ' + priceVal.toLocaleString();
                    totalFeeSpan.textContent = '৳ ' + priceVal.toLocaleString();
                } else {
                    baseFeeSpan.textContent = '৳ 0';
                    totalFeeSpan.textContent = '৳ 0';
                }
            });
        }
    });
</script>
@endsection
