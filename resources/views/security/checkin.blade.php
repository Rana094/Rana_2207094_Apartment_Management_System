@extends('layouts.dashboard')

@section('title', 'Visitor Check-In — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/security/dashboard') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Gate Terminal
    </a>
    <h1 class="db-title">Visitor Check-In Registry</h1>
    <p class="db-subtitle">Register walk-in guests or verify pre-approved access codes for entry clearance.</p>
</div>

<div class="grid grid-2" style="align-items: start;">
    
    <!-- Left Column: Passcode Verification & Search Results -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1.15rem;">Search Passcode</h3>
            
            <form action="{{ url('/security/checkin') }}" method="GET" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
                <input type="text" name="passcode" class="form-control" placeholder="e.g. N-5509" value="{{ request('passcode') }}" required style="font-weight: 700; text-transform: uppercase;">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            
            @if(request('passcode') == 'N-5509')
                <!-- Pre-approved Visitor Details Card -->
                <div style="background-color: var(--bg-main); border: 1px solid var(--border-color); padding: 1.25rem; border-radius: var(--radius-md); border-left: 4px solid var(--color-approved);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge badge-approved">code verified</span>
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary-color);">Pre-Approved</span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">
                        <div>Visitor: <strong style="color: var(--text-primary); font-size: 0.95rem;">Farhan Alvi</strong></div>
                        <div>Category: <strong style="color: var(--text-primary);">Food Delivery (Foodpanda)</strong></div>
                        <div>Destination Flat: <strong style="color: var(--primary-color); font-weight: 700;">Flat 3B — John Doe</strong></div>
                        <div>Phone Number: <strong style="color: var(--text-primary);">+880 1812 998877</strong></div>
                        <div>Vehicle: <strong style="color: var(--text-primary);">Dhaka Metro-Ha-1234</strong></div>
                        <div style="border-top: 1px solid var(--border-color); padding-top: 0.5rem; margin-top: 0.25rem;">
                            Remarks: <span style="font-style: italic;">"Delivering lunch pack. Please let rider enter elevator."</span>
                        </div>
                    </div>
                    
                    <form action="{{ url('/security/dashboard') }}" method="GET" style="margin-top: 1.5rem;">
                        <input type="hidden" name="checkin_success" value="1">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700;">
                            Approve Entry & Check-In
                        </button>
                    </form>
                </div>
            @elseif(request('passcode') && request('passcode') != 'N-5509')
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

    <!-- Right Column: Manual Walk-In Registry Form -->
    <div class="card">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">Manual Walk-In Entry</h3>
        
        <form action="{{ url('/security/dashboard') }}" method="GET">
            <input type="hidden" name="manual_checkin" value="1">
            
            <div class="grid grid-2">
                <div class="form-group">
                    <label for="man-name" class="form-label">Visitor Full Name</label>
                    <input type="text" id="man-name" name="name" class="form-control" placeholder="e.g. Robin Khan" required>
                </div>
                <div class="form-group">
                    <label for="man-phone" class="form-label">Phone Number</label>
                    <input type="tel" id="man-phone" name="phone" class="form-control" placeholder="e.g. +880 1711 000000" required>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="man-cat" class="form-label">Visitor Category</label>
                    <select id="man-cat" name="category" class="form-control form-select" required>
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
                    <option value="1">Flat 3B — John Doe</option>
                    <option value="2">Flat 5A — Karim Alvi</option>
                    <option value="3">Flat 4D — Vacant Unit</option>
                </select>
            </div>

            <div class="form-group">
                <label for="man-purpose" class="form-label">Purpose of Entry / Comments</label>
                <textarea id="man-purpose" name="purpose" class="form-control" rows="3" placeholder="e.g. Delivering package, electric socket repair..." required></textarea>
            </div>

            <div style="background-color: var(--bg-main); padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.8rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.5rem;">
                <strong>Gate Security Note:</strong> For manual registrations, verify the visitor's national ID card or delivery app manifest screen before clearance.
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                Log Entry & Check-In
            </button>
        </form>
    </div>

</div>
@endsection
