@extends('layouts.dashboard')

@section('title', 'Complaints Registry - Nestora')

@section('content')
<div class="db-header"><h1 class="db-title">Maintenance Complaints Registry</h1><p class="db-subtitle">Delegate resident issues to maintenance staff.</p></div>
@if(session('status'))<div class="alert alert-success" style="margin-bottom:1rem;">{{ session('status') }}</div>@endif
<div class="table-responsive">
    <table class="db-table">
        <thead><tr><th>Ticket</th><th>Resident / Flat</th><th>Issue</th><th>Priority</th><th>Technician</th><th>Status</th><th style="text-align:right;">Action</th></tr></thead>
        <tbody>
            @forelse($complaints as $complaint)
                @php($workOrder = $complaint->workOrders->sortByDesc('created_at')->first())
                <tr>
                    <td style="font-weight:700;">#{{ $complaint->id }}</td>
                    <td>{{ $complaint->resident?->name ?? '-' }}<div class="text-muted text-xs">{{ $complaint->flat?->flat_number ?? 'No flat' }}</div></td>
                    <td><strong>{{ $complaint->title }}</strong><div class="text-muted text-xs">{{ $complaint->category ?? 'General' }}</div></td>
                    <td><span class="badge badge-pending">{{ $complaint->priority }}</span></td>
                    <td>{{ $workOrder?->assignedStaff?->name ?? 'Unassigned' }}</td>
                    <td><span class="badge badge-in-progress">{{ str_replace('_',' ',$complaint->status) }}</span></td>
                    <td style="text-align:right;"><a href="{{ route('manager.complaints.assign', $complaint) }}" class="btn btn-outline btn-sm">{{ $workOrder ? 'Reassign' : 'Assign Staff' }}</a></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;padding:2rem;">No complaints found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="table-pagination"><div class="pagination-info">{{ $complaints->total() }} complaints</div><div class="pagination-btns">@if($complaints->previousPageUrl())<a href="{{ $complaints->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>@endif @if($complaints->nextPageUrl())<a href="{{ $complaints->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>@endif</div></div>
</div>
@endsection
