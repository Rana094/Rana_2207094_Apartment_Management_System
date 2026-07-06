@extends('layouts.dashboard')

@section('title', 'Assign Work Order #T-2033 — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/manager/complaints') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Complaints Registry
    </a>
    <h1 class="db-title">Assign Repair Technician</h1>
    <p class="db-subtitle">Dispatch building maintenance technicians and set task deadlines.</p>
</div>

<div style="max-width: 680px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">Work Order Dispatch Form</h3>
        
        <form action="{{ url('/manager/complaints') }}" method="GET">
            <!-- Hidden session success flag -->
            <input type="hidden" name="assigned" value="1">
            
            <!-- Complaint summary read only -->
            <div class="form-group">
                <label class="form-label">Selected Issue Ticket</label>
                <input type="text" class="form-control" value="Bathroom pipe leakage (#T-2033) — Flat 3B" readonly style="background-color: var(--bg-main); font-weight: 600;">
            </div>

            <!-- Technician select list -->
            <div class="form-group">
                <label for="assign-staff" class="form-label">Select Repair Technician</label>
                <select id="assign-staff" name="technician_id" class="form-control form-select" required>
                    <option value="" disabled selected>Select staff...</option>
                    <option value="1">Ali Khan — Plumber (Active, 0 active work orders)</option>
                    <option value="2">Hasan Kabir — Electrician (Active, 1 active work order)</option>
                    <option value="3">Abul Kalam — Carpenter (Active, 0 active work orders)</option>
                </select>
            </div>

            <div class="grid grid-2">
                <!-- Urgency override -->
                <div class="form-group">
                    <label for="assign-urgency" class="form-label">Urgency Level</label>
                    <select id="assign-urgency" name="urgency" class="form-control form-select" required>
                        <option value="low">Low (Routine)</option>
                        <option value="medium">Medium (Standard)</option>
                        <option value="high" selected>High (Urgent Repair)</option>
                    </select>
                </div>

                <!-- Deadline -->
                <div class="form-group">
                    <label for="assign-deadline" class="form-label">Resolution Deadline</label>
                    <input type="date" id="assign-deadline" name="deadline" class="form-control" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                </div>
            </div>

            <!-- Instructions -->
            <div class="form-group">
                <label for="assign-instruct" class="form-label">Special Dispatch Instructions</label>
                <textarea id="assign-instruct" name="instructions" class="form-control" rows="4" placeholder="e.g. Please bring sink replacement seals. Ring bell or contact John Doe before visiting flat..."></textarea>
            </div>

            <div style="background-color: var(--primary-light); padding: 1.25rem; border-radius: var(--radius-md); border: 1px dashed rgba(79,70,229,0.25); margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5;">
                <strong>Note:</strong> Dispatching this task will change the ticket status to <strong>In Progress</strong> and push it directly onto the designated technician's active mobile workspace.
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ url('/manager/complaints') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Dispatch Technician</button>
            </div>
        </form>
    </div>
</div>
@endsection
