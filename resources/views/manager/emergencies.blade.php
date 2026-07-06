@extends('layouts.dashboard')

@section('title', 'Emergency Alerts Log — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title" style="color: var(--color-emergency);">Emergency dispatch panel</h1>
    <p class="db-subtitle">Monitor society panic alarms, coordinate responder dispatch, and declare resolved statuses.</p>
</div>

<!-- Active Sirens Warning Banner -->
<div class="alert alert-danger" style="margin-bottom: 2rem; background-color: #fff1f2; border: 1px solid #fecdd3; display: flex; align-items: center; gap: 1rem;">
    <div style="width: 2.5rem; height: 2.5rem; border-radius: var(--radius-full); background-color: var(--color-emergency); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; animation: pulse 2s infinite;">
        🚨
    </div>
    <div>
        <strong style="color: #9f1239;">Active Emergency Alert!</strong>
        <p style="margin: 0.15rem 0 0 0; font-size: 0.85rem; color: #be123c;">Unit 3B (John Doe) has triggered a Medical Emergency. Guard Ali Khan has been dispatched.</p>
    </div>
</div>

<!-- Log Table -->
<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Alarm ID</th>
                <th>Target Unit</th>
                <th>Resident Name</th>
                <th>Alert Type</th>
                <th>Dispatch Time</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: rgba(244, 63, 94, 0.02);">
                <td style="font-weight: 700; color: var(--color-emergency);">#AL-9088</td>
                <td style="font-weight: 700;">Flat 3B</td>
                <td style="font-weight: 600;">John Doe</td>
                <td><span class="badge badge-emergency" style="font-size: 0.75rem;">Medical Emergency</span></td>
                <td>Today, 10:12 PM</td>
                <td><span class="badge badge-emergency" style="font-size: 0.7rem; animation: pulse 1.5s infinite; border: 1px solid #fda4af;">active alarm</span></td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 0.5rem;">
                        <button type="button" class="btn btn-primary btn-sm" style="background-color: var(--color-emergency); border-color: var(--color-emergency);" onclick="alert('Alarm marked resolved.');">Resolve Alert</button>
                    </div>
                </td>
            </tr>
            <tr>
                <td>#AL-8890</td>
                <td style="font-weight: 700;">Flat 4D</td>
                <td style="font-weight: 600;">Sumaiya Karim</td>
                <td><span class="badge badge-pending" style="background-color: #fee2e2; color: #ef4444;">Severe Gas Leak</span></td>
                <td>July 01, 2026, 09:15 AM</td>
                <td><span class="badge badge-completed" style="background-color: var(--border-color); color: var(--text-secondary);">resolved</span></td>
                <td style="text-align: right;">
                    <span class="text-muted text-xs">Resolved (Duty Guard Ali)</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
