@extends('layouts.dashboard')

@section('title', 'Manager Dashboard - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Building Management Dashboard</h1>
    <p class="db-subtitle">Live occupancy, billing, visitor, complaint, and approval information.</p>
</div>

@if ($activeEmergencies->isNotEmpty())
    <div class="card" style="margin-bottom: 2rem; border: 2px solid #fda4af;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: var(--color-emergency);">Active Emergency Alerts</h3>
            <a href="{{ route('manager.emergencies.index') }}" class="btn btn-danger btn-sm">Open Dispatch Panel</a>
        </div>
        @foreach ($activeEmergencies as $emergency)
            <div style="display: flex; justify-content: space-between; gap: 1rem; padding: .75rem 0; border-top: 1px solid var(--border-color);">
                <div><strong>#AL-{{ $emergency->id }} {{ ucfirst($emergency->type) }}</strong><div class="text-muted text-xs">{{ $emergency->resident?->name }} - Flat {{ $emergency->flat?->flat_number ?? 'unassigned' }}</div></div>
                <span class="badge badge-emergency">{{ str_replace('_', ' ', $emergency->status) }}</span>
            </div>
        @endforeach
    </div>
@endif

<div class="grid grid-4" style="margin-bottom: 2rem;">
    <a href="{{ route('manager.residents.index') }}" class="stat-card" style="color: inherit; text-decoration: none;">
        <div class="stat-card-left"><span class="stat-label-text">Residents</span><span class="stat-val">{{ $stats['residents'] }}</span></div>
    </a>
    <a href="{{ route('manager.flats.index') }}" class="stat-card" style="color: inherit; text-decoration: none;">
        <div class="stat-card-left">
            <span class="stat-label-text">Occupied Flats</span>
            <span class="stat-val">{{ $stats['occupied_flats'] }}</span>
            <span style="font-size: .75rem; color: var(--text-secondary);">{{ $stats['available_flats'] }} available for signup</span>
            @if (($stats['reserved_flats'] ?? 0) > 0)
                <span style="font-size: .75rem; color: var(--color-pending-verify);">{{ $stats['reserved_flats'] }} pending approval</span>
            @endif
        </div>
    </a>
    <a href="{{ route('manager.bills.index') }}" class="stat-card" style="color: inherit; text-decoration: none;">
        <div class="stat-card-left">
            <span class="stat-label-text">Collected Revenue</span>
            <span class="stat-val money"><x-taka />{{ number_format((float) $stats['revenue'], 2) }}</span>
            <span style="font-size: .75rem; color: var(--color-rejected);">{{ $stats['unpaid_bills'] }} unpaid bills</span>
        </div>
    </a>
    <a href="{{ route('manager.resident-approvals.index') }}" class="stat-card" style="color: inherit; text-decoration: none;">
        <div class="stat-card-left"><span class="stat-label-text">Pending Approvals</span><span class="stat-val">{{ $stats['pending_approvals'] }}</span></div>
    </a>
</div>

<div class="grid grid-3" style="align-items: start;">
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem;">Pending Resident Approvals</h3>
                <a href="{{ route('manager.resident-approvals.index') }}">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="db-table">
                    <thead><tr><th>Resident</th><th>Requested Flat</th><th>Type</th><th style="text-align: right;">Action</th></tr></thead>
                    <tbody>
                    @forelse ($pendingResidents as $resident)
                        <tr>
                            <td><strong>{{ $resident->name }}</strong><div style="font-size: .75rem; color: var(--text-muted);">{{ $resident->email }}</div></td>
                            <td>
                                @if ($resident->requestedFlat)
                                    {{ $resident->requestedFlat->building?->name ?? 'Building' }}, Flat {{ $resident->requestedFlat->flat_number }}
                                @else
                                    {{ $resident->flat_info ?: 'Not provided' }}
                                @endif
                            </td>
                            <td><span class="badge badge-pending-verification">{{ ucfirst($resident->resident_type ?: 'resident') }}</span></td>
                            <td style="text-align: right;"><a href="{{ route('manager.resident-approvals.index') }}" class="btn btn-primary btn-sm">Review</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No approvals are pending.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.2rem;">Priority Complaints</h3>
                <a href="{{ route('manager.complaints.index') }}">View All</a>
            </div>
            <div class="table-responsive">
                <table class="db-table">
                    <thead><tr><th>Flat</th><th>Issue</th><th>Priority</th><th style="text-align: right;">Action</th></tr></thead>
                    <tbody>
                    @forelse ($urgentComplaints as $complaint)
                        <tr>
                            <td>{{ $complaint->flat?->flat_number ?? 'Unassigned' }}</td>
                            <td><strong>{{ $complaint->title }}</strong><div style="font-size: .75rem; color: var(--text-muted);">{{ $complaint->resident?->name }}</div></td>
                            <td><span class="badge badge-rejected">{{ ucfirst($complaint->priority) }}</span></td>
                            <td style="text-align: right;"><a href="{{ route('manager.complaints.assign', $complaint) }}" class="btn btn-outline btn-sm">Assign Staff</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No priority complaints need assignment.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Management Tools</h3>
            <div style="display: flex; flex-direction: column; gap: .75rem;">
                <a href="{{ route('manager.bills.generate') }}" class="btn btn-primary" style="justify-content: center;">Generate Bills</a>
                <a href="{{ route('manager.notices.index') }}" class="btn btn-outline" style="justify-content: center;">Publish Notice</a>
                <a href="{{ route('manager.flats.create') }}" class="btn btn-outline" style="justify-content: center;">Add Flat</a>
            </div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Today's Operations</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem; font-size: .9rem;">
                <div style="display: flex; justify-content: space-between;"><span>Visitors</span><strong>{{ $stats['today_visitors'] }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span>Open complaints</span><strong>{{ $stats['open_complaints'] }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span>Unpaid bills</span><strong>{{ $stats['unpaid_bills'] }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection
