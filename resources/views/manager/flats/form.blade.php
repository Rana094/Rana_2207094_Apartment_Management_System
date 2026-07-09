@extends('layouts.dashboard')

@section('title', isset($flat) ? 'Edit Unit — Nestora' : 'Register Flat Unit — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/manager/flats') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Unit Registry
    </a>
    <h1 class="db-title">{{ isset($flat) ? 'Modify Flat Details' : 'Register New Flat Unit' }}</h1>
    <p class="db-subtitle">Enter structural, configuration, and parking allocations for the building unit.</p>
</div>

<div style="max-width: 720px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">Unit Configuration Form</h3>
        
        <form action="{{ url('/manager/flats') }}" method="GET">
            <input type="hidden" name="flat_saved" value="1">
            
            <div class="grid grid-2">
                <!-- Flat Name -->
                <div class="form-group">
                    <label for="flat-no" class="form-label">Flat / Unit Number</label>
                    <input type="text" id="flat-no" name="number" class="form-control" value="{{ isset($flat) ? 'Flat 3B' : '' }}" placeholder="e.g. Flat 3B" required>
                </div>

                <!-- Building Block -->
                <div class="form-group">
                    <label for="flat-block" class="form-label">Building Block / Tower</label>
                    <select id="flat-block" name="block" class="form-control form-select" required>
                        <option value="" disabled {{ !isset($flat) ? 'selected' : '' }}>Select Tower...</option>
                        <option value="A" {{ isset($flat) && $flat['block'] == 'A' ? 'selected' : '' }} selected>Building A (Tower 1)</option>
                        <option value="B" {{ isset($flat) && $flat['block'] == 'B' ? 'selected' : '' }}>Building B (Tower 2)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-3">
                <!-- Floor Level -->
                <div class="form-group">
                    <label for="flat-floor" class="form-label">Floor level</label>
                    <input type="number" id="flat-floor" name="floor" class="form-control" value="{{ isset($flat) ? '3' : '' }}" placeholder="e.g. 3" min="1" max="25" required>
                </div>

                <!-- Sq Ft -->
                <div class="form-group">
                    <label for="flat-size" class="form-label">Square Footage</label>
                    <input type="number" id="flat-size" name="size" class="form-control" value="{{ isset($flat) ? '1650' : '' }}" placeholder="e.g. 1650" required>
                </div>

                <!-- Parking Slot -->
                <div class="form-group">
                    <label for="flat-parking" class="form-label">Assigned Parking Slot</label>
                    <input type="text" id="flat-parking" name="parking_slot" class="form-control" value="{{ isset($flat) ? 'Slot P-88' : '' }}" placeholder="e.g. Slot P-88">
                </div>
            </div>

            <div class="grid grid-3">
                <!-- Bed count -->
                <div class="form-group">
                    <label for="flat-beds" class="form-label">Bedrooms Count</label>
                    <select id="flat-beds" name="beds" class="form-control form-select">
                        <option value="1">1 Bed</option>
                        <option value="2">2 Bed</option>
                        <option value="3" selected>3 Bed</option>
                        <option value="4">4 Bed</option>
                    </select>
                </div>

                <!-- Bath count -->
                <div class="form-group">
                    <label for="flat-baths" class="form-label">Bathrooms Count</label>
                    <select id="flat-baths" name="baths" class="form-control form-select">
                        <option value="1">1 Bath</option>
                        <option value="2">2 Bath</option>
                        <option value="3" selected>3 Bath</option>
                        <option value="4">4 Bath</option>
                    </select>
                </div>

                <!-- Occupancy status -->
                <div class="form-group">
                    <label for="flat-occupancy" class="form-label">Occupancy State</label>
                    <select id="flat-occupancy" name="occupancy" class="form-control form-select" required>
                        <option value="vacant" {{ isset($flat) && $flat['occupancy'] == 'vacant' ? 'selected' : '' }}>Vacant / Empty</option>
                        <option value="owner" {{ isset($flat) && $flat['occupancy'] == 'owner' ? 'selected' : '' }} selected>Occupied by Owner</option>
                        <option value="tenant" {{ isset($flat) && $flat['occupancy'] == 'tenant' ? 'selected' : '' }}>Occupied by Tenant</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <a href="{{ url('/manager/flats') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">
                    {{ isset($flat) ? 'Update Unit Registry' : 'Register Apartment Unit' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
