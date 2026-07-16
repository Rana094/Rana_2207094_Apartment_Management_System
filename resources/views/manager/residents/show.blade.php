@extends('layouts.dashboard')

@section('title', 'Resident Profile - Nestora')

@section('content')
@php
    $profile = $resident->residentProfile;
    $flat = $profile?->flat;
    $totalBilled = (float) $resident->bills->sum('amount');
    $totalPaid = (float) $resident->bills->where('status', 'paid')->sum('amount');
@endphp

<div class="db-header">
    <a href="{{ route('manager.residents.index') }}" style="display: inline-block; margin-bottom: .5rem;">&larr; Back to Residents</a>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h1 class="db-title">{{ $resident->name }}</h1>
        <span class="badge badge-{{ $resident->status === 'approved' ? 'approved' : 'pending-verification' }}">{{ str_replace('_', ' ', ucfirst($resident->status)) }}</span>
    </div>
</div>

<div class="grid grid-3" style="align-items: start;">
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card" style="text-align: center; padding: 2rem 1.5rem;">
            <div class="db-sidebar-avatar" style="width: 4.5rem; height: 4.5rem; font-size: 1.25rem; margin: 0 auto 1rem;">{{ strtoupper(substr($resident->name, 0, 2)) }}</div>
            <h3>{{ $resident->name }}</h3>
            <p class="text-muted">{{ ucfirst($profile?->resident_type ?? $resident->resident_type ?? 'resident') }}</p>
            <div style="border-top: 1px solid var(--border-color); margin-top: 1.5rem; padding-top: 1rem; text-align: left; display: flex; flex-direction: column; gap: .75rem; font-size: .875rem;">
                <div><span class="text-muted">Email:</span> <strong>{{ $resident->email }}</strong></div>
                <div><span class="text-muted">Phone:</span> <strong>{{ $resident->phone ?: 'Not provided' }}</strong></div>
                <div><span class="text-muted">Building:</span> <strong>{{ $flat?->building?->name ?? 'Unassigned' }}</strong></div>
                <div><span class="text-muted">Flat:</span> <strong>{{ $flat?->flat_number ?? $resident->flat_info ?? 'Unassigned' }}</strong></div>
                <div><span class="text-muted">Move in:</span> <strong>{{ $profile?->move_in_date?->format('M d, Y') ?? 'Not recorded' }}</strong></div>
            </div>
        </div>

        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Account Ledger</h3>
            <div style="display: flex; flex-direction: column; gap: .75rem;">
                <div style="display: flex; justify-content: space-between;"><span>Total billed</span><strong class="money"><x-taka />{{ number_format($totalBilled, 2) }}</strong></div>
                <div style="display: flex; justify-content: space-between;"><span>Total paid</span><strong class="money" style="color: var(--color-approved);"><x-taka />{{ number_format($totalPaid, 2) }}</strong></div>
                <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: .75rem;"><span>Outstanding</span><strong class="money" style="color: var(--color-rejected);"><x-taka />{{ number_format($totalBilled - $totalPaid, 2) }}</strong></div>
            </div>
        </div>
    </div>

    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Flat Members</h3>
            <div class="table-responsive"><table class="db-table">
                <thead><tr><th>Name</th><th>Relationship</th><th>Phone</th></tr></thead>
                <tbody>
                @forelse ($profile?->flatMembers ?? [] as $member)
                    <tr><td><strong>{{ $member->name }}</strong></td><td>{{ $member->relationship ?: '-' }}</td><td>{{ $member->phone ?: '-' }}</td></tr>
                @empty
                    <tr><td colspan="3" style="text-align: center; color: var(--text-muted);">No flat members recorded.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Registered Vehicles</h3>
            <div class="table-responsive"><table class="db-table">
                <thead><tr><th>Type</th><th>Vehicle</th><th>Registration</th><th>Parking</th><th>Status</th></tr></thead>
                <tbody>
                @forelse ($profile?->vehicleRegistrations ?? [] as $vehicle)
                    <tr><td>{{ ucfirst($vehicle->vehicle_type) }}</td><td>{{ trim(($vehicle->brand ?? '').' '.($vehicle->model ?? '')) ?: '-' }}</td><td><strong>{{ $vehicle->registration_number }}</strong></td><td>{{ $vehicle->parking_slot ?: '-' }}</td><td><span class="badge badge-approved">{{ ucfirst($vehicle->status) }}</span></td></tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No vehicles recorded.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Documents</h3>
            <div class="table-responsive"><table class="db-table">
                <thead><tr><th>Document</th><th>Type</th><th>Status</th><th style="text-align: right;">Action</th></tr></thead>
                <tbody>
                @forelse ($resident->documents as $document)
                    <tr><td><strong>{{ $document->title }}</strong></td><td>{{ str_replace('_', ' ', ucfirst($document->type)) }}</td><td><span class="badge badge-pending-verification">{{ str_replace('_', ' ', ucfirst($document->status)) }}</span></td><td style="text-align: right;"><a href="{{ $document->secureUrl() }}" class="btn btn-outline btn-sm">View</a></td></tr>
                @empty
                    <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No documents uploaded.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Maintenance Complaints</h3>
            <div class="table-responsive"><table class="db-table">
                <thead><tr><th>Ticket</th><th>Issue</th><th>Priority</th><th>Status</th><th style="text-align: right;">Action</th></tr></thead>
                <tbody>
                @forelse ($resident->complaints as $complaint)
                    <tr><td>#{{ $complaint->id }}</td><td><strong>{{ $complaint->title }}</strong></td><td>{{ ucfirst($complaint->priority) }}</td><td><span class="badge badge-in-progress">{{ str_replace('_', ' ', ucfirst($complaint->status)) }}</span></td><td style="text-align: right;"><a href="{{ route('manager.complaints.assign', $complaint) }}" class="btn btn-outline btn-sm">Assign</a></td></tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted);">No complaints submitted.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div>
    </div>
</div>
@endsection
