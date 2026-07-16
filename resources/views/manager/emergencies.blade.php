@extends('layouts.dashboard')

@section('title', 'Emergency Alerts - Nestora')

@section('content')
<div class="db-header"><h1 class="db-title" style="color:var(--color-emergency);">Emergency Dispatch Panel</h1><p class="db-subtitle">Monitor and resolve resident emergency alerts.</p></div>
@if(session('status'))<div class="alert alert-success" style="margin-bottom:1rem;">{{ session('status') }}</div>@endif
<div class="table-responsive">
    <table class="db-table">
        <thead><tr><th>Alert</th><th>Flat</th><th>Resident</th><th>Type</th><th>Created</th><th>Status</th><th style="text-align:right;">Action</th></tr></thead>
        <tbody>
            @forelse($emergencies as $emergency)
                <tr><td style="font-weight:700;">#AL-{{ $emergency->id }}</td><td>{{ $emergency->flat?->flat_number ?? '-' }}</td><td>{{ $emergency->resident?->name ?? '-' }}</td><td>{{ ucfirst($emergency->type) }}</td><td>{{ $emergency->created_at?->format('M d, Y H:i') }}</td><td><span class="badge badge-{{ $emergency->status === 'resolved' ? 'resolved' : 'emergency' }}">{{ str_replace('_',' ',$emergency->status) }}</span></td><td style="text-align:right;">@if($emergency->status !== 'resolved')<form method="POST" action="{{ route('manager.emergencies.status',$emergency) }}">@csrf<input type="hidden" name="status" value="resolved"><button class="btn btn-primary btn-sm">Resolve Alert</button></form>@else<span class="text-muted text-xs">Resolved</span>@endif</td></tr>
            @empty<tr><td colspan="7" style="text-align:center;padding:2rem;">No emergency alerts.</td></tr>@endforelse
        </tbody>
    </table>
    <div class="table-pagination"><div class="pagination-info">{{ $emergencies->total() }} alerts</div><div class="pagination-btns">@if($emergencies->previousPageUrl())<a href="{{ $emergencies->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>@endif @if($emergencies->nextPageUrl())<a href="{{ $emergencies->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>@endif</div></div>
</div>
@endsection
