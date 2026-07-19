@extends('layouts.dashboard')

@section('title', 'My Complaints - Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Maintenance Complaints</h1>
        <p class="db-subtitle">Report maintenance issues in your flat or common areas and track resolution progress.</p>
    </div>

    <a href="{{ route('resident.complaints.create') }}" class="btn btn-primary">File New Complaint</a>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
@endif

<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 200px;" disabled>
                <option value="">All Categories</option>
            </select>
            <select class="form-control form-select" style="max-width: 200px;" disabled>
                <option value="">All Statuses</option>
            </select>
        </div>
    </div>

    <table class="db-table">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Subject / Issue</th>
                <th>Category</th>
                <th>Filed Date</th>
                <th>Urgency</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($complaints as $complaint)
                <tr>
                    <td style="font-weight: 700;">#T-{{ $complaint->id }}</td>
                    <td style="font-weight: 600;">
                        {{ $complaint->title }}
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ str($complaint->description)->limit(90) }}</div>
                    </td>
                    <td>{{ ucfirst($complaint->category ?? 'General') }}</td>
                    <td>{{ $complaint->created_at?->format('M d, Y') ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ in_array($complaint->priority, ['high', 'urgent', 'emergency'], true) ? 'rejected' : 'pending' }}" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">
                            {{ $complaint->priority }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $complaint->status === 'resolved' ? 'resolved' : ($complaint->status === 'open' ? 'pending' : 'in-progress') }}">
                            {{ str_replace('_', ' ', $complaint->status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('resident.complaints.show', $complaint) }}" class="btn btn-outline btn-sm">View Status</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">No maintenance complaints submitted yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>{{ $complaints->count() }}</strong> of <strong>{{ $complaints->total() }}</strong> tickets</div>
        <div class="pagination-btns">
            @if ($complaints->previousPageUrl())
                <a href="{{ $complaints->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>
            @else
                <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            @endif

            @if ($complaints->nextPageUrl())
                <a href="{{ $complaints->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>
            @else
                <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
            @endif
        </div>
    </div>
</div>
@endsection
