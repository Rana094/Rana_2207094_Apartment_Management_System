@extends('layouts.dashboard')

@section('title', 'Staff Dashboard — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Maintenance Work Workspace</h1>
    <p class="db-subtitle">View your assigned repair tickets, update statuses, and log resolved work order details.</p>
</div>

<!-- Operational Stat Cards Grid -->
<div class="grid grid-3" style="margin-bottom: 2rem;">
    <!-- Active Tasks -->
    <div class="stat-card" style="border-left: 4px solid var(--color-in-progress);">
        <div class="stat-card-left">
            <span class="stat-label-text">Active Work Orders</span>
            <span class="stat-val" style="font-size: 1.6rem; color: var(--color-in-progress);">1 Active Task</span>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Assigned and in progress</span>
        </div>
    </div>

    <!-- Completed Tasks -->
    <div class="stat-card" style="border-left: 4px solid var(--color-approved);">
        <div class="stat-card-left">
            <span class="stat-label-text">Resolved Tasks</span>
            <span class="stat-val" style="font-size: 1.6rem; color: var(--color-approved);">15 Resolved</span>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">All time history logs</span>
        </div>
    </div>

    <!-- Overdue Tasks -->
    <div class="stat-card" style="border-left: 4px solid var(--color-rejected);">
        <div class="stat-card-left">
            <span class="stat-label-text">Overdue Deadlines</span>
            <span class="stat-val" style="font-size: 1.6rem; color: var(--color-rejected);">0 Overdue</span>
            <span style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Healthy response time</span>
        </div>
    </div>
</div>

<!-- Assigned Work Orders List -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <span>Assigned Work Orders</span>
        <span class="badge badge-in-progress" style="font-size: 0.75rem;">1 pending repair</span>
    </h3>
    
    <div class="table-responsive">
        <table class="db-table" style="font-size: 0.875rem;">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Destination Unit</th>
                    <th>Issue Summary</th>
                    <th>Deadline</th>
                    <th>Urgency Priority</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: rgba(79, 70, 229, 0.015);">
                    <td style="font-weight: 700;">#T-2033</td>
                    <td style="font-weight: 700;">Flat 3B<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Building A (Tower 1)</div></td>
                    <td style="font-weight: 600;">Bathroom pipe leakage in master washroom</td>
                    <td>July 04, 2026</td>
                    <td><span class="badge badge-rejected" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">high</span></td>
                    <td><span class="badge badge-in-progress">in progress</span></td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem;">
                            <a href="{{ url('/maintenance/orders/2033') }}" class="btn btn-primary btn-sm" style="padding: 0.25rem 0.5rem;">View Task Details</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Notice updates for staff -->
<div class="card" style="padding: 1.5rem;">
    <h3 style="font-size: 1.15rem; margin-bottom: 1rem; color: var(--primary-color);">Staff Safety Bulletins</h3>
    <div style="display: flex; gap: 1rem; align-items: start; background-color: var(--bg-main); padding: 1rem; border-radius: var(--radius-md);">
        <div style="font-size: 1.5rem; flex-shrink: 0;">⚠️</div>
        <div>
            <strong style="font-size: 0.85rem; color: var(--text-primary);">Rooftop Access Warning</strong>
            <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.15rem; line-height: 1.4;">
                Due to water pump maintenance, the rooftop elevator access lobby will be closed. Please use the stairwell doors for direct rooftop access.
            </p>
        </div>
    </div>
</div>
@endsection
