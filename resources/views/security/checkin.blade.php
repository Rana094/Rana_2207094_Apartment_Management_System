@extends('layouts.dashboard')

@section('title', 'Visitor Check-In - Nestora')

@section('content')
<div class="db-header">
    <a href="{{ route('security.dashboard') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        Back to Gate Terminal
    </a>
    <h1 class="db-title">Visitor Check-In Registry</h1>
    <p class="db-subtitle">Register walk-in guests or verify pre-approved access codes for entry clearance.</p>
</div>

<div class="grid grid-2" style="align-items: start;">
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1.15rem;">Search Passcode</h3>

            {{-- GET lookup asks SecurityPortalController@checkin to find an approved visitor request by access code. --}}
            <form action="{{ route('security.checkin') }}" method="GET" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
                <input type="text" name="passcode" class="form-control" placeholder="Enter visitor access code" value="{{ request('passcode') }}" required style="font-weight: 700; text-transform: uppercase;">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            @if ($visitor)
                <div style="background-color: var(--bg-main); border: 1px solid var(--border-color); padding: 1.25rem; border-radius: var(--radius-md); border-left: 4px solid var(--color-approved);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge badge-approved">code verified</span>
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary-color);">{{ str_replace('_', ' ', $visitor->status) }}</span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">
                        <div>Visitor: <strong style="color: var(--text-primary); font-size: 0.95rem;">{{ $visitor->visitor_name }}</strong></div>
                        <div>Destination Flat: <strong style="color: var(--primary-color); font-weight: 700;">{{ $visitor->flat?->flat_number ?? '-' }} - {{ $visitor->resident?->name ?? '-' }}</strong></div>
                        <div>Phone Number: <strong style="color: var(--text-primary);">{{ $visitor->visitor_phone ?? '-' }}</strong></div>
                        <div>Visit Date: <strong style="color: var(--text-primary);">{{ $visitor->visit_date?->format('M d, Y') ?? '-' }}</strong></div>
                        <div style="border-top: 1px solid var(--border-color); padding-top: 0.5rem; margin-top: 0.25rem;">
                            Purpose: <span style="font-style: italic;">{{ $visitor->purpose ?? 'No purpose provided.' }}</span>
                        </div>
                    </div>

                    @if (! $visitor->checked_in_at)
                        {{-- POST check-in stores timestamps and creates the visitor_logs record shown in the log registry. --}}
                        <form action="{{ route('security.checkin.store') }}" method="POST" style="margin-top: 1.5rem;">
                            @csrf
                            <input type="hidden" name="passcode" value="{{ $visitor->access_code }}">
                            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700;">
                                Approve Entry & Check-In
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success" style="margin-top: 1.5rem;">Visitor already checked in at {{ $visitor->checked_in_at?->format('M d, Y H:i') }}.</div>
                    @endif
                </div>
            @elseif(request('passcode'))
                <div class="alert alert-danger" style="margin-bottom: 0;">
                    Code not found or expired. Please check and try again, or register manually.
                </div>
            @else
                <p style="font-size: 0.85rem; color: var(--text-secondary); text-align: center; margin: 2rem 0;">
                    Enter a resident-generated code to verify entry clearance details.
                </p>
            @endif
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">Manual Walk-In Entry</h3>

        {{-- Manual entry creates a visitor request and immediately logs the visitor as checked in. --}}
        <form action="{{ route('security.checkin.store') }}" method="POST">
            @csrf

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="man-name" class="form-label">Visitor Full Name</label>
                    <input type="text" id="man-name" name="name" class="form-control" placeholder="e.g. Robin Khan" required>
                </div>
                <div class="form-group">
                    <label for="man-phone" class="form-label">Phone Number</label>
                    <input type="tel" id="man-phone" name="phone" class="form-control" placeholder="e.g. +880 1711 000000">
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="man-cat" class="form-label">Visitor Category</label>
                    <select id="man-cat" name="category" class="form-control form-select">
                        <option value="guest">Personal Guest</option>
                        <option value="delivery" selected>Delivery / Courier</option>
                        <option value="service">Home Repair Technician</option>
                        <option value="other">Other Miscellaneous</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="man-plate" class="form-label">Vehicle Plate No <span class="text-muted" style="font-weight: normal;">(Optional)</span></label>
                    <input type="text" id="man-plate" name="vehicle_plate" class="form-control" placeholder="e.g. Dhaka Metro-Ka-9999">
                </div>
            </div>

            <div class="form-group">
                <label for="man-flat" class="form-label">Destination Flat Unit</label>
                <select id="man-flat" name="flat_id" class="form-control form-select" required>
                    <option value="" disabled selected>Select flat...</option>
                    @foreach ($flats as $flat)
                        <option value="{{ $flat->id }}">{{ $flat->building?->name ?? 'Building' }} - {{ $flat->flat_number }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="man-purpose" class="form-label">Purpose of Entry / Comments</label>
                <textarea id="man-purpose" name="purpose" class="form-control" rows="3" placeholder="e.g. Delivering package, electric socket repair..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                Log Entry & Check-In
            </button>
        </form>
    </div>
</div>
@endsection
