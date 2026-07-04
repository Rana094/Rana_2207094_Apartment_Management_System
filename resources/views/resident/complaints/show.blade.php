@extends('layouts.dashboard')

@section('title', 'Complaint Ticket Details #T-2033 — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/resident/complaints') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Complaints
    </a>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; width: 100%;">
        <h1 class="db-title" style="font-size: 1.5rem;">Complaint Ticket #T-2033</h1>
        <span class="badge badge-in-progress" style="font-size: 0.85rem; padding: 0.4rem 1rem;">in progress</span>
    </div>
</div>

<div class="grid grid-3" style="align-items: start;">
    
    <!-- Left Column: Details & Assigned Staff (Span 2) -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <!-- Core Details -->
        <div class="card">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">Bathroom pipe leakage in master washroom</h3>
            
            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem; font-size: 0.85rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; flex-wrap: wrap;">
                <div><span class="text-muted">Category:</span> <strong style="color: var(--text-primary);">Plumbing</strong></div>
                <div><span class="text-muted">Urgency:</span> <span class="badge badge-rejected" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">high urgency</span></div>
                <div><span class="text-muted">Location:</span> <strong style="color: var(--text-primary);">Master washroom (Unit 3B)</strong></div>
                <div><span class="text-muted">Filed On:</span> <strong style="color: var(--text-primary);">July 02, 2026</strong></div>
            </div>
            
            <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary); margin-bottom: 0;">
                There is water leaking slowly from the joint underneath the washroom basin sink. It accumulates water on the bathroom tile floors overnight and is starting to seep near the bathroom door sill. Please check it immediately before it damages the bedroom hardwood flooring.
            </p>
        </div>

        <!-- Discussion Log / Conversation -->
        <div class="card">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Discussion History</h3>
            
            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                <!-- Comment 1: Resident -->
                <div style="background-color: var(--primary-light); padding: 1rem; border-radius: var(--radius-md); align-self: flex-start; max-width: 80%;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--primary-color); margin-bottom: 0.25rem;">John Doe (You)</div>
                    <p style="font-size: 0.85rem; color: var(--text-primary); margin-bottom: 0;">Water is collecting on the floor. I placed a bucket underneath the pipe for now, but it fills up every few hours.</p>
                    <div style="font-size: 0.7rem; color: var(--text-secondary); text-align: right; margin-top: 0.25rem;">July 02, 2026 at 10:15 AM</div>
                </div>

                <!-- Comment 2: Technician -->
                <div style="background-color: #f1f5f9; padding: 1rem; border-radius: var(--radius-md); align-self: flex-end; max-width: 80%;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--secondary-color); margin-bottom: 0.25rem;">Ali Khan (Plumber Technician)</div>
                    <p style="font-size: 0.85rem; color: var(--text-primary); margin-bottom: 0;">Hello John, I have received the ticket. I will visit your flat between 2:00 PM and 4:00 PM today with replacement seal joints. Please ensure someone is at home.</p>
                    <div style="font-size: 0.7rem; color: var(--text-secondary); text-align: right; margin-top: 0.25rem;">July 03, 2026 at 09:30 AM</div>
                </div>
            </div>
            
            <!-- Send Comment Form -->
            <form action="#" method="POST" style="border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                @csrf
                <div class="form-group">
                    <label for="new-comment" class="form-label" style="font-size: 0.85rem;">Post Reply to Technician</label>
                    <textarea id="new-comment" class="form-control" rows="3" placeholder="Write a reply..."></textarea>
                </div>
                <div style="text-align: right;">
                    <button type="button" class="btn btn-primary btn-sm" onclick="alert('Mock: Comment posted.');">Send Reply</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Status Tracker & Assigned Staff Info -->
    <div style="grid-column: span 1; display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Status Timeline Card -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Ticket Timeline</h3>
            <ul class="steps-list" style="border: none; padding: 0; background: none; margin-bottom: 0;">
                <li class="step-item completed">
                    <span class="step-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 0.75rem; height: 0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    </span>
                    <div>
                        <div class="step-title">Ticket Filed</div>
                        <div class="step-desc">July 2, 2026 at 09:00 AM</div>
                    </div>
                </li>
                <li class="step-item completed">
                    <span class="step-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 0.75rem; height: 0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    </span>
                    <div>
                        <div class="step-title">Technician Assigned</div>
                        <div class="step-desc">July 2, 2026 at 11:30 AM</div>
                    </div>
                </li>
                <li class="step-item active">
                    <span class="step-badge">3</span>
                    <div>
                        <div class="step-title">In Progress</div>
                        <div class="step-desc">Technician is resolving the pipe joint.</div>
                    </div>
                </li>
                <li class="step-item">
                    <span class="step-badge">4</span>
                    <div>
                        <div class="step-title">Resolved / Complete</div>
                        <div class="step-desc">Pending technician proof upload.</div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Assigned Staff Info -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Assigned Technician</h3>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                <div class="db-sidebar-avatar" style="width: 3rem; height: 3rem; background-color: var(--secondary-color); font-size: 1.1rem;">AK</div>
                <div>
                    <div style="font-weight: 700; color: var(--text-primary); font-size: 0.95rem;">Ali Khan</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">Senior Plumber Staff</div>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem; border-top: 1px solid var(--border-color); padding-top: 0.75rem;">
                <div><span class="text-muted">Staff ID:</span> <strong>#ST-0992</strong></div>
                <div><span class="text-muted">Phone:</span> <strong>+880 1711 098765</strong></div>
            </div>
        </div>

    </div>
</div>
@endsection
