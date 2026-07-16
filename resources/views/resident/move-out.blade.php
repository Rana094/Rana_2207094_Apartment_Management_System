@extends('layouts.dashboard')

@section('title', 'Move-Out Request Clearance — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Move-Out Clearance Request</h1>
    <p class="db-subtitle">Submit your notice of intent to vacate your flat unit and request service fee clearance from management.</p>
</div>

<div class="grid grid-3" style="align-items: start;">
    
    <!-- Left Column: Clearance Checklist & Rules (1 Column) -->
    <div class="card" style="grid-column: span 1; background-color: var(--primary-light); border-color: rgba(79,70,229,0.15);">
        <h3 style="font-size: 1.1rem; color: var(--primary-color); margin-bottom: 1rem;">Vacating Checklist</h3>
        <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 1.25rem;">
            Before a clearance certificate is issued by the building manager, ensure the following steps are satisfied:
        </p>
        
        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-primary); display: flex; flex-direction: column; gap: 0.75rem; line-height: 1.4;">
            <li>All utility dues (electricity, water, gas) are paid in full.</li>
            <li>Monthly society service charges are cleared up to the move-out month.</li>
            <li>No open damage disputes exist in your flat unit.</li>
            <li>RFID parking tags and common gate keys are returned to the guard office.</li>
        </ul>
        
        <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 1.5rem; border-top: 1px solid rgba(79,70,229,0.15); padding-top: 1rem;">
            Notice period requirement: <strong>30 Days</strong> prior to vacating.
        </div>
    </div>

    <!-- Right Column: Move Out Form (2 Columns) -->
    <div class="card" style="grid-column: span 2;">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.25rem;">Notice of Intent Form</h3>
        
        <form action="{{ route('resident.move-out.store') }}" method="POST" id="moveout-form">
            @csrf
            
            <div class="grid grid-2">
                <!-- Vacating Date -->
                <div class="form-group">
                    <label for="move-date" class="form-label">Planned Move-Out Date</label>
                    <input type="date" id="move-date" name="move_out_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                </div>
                
                <!-- Reason -->
                <div class="form-group">
                    <label for="move-reason" class="form-label">Reason for Vacating</label>
                    <select id="move-reason" name="reason" class="form-control form-select" required>
                        <option value="" disabled selected>Select reason...</option>
                        <option value="lease_ended">Rental Lease Agreement Expired</option>
                        <option value="relocated">Work Relocation / City Change</option>
                        <option value="sold">Flat Sold to New Owner</option>
                        <option value="other">Other Personal Reasons</option>
                    </select>
                </div>
            </div>

            <!-- Forwarding Address -->
            <div class="form-group">
                <label for="move-address" class="form-label">Forwarding Address <span class="text-muted" style="font-weight: normal;">(For security refund receipt dispatch)</span></label>
                <textarea id="move-address" name="forwarding_address" class="form-control" rows="3" placeholder="Enter your upcoming residential address..." required></textarea>
            </div>

            <!-- Dues Acknowledgement -->
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="remember-label" style="align-items: flex-start;">
                    <input type="checkbox" name="dues_ack" class="form-checkbox" style="margin-top: 0.25rem;" required>
                    <span style="font-size: 0.85rem; line-height: 1.4; color: var(--text-secondary);">
                        I acknowledge that my security gate RFID tag will be automatically deactivated at 11:59 PM on my designated move-out date. I agree to pay any outstanding society bills prior to leaving.
                    </span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center;">
                Submit Notice of Vacating
            </button>
        </form>

        <div style="margin-top: 1.5rem;">
            <h4 style="margin-bottom: .75rem;">Request History</h4>
            @forelse ($moveOutRequests as $request)
                <div class="card-static" style="margin-bottom: .75rem;">
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <strong>{{ $request->requested_move_out_date?->format('M d, Y') }}</strong>
                        <span class="badge badge-pending-verification">{{ $request->status }}</span>
                    </div>
                    <p style="margin:.5rem 0 0; color: var(--text-secondary); font-size:.85rem;">{{ $request->reason ?: 'No reason provided.' }}</p>
                </div>
            @empty
                <p style="font-size:.85rem; color: var(--text-muted);">No move-out requests submitted yet.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
