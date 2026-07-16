@extends('layouts.dashboard')

@section('title', 'Facility Bookings - Nestora')

@section('content')
<div class="db-header"><h1 class="db-title">Amenity Reservations Queue</h1><p class="db-subtitle">Approve or reject resident facility requests.</p></div>
@if(session('status'))<div class="alert alert-success" style="margin-bottom:1rem;">{{ session('status') }}</div>@endif
<div class="table-responsive">
    <table class="db-table">
        <thead><tr><th>Reference</th><th>Resident</th><th>Facility</th><th>Date & Time</th><th>Purpose</th><th>Status</th><th style="text-align:right;">Actions</th></tr></thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td style="font-weight:700;">#BK-{{ $booking->id }}</td><td>{{ $booking->resident?->name ?? '-' }}</td><td>{{ $booking->facility?->name ?? '-' }}</td>
                    <td>{{ $booking->booking_date?->format('M d, Y') }}<div class="text-muted text-xs">{{ $booking->start_time }} - {{ $booking->end_time }}</div></td><td>{{ $booking->purpose ?? '-' }}</td>
                    <td><span class="badge badge-{{ $booking->status === 'approved' ? 'approved' : 'pending' }}">{{ $booking->status }}</span></td>
                    <td style="text-align:right;">
                        @if($booking->status === 'pending')
                            <div style="display:inline-flex;gap:.5rem;">
                                <form method="POST" action="{{ route('manager.bookings.status',$booking) }}">@csrf<input type="hidden" name="status" value="approved"><button class="btn btn-primary btn-sm">Approve</button></form>
                                <form id="reject-booking-{{ $booking->id }}" method="POST" action="{{ route('manager.bookings.status',$booking) }}">@csrf<input type="hidden" name="status" value="rejected"><button type="button" class="btn btn-danger btn-sm" onclick="showConfirmModal('Reject booking?', 'Reject this facility request?', function(){ document.getElementById('reject-booking-{{ $booking->id }}').submit(); }, true)">Reject</button></form>
                            </div>
                        @else<span class="text-muted text-xs">Decision recorded</span>@endif
                    </td>
                </tr>
            @empty<tr><td colspan="7" style="text-align:center;padding:2rem;">No booking requests.</td></tr>@endforelse
        </tbody>
    </table>
    <div class="table-pagination"><div class="pagination-info">{{ $bookings->total() }} bookings</div><div class="pagination-btns">@if($bookings->previousPageUrl())<a href="{{ $bookings->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>@endif @if($bookings->nextPageUrl())<a href="{{ $bookings->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>@endif</div></div>
</div>
@endsection
