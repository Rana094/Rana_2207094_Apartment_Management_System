@extends('layouts.dashboard')

@section('title', 'Resident Approvals — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Pending Resident Approvals</h1>
    <p class="db-subtitle">Review verification documents and approve or reject portal access requests for owners and tenants.</p>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1.5rem;">
        {{ session('status') }}
    </div>
@endif

<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Resident Applicant</th>
                <th>Requested Flat Info</th>
                <th>Resident Type</th>
                <th>Verification Status</th>
                <th>Submitted Documents</th>
                <th style="text-align: right;">Approval Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($residents as $resident)
                <tr>
                    <td style="font-weight: 600;">
                        {{ $resident->name }}
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ $resident->email }} | {{ $resident->phone }}</div>
                    </td>
                    <td style="font-weight: 600;">{{ $resident->flat_info ?? 'Not Assigned' }}</td>
                    <td>
                        @if (($resident->resident_type ?? 'tenant') === 'owner')
                            <span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d;">Owner occupied</span>
                        @else
                            <span class="badge badge-unpaid" style="background-color: var(--secondary-light); color: var(--secondary-color);">Tenant</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-pending-verification">
                            {{ str_replace('_', ' ', (string) $resident->status) }}
                        </span>
                    </td>
                    <td>
                        <!-- Mock Verification Documents download button -->
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button type="button" class="btn btn-outline btn-xs" style="padding: 0.15rem 0.4rem; font-size: 0.75rem;" onclick="alert('Mock: Downloading NID verification copy.');">
                                NID File
                            </button>
                            <button type="button" class="btn btn-outline btn-xs" style="padding: 0.15rem 0.4rem; font-size: 0.75rem;" onclick="alert('Mock: Downloading Lease Verification agreement.');">
                                Lease Agreement
                            </button>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem;">
                            <!-- Approve Action -->
                            <form method="POST" action="{{ route('manager.resident-approvals.approve', $resident) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                            </form>
                            
                            <!-- Reject Action Triggering Modal -->
                            <button type="button" class="btn btn-outline btn-sm" onclick="showConfirmModal('Reject applicant?', 'Are you sure you want to reject {{ $resident->name }}? Access will be denied.', function(){
                                const form = document.getElementById('reject-form-{{ $resident->id }}');
                                if(form) form.submit();
                            }, true)">Reject</button>
                            
                            <!-- Hidden Rejection Form -->
                            <form method="POST" id="reject-form-{{ $resident->id }}" action="{{ route('manager.resident-approvals.reject', $resident) }}" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                        <!-- Happy State SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 2.5rem; height: 2.5rem; margin: 0 auto 0.75rem auto; color: var(--text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a9.008 9.008 0 01-11.713 0M3 12c0-1.268.63-2.39 1.593-3.068a9.008 9.008 0 0111.713 0" />
                        </svg>
                        No resident approvals are pending.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="table-pagination" style="margin-top: 1.5rem;">
        <div>{{ $residents->links() }}</div>
    </div>
</div>
@endsection
