@extends('layouts.dashboard')

@section('title', 'Work Order #T-2033 — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/maintenance/dashboard') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Workspace
    </a>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; width: 100%;">
        <h1 class="db-title" style="font-size: 1.5rem;">Work Order #T-2033</h1>
        <span class="badge badge-in-progress" style="font-size: 0.85rem; padding: 0.4rem 1rem;">in progress</span>
    </div>
</div>

<div class="grid grid-3" style="align-items: start;">
    
    <!-- Left Column: Work Order details (Span 2) -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <!-- Problem Description -->
        <div class="card">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">Bathroom pipe leakage in master washroom</h3>
            
            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem; font-size: 0.85rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; flex-wrap: wrap;">
                <div><span class="text-muted">Target Flat:</span> <strong style="color: var(--primary-color);">Flat 3B (Building A)</strong></div>
                <div><span class="text-muted">Urgency:</span> <span class="badge badge-rejected" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">high priority</span></div>
                <div><span class="text-muted">Assigned Date:</span> <strong style="color: var(--text-primary);">July 02, 2026</strong></div>
                <div><span class="text-muted">Deadline Date:</span> <strong style="color: var(--color-rejected);">July 04, 2026</strong></div>
            </div>
            
            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Job Description:</h4>
            <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-secondary); margin-bottom: 1.5rem;">
                There is water leaking slowly from the joint underneath the washroom basin sink. It accumulates water on the bathroom tile floors overnight and is starting to seep near the bathroom door sill. Please check it immediately before it damages the bedroom hardwood flooring.
            </p>
            
            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Manager Dispatch Instructions:</h4>
            <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-secondary); margin-bottom: 0; font-style: italic; background-color: var(--bg-main); padding: 1rem; border-radius: var(--radius-md);">
                Please bring sink replacement seals. Ring bell or contact John Doe before visiting flat to confirm availability.
            </p>
        </div>

        <!-- Resident / Contact Info -->
        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">Resident Information</h3>
            <div class="grid grid-2" style="font-size: 0.875rem;">
                <div>
                    <span class="text-muted">Primary Tenant Name:</span>
                    <div style="font-weight: 700; color: var(--text-primary); font-size: 1rem; margin-top: 0.25rem;">John Doe</div>
                </div>
                <div>
                    <span class="text-muted">Contact Phone Number:</span>
                    <div style="font-weight: 700; color: var(--primary-color); font-size: 1rem; margin-top: 0.25rem;">+880 1711 223344</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Status & Action triggers -->
    <div style="grid-column: span 1; display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Quick Actions Card -->
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem; text-align: left;">Task Execution</h3>
            
            <a href="{{ url('/maintenance/orders/2033/update') }}" class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 0.75rem; font-weight: 700;">
                Update Task Status
            </a>
            
            <a href="{{ url('/maintenance/dashboard') }}" class="btn btn-outline" style="width: 100%; justify-content: center;">
                Back to Dashboard
            </a>
        </div>

        <!-- Task Timeline -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Job State Progress</h3>
            <ul class="steps-list" style="border: none; padding: 0; background: none; margin-bottom: 0;">
                <li class="step-item completed">
                    <span class="step-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 0.75rem; height: 0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    </span>
                    <div>
                        <div class="step-title">Ticket Dispatched</div>
                        <div class="step-desc">July 2, 2026 by Manager</div>
                    </div>
                </li>
                <li class="step-item active">
                    <span class="step-badge">2</span>
                    <div>
                        <div class="step-title">In Progress</div>
                        <div class="step-desc">Technician is resolving faucet joints.</div>
                    </div>
                </li>
                <li class="step-item">
                    <span class="step-badge">3</span>
                    <div>
                        <div class="step-title">Resolved / Complete</div>
                        <div class="step-desc">Requires upload of completion photos.</div>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</div>
@endsection
