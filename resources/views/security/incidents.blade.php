@extends('layouts.dashboard')

@section('title', 'Security Incidents Log — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Security Incidents Directory</h1>
    <p class="db-subtitle">Register and review security incident reports, noise complaints, or parking disputes.</p>
</div>

<div class="grid grid-3" style="align-items: start;">
    
    <!-- Left Column: Incident reporting Form (1 Column) -->
    <div class="card" style="grid-column: span 1;">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">File Incident Report</h3>
        
        <form action="#" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="inc-title" class="form-label">Incident Subject</label>
                <input type="text" id="inc-title" name="subject" class="form-control" placeholder="e.g. Unauthorized parking block" required>
            </div>
            
            <div class="form-group">
                <label for="inc-cat" class="form-label">Incident Category</label>
                <select id="inc-cat" name="category" class="form-control form-select" required>
                    <option value="parking" selected>Parking Dispute / Blockage</option>
                    <option value="noise">Noise Complaint / Disturbance</option>
                    <option value="theft">Theft / Vandalism / Damage</option>
                    <option value="suspicious">Suspicious Activity / Person</option>
                    <option value="other">Other Incident</option>
                </select>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="inc-date" class="form-label">Date</label>
                    <input type="date" id="inc-date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label for="inc-time" class="form-label">Time</label>
                    <input type="time" id="inc-time" name="time" class="form-control" value="{{ date('H:i') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="inc-flat" class="form-label">Involved Flat Unit <span class="text-muted" style="font-weight: normal;">(Optional)</span></label>
                <select id="inc-flat" name="flat_id" class="form-control form-select">
                    <option value="" selected>Select involved flat...</option>
                    <option value="1">Flat 3B — John Doe</option>
                    <option value="2">Flat 5A — Karim Alvi</option>
                </select>
            </div>

            <div class="form-group">
                <label for="inc-desc" class="form-label">Incident Details / Description</label>
                <textarea id="inc-desc" name="description" class="form-control" rows="4" placeholder="Describe the incident, names involved, and immediate actions taken by gate security..." required></textarea>
            </div>

            <button type="button" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="alert('Security incident report logged and supervisor alerted.');">
                Log Incident Report
            </button>
        </form>
    </div>

    <!-- Right Column: Incident log list table (2 Columns) -->
    <div class="table-responsive" style="grid-column: span 2;">
        <div class="table-toolbar">
            <div class="table-toolbar-left">
                <select class="form-control form-select" style="max-width: 200px;">
                    <option value="">All Categories</option>
                    <option value="parking">Parking</option>
                    <option value="noise">Noise</option>
                    <option value="theft">Theft</option>
                </select>
            </div>
        </div>
        
        <table class="db-table">
            <thead>
                <tr>
                    <th>Incident Subject</th>
                    <th>Category</th>
                    <th>Date & Time</th>
                    <th>Involved Flat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: 600;">
                        Basement parking slot block
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Delivery van parked in Slot P-88 without RFID clearance tag. Owner notified.</div>
                    </td>
                    <td>Parking dispute</td>
                    <td>Today, 08:30 AM</td>
                    <td style="font-weight: 600;">Flat 3B (John Doe)</td>
                    <td><span class="badge badge-completed" style="background-color: var(--border-color); color: var(--text-secondary);">resolved</span></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">
                        Loud music disturbance
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Loud party noise after 11:00 PM. Guard visited corridor and requested volume control.</div>
                    </td>
                    <td>Noise disturbance</td>
                    <td>July 08, 2026</td>
                    <td style="font-weight: 600;">Flat 5A (Karim Alvi)</td>
                    <td><span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d;">logged</span></td>
                </tr>
            </tbody>
        </table>
        
        <div class="table-pagination">
            <div class="pagination-info">Showing <strong>2</strong> incident logs</div>
            <div class="pagination-btns">
                <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
                <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
            </div>
        </div>
    </div>

</div>
@endsection
