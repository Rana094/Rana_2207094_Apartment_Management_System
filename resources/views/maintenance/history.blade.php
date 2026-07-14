@extends('layouts.dashboard')

@section('title', 'Repair History — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Repair History Archive</h1>
    <p class="db-subtitle">Review historical logs of resolved maintenance tickets and repair jobs completed by you.</p>
</div>

<!-- History Table -->
<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Destination Unit</th>
                <th>Subject & Category</th>
                <th>Completion Date</th>
                <th>Urgency Level</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">#T-1804</td>
                <td style="font-weight: 700;">Flat 3B<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Building A (Tower 1)</div></td>
                <td>
                    <div style="font-weight: 700;">Intercom handset static noise</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Electrical Specialty</div>
                </td>
                <td>June 18, 2026</td>
                <td><span class="badge badge-pending" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">medium</span></td>
                <td><span class="badge badge-resolved">resolved</span></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="alert('Viewing completed task record #T-1804.');">View Record</button>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">#T-1192</td>
                <td style="font-weight: 700;">Flat 2A<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Building A (Tower 1)</div></td>
                <td>
                    <div style="font-weight: 700;">Rusted balcony safety grill painting</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">General Carpentry</div>
                </td>
                <td>May 12, 2026</td>
                <td><span class="badge badge-pending" style="font-size: 0.7rem; padding: 0.15rem 0.5rem; background-color: #f3f4f6; color: var(--text-secondary);">low</span></td>
                <td><span class="badge badge-resolved">resolved</span></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="alert('Viewing completed task record #T-1192.');">View Record</button>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>2</strong> resolved logs of <strong>15</strong> total records</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
