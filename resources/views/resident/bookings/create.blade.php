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
                        <option
                            value="{{ $facility->id }}"
                            data-name="{{ $facility->name }}"
                            data-fee="{{ $facility->booking_fee }}"
                            @selected((string) old('facility_id') === (string) $facility->id)
                        >
                            {{ $facility->name === 'Gym' ? 'Gym Monthly Subscription' : $facility->name }} - {{ number_format((float) $facility->booking_fee, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="rooftop-weather-panel" style="display:none; background:#ecfeff; border:1px solid #a5f3fc; border-radius:var(--radius-md); color:#155e75; padding:1rem; margin-bottom:1rem;">
                <strong>Rooftop weather check</strong>
                <div id="rooftop-weather-content" style="font-size:.9rem; margin-top:.5rem;">Checking current weather...</div>
            </div>

            <div id="gym-subscription-panel" style="display:none; background:#f8fafc; border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1rem; margin-bottom:1rem;">
                <strong>Monthly subscription request</strong>
                <p style="font-size:.9rem; color:var(--text-secondary); margin:.35rem 0 0;">After manager approval, a Tk 3,000 gym subscription bill will be generated in your dashboard. You cannot submit another gym request while one is pending or approved.</p>
            </div>

            <div class="grid grid-3">
                <div class="form-group">
                    <label for="booking-date" class="form-label">Booking Date</label>
                    <input type="date" id="booking-date" name="booking_date" class="form-control" min="{{ now()->toDateString() }}" value="{{ old('booking_date', now()->addDays(3)->toDateString()) }}" required>
                </div>
                <div class="form-group booking-time-field">
                    <label for="booking-start" class="form-label">Start Time</label>
                    <input type="time" id="booking-start" name="start_time" class="form-control" value="{{ old('start_time', '16:00') }}" required>
                </div>
                <div class="form-group booking-time-field">
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
    const weatherPanel = document.getElementById('rooftop-weather-panel');
    const weatherContent = document.getElementById('rooftop-weather-content');
    const gymPanel = document.getElementById('gym-subscription-panel');
    const timeFields = document.querySelectorAll('.booking-time-field');
    const startInput = document.getElementById('booking-start');
    const endInput = document.getElementById('booking-end');
    const takaIcon = @json(asset('icons/taka.png'));
    const weatherUrl = @json(route('resident.bookings.weather.current'));
    const syncFee = function () {
        const option = select.options[select.selectedIndex];
        const amount = Number(option?.dataset.fee || 0);
        const name = option?.dataset.name || '';
        fee.innerHTML = '<img src="' + takaIcon + '" alt="BDT" class="taka-icon">' + amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        weatherPanel.style.display = name.includes('Rooftop') ? 'block' : 'none';
        gymPanel.style.display = name === 'Gym' ? 'block' : 'none';
        timeFields.forEach((field) => field.style.display = name === 'Gym' ? 'none' : 'block');
        startInput.required = name !== 'Gym';
        endInput.required = name !== 'Gym';

        if (name.includes('Rooftop')) {
            fetchWeather();
        }
    };

    const fetchWeather = function () {
        weatherContent.textContent = 'Checking current weather...';

        fetch(weatherUrl, {
            headers: { 'Accept': 'application/json' },
        })
            .then((response) => response.json())
            .then((data) => {
                if (!data.available) {
                    weatherContent.textContent = data.message || 'Weather data is unavailable.';
                    return;
                }

                weatherContent.innerHTML =
                    '<div><strong>' + data.location + '</strong>: ' + data.description + '</div>' +
                    '<div>Temperature: ' + data.temperature + '&deg;C, feels like ' + data.feels_like + '&deg;C</div>' +
                    '<div>Humidity: ' + data.humidity + '%, wind: ' + data.wind_speed + ' m/s</div>' +
                    '<div style="margin-top:.35rem;">' + data.safety_message + '</div>';
            })
            .catch(() => {
                weatherContent.textContent = 'Weather data is temporarily unavailable.';
            });
    };

    select.addEventListener('change', syncFee);
    syncFee();
});
</script>
@endsection
