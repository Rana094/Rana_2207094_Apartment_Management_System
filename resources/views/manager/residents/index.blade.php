@extends('layouts.dashboard')

@section('title', 'Registered Residents - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Registered Residents</h1>
    <p class="db-subtitle">Manage resident profiles, flat assignments, and account access.</p>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
@endif

<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Resident</th>
                <th>Assigned Unit</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($residents as $resident)
                @php($profile = $resident->residentProfile)
                <tr>
                    <td style="font-weight: 600;">
                        {{ $resident->name }}
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ $resident->email }}</div>
                    </td>
                    <td>{{ $profile?->flat?->flat_number ?? $resident->flat_info ?? 'Unassigned' }}</td>
                    <td><span class="badge badge-approved">{{ $resident->resident_type ?? $profile?->resident_type ?? 'resident' }}</span></td>
                    <td>{{ $resident->phone ?? '-' }}</td>
                    <td><span class="badge {{ $resident->status === 'approved' ? 'badge-approved' : 'badge-rejected' }}">{{ str_replace('_', ' ', $resident->status) }}</span></td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem;">
                            <a href="{{ route('manager.residents.show', $resident) }}" class="btn btn-outline btn-sm">View Profile</a>
                            <form id="resident-status-{{ $resident->id }}" method="POST" action="{{ route('manager.residents.status', $resident) }}">
                                @csrf
                                <input type="hidden" name="status" value="{{ $resident->status === 'suspended' ? 'approved' : 'suspended' }}">
                                <button type="button" class="btn {{ $resident->status === 'suspended' ? 'btn-primary' : 'btn-danger' }} btn-sm" onclick="showConfirmModal('Update resident access?', 'Change access for {{ addslashes($resident->name) }}?', function(){ document.getElementById('resident-status-{{ $resident->id }}').submit(); }, true)">
                                    {{ $resident->status === 'suspended' ? 'Reactivate' : 'Suspend' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align: center; padding: 2rem;">No residents found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-pagination">
        <div class="pagination-info">Showing {{ $residents->firstItem() ?? 0 }}-{{ $residents->lastItem() ?? 0 }} of {{ $residents->total() }}</div>
        <div class="pagination-btns">
            @if ($residents->previousPageUrl()) <a href="{{ $residents->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a> @endif
            @if ($residents->nextPageUrl()) <a href="{{ $residents->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a> @endif
        </div>
    </div>
</div>
@endsection
