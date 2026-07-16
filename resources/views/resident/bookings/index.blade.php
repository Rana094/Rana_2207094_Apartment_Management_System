@extends('layouts.dashboard')

@section('title', 'My Facility Bookings - Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Facility Bookings</h1>
        <p class="db-subtitle">View your reservation history and submit new facility requests.</p>
    </div>
    <a href="{{ route('resident.bookings.create') }}" class="btn btn-primary">Book a Facility</a>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
@endif

<div class="table-responsive">
    <table class="db-table">
        <thead><tr><th>Booking</th><th>Facility</th><th>Date</th><th>Time</th><th>Fee</th><th>Purpose</th><th>Status</th></tr></thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td><strong>#BK-{{ $booking->id }}</strong></td>
                    <td>{{ $booking->facility?->name ?? 'Unknown facility' }}</td>
                    <td>{{ $booking->booking_date?->format('M d, Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($booking->end_time)->format('g:i A') }}</td>
                    <td><span class="money"><x-taka />{{ number_format((float) ($booking->facility?->booking_fee ?? 0), 2) }}</span></td>
                    <td>{{ $booking->purpose ?: '-' }}</td>
                    <td><span class="badge badge-{{ $booking->status === 'approved' ? 'approved' : ($booking->status === 'rejected' ? 'rejected' : 'pending-verification') }}">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">No facility bookings submitted.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if ($bookings->hasPages())
        <div class="table-pagination" style="margin-top: 1rem;">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
