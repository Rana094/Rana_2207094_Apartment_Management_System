@extends('layouts.dashboard')

@section('title', 'Generate Visitor Pass — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/resident/visitors') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Visitors
    </a>
    <h1 class="db-title">Create pre-approved visitor pass</h1>
    <p class="db-subtitle">Register a visitor to generate a passcode that guards will verify at the building main gate.</p>
</div>

<div style="max-width: 680px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">New Visitor Pass Details</h3>
        
        <form action="{{ route('resident.visitors.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-2">
                <!-- Visitor Name -->
                <div class="form-group">
                    <label for="vis-name" class="form-label">Visitor Full Name</label>
                    <input type="text" id="vis-name" name="name" class="form-control" placeholder="e.g. Kabir Ahmad" required>
                </div>
                
                <!-- Phone Number -->
                <div class="form-group">
                    <label for="vis-phone" class="form-label">Visitor Phone Number</label>
                    <input type="tel" id="vis-phone" name="phone" class="form-control" placeholder="e.g. +880 1812 345678" required>
                </div>
            </div>

            <div class="grid grid-2">
                <!-- Visitor Type -->
                <div class="form-group">
                    <label for="vis-type" class="form-label">Visitor Category</label>
                    <select id="vis-type" name="type" class="form-control form-select" required>
                        <option value="" disabled selected>Select category...</option>
                        <option value="guest">Guest / Personal Visitor</option>
                        <option value="delivery">Delivery Rider (Food/Parcel)</option>
                        <option value="service">Home Repair / Cleaner / Service</option>
                        <option value="taxi">Ride Share (Uber/Pathao)</option>
                    </select>
                </div>

                <!-- Vehicle License Plate -->
                <div class="form-group">
                    <label for="vis-plate" class="form-label">Vehicle Number <span class="text-muted" style="font-weight: normal;">(Optional)</span></label>
                    <input type="text" id="vis-plate" name="vehicle_plate" class="form-control" placeholder="e.g. Dhaka Metro-Ga-11-2233">
                </div>
            </div>

            <div class="grid grid-2">
                <!-- Date -->
                <div class="form-group">
                    <label for="vis-date" class="form-label">Scheduled Date</label>
                    <input type="date" id="vis-date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Time -->
                <div class="form-group">
                    <label for="vis-time" class="form-label">Expected Arrival Time</label>
                    <input type="time" id="vis-time" name="time" class="form-control" value="12:00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="vis-purpose" class="form-label">Purpose of Visit / Special Notes</label>
                <textarea id="vis-purpose" name="purpose" class="form-control" rows="3" placeholder="e.g. Delivering a large couch, family dinner party, fixing kitchen lights..."></textarea>
            </div>

            <div style="background-color: var(--primary-light); padding: 1.25rem; border-radius: var(--radius-md); border: 1px dashed rgba(79,70,229,0.25); margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">
                <strong>Note:</strong> Generating this pass creates a gate access code in the database which you can share with your guest. Once the security guard registers their entry with this code, the visitor log is updated.
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ url('/resident/visitors') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Generate Access Passcode</button>
            </div>
        </form>
    </div>
</div>
@endsection
