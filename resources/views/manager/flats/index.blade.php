@extends('layouts.dashboard')

@section('title', 'Unit Registry - Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
    <div>
        <h1 class="db-title">Apartment Unit Registry</h1>
        <p class="db-subtitle">Manage buildings, flat details, occupancy, and resident assignments.</p>
    </div>
    <a href="{{ route('manager.flats.create') }}" class="btn btn-primary">Register New Unit</a>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
@endif

<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Unit</th>
                <th>Building</th>
                <th>Floor</th>
                <th>Area</th>
                <th>Status</th>
                <th>Resident</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($flats as $flat)
                @php($resident = $flat->residentProfiles->first()?->user)
                @php($pendingResident = $flat->pendingResidentRequests->first())
                @php($isReserved = $flat->status === 'vacant' && $pendingResident)
                <tr>
                    <td style="font-weight: 700;">{{ $flat->flat_number }}</td>
                    <td>{{ $flat->building?->name ?? '-' }}</td>
                    <td>{{ $flat->floor ?? '-' }}</td>
                    <td>{{ $flat->area_sqft ? number_format((float) $flat->area_sqft).' sq ft' : '-' }}</td>
                    <td>
                        @if ($isReserved)
                            <span class="badge badge-pending-verification">pending approval</span>
                        @else
                            <span class="badge {{ $flat->status === 'vacant' ? 'badge-pending' : 'badge-approved' }}">{{ $flat->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($resident)
                            <a href="{{ route('manager.residents.show', $resident) }}">{{ $resident->name }}</a>
                        @elseif ($pendingResident)
                            <span>{{ $pendingResident->name }}</span>
                            <div class="text-xs" style="color: var(--text-muted);">Pending approval</div>
                        @else
                            <span class="text-muted">Unassigned</span>
                        @endif
                    </td>
                    <td style="text-align: right;"><a href="{{ route('manager.flats.edit', $flat) }}" class="btn btn-outline btn-sm">Edit Flat</a></td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center; padding: 2rem;">No flats registered.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="table-pagination">
        <div class="pagination-info">{{ $flats->total() }} registered units</div>
        <div class="pagination-btns">
            @if ($flats->previousPageUrl()) <a href="{{ $flats->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a> @endif
            @if ($flats->nextPageUrl()) <a href="{{ $flats->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a> @endif
        </div>
    </div>
</div>
@endsection
