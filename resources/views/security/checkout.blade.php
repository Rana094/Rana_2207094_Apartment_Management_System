@extends('layouts.dashboard')

@section('title', 'Visitor Check-Out — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/security/dashboard') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Gate Terminal
    </a>
    <h1 class="db-title">Visitor Check-Out Exit Registry</h1>
    <p class="db-subtitle">Register visitor exits and release their entry gate pass codes.</p>
</div>

<div class="panic-container" style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem; text-align: center;">Find Checked-In Visitor</h3>
        
        <form action="{{ url('/security/checkout') }}" method="GET" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="text" name="passcode" class="form-control" placeholder="Enter Passcode (e.g. N-5509)" value="{{ request('passcode') }}" required style="font-weight: 700; text-transform: uppercase; text-align: center;">
            <button type="submit" class="btn btn-primary">Lookup</button>
        </form>
        
        @if(request('passcode') == 'N-5509')
            <!-- Active Visitor Record Card -->
            <div style="background-color: var(--bg-main); border: 1px solid var(--border-color); padding: 1.5rem; border-radius: var(--radius-md); text-align: left;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <div>
                        <strong style="font-size: 1.15rem; color: var(--text-primary);">Farhan Alvi</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Category: Delivery (Foodpanda)</div>
                    </div>
                    <span class="badge badge-unpaid" style="background-color: var(--secondary-light); color: var(--secondary-color); font-size: 0.8rem;">Currently Inside</span>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.85rem; color: var(--text-secondary);">
                    <div>Destination Unit: <strong style="color: var(--text-primary);">Flat 3B — John Doe</strong></div>
                    <div>Check-In Timestamp: <strong style="color: var(--text-primary);">Today, 10:12 AM</strong></div>
                    <div>Duration Inside: <strong style="color: var(--primary-color);">2 Hours, 15 Mins</strong></div>
                    <div>Vehicle Number: <strong style="color: var(--text-primary);">Dhaka Metro-Ha-1234</strong></div>
                </div>
                
                <form action="{{ url('/security/dashboard') }}" method="GET" style="margin-top: 1.5rem;">
                    <input type="hidden" name="checkout_success" value="1">
                    <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center; font-weight: 700; background-color: var(--color-emergency); border-color: var(--color-emergency);">
                        Approve Check-Out & Register Exit
                    </button>
                </form>
            </div>
        @elseif(request('passcode'))
            <div class="alert alert-danger" style="margin-bottom: 0; text-align: center;">
                No active check-in record found for this passcode.
            </div>
        @else
            <p style="font-size: 0.85rem; color: var(--text-secondary); text-align: center; margin: 2rem 0;">
                Input a visitor passcode (e.g. <code>N-5509</code>) to load their active entry file.
            </p>
        @endif
    </div>
</div>
@endsection
