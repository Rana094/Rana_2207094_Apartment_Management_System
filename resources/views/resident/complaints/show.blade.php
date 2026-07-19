@extends('layouts.dashboard')

@php
    $status = $complaint->status;
    // The newest work order is the current staff assignment created by the manager from this complaint.
    $assignedOrder = $complaint->workOrders->sortByDesc('created_at')->first();
@endphp

@section('title', 'Complaint Ticket #T-'.$complaint->id.' - Nestora')

@section('content')
<div class="db-header">
    <a href="{{ route('resident.complaints.index') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        Back to Complaints
    </a>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; width: 100%;">
        <h1 class="db-title" style="font-size: 1.5rem;">Complaint Ticket #T-{{ $complaint->id }}</h1>
        <span class="badge badge-{{ $status === 'resolved' ? 'resolved' : ($status === 'open' ? 'pending' : 'in-progress') }}" style="font-size: 0.85rem; padding: 0.4rem 1rem;">{{ str_replace('_', ' ', $status) }}</span>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
@endif

<div class="grid grid-3" style="align-items: start;">
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ $complaint->title }}</h3>

            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem; font-size: 0.85rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; flex-wrap: wrap;">
                <div><span class="text-muted">Category:</span> <strong style="color: var(--text-primary);">{{ ucfirst($complaint->category ?? 'General') }}</strong></div>
                <div><span class="text-muted">Urgency:</span> <span class="badge badge-{{ in_array($complaint->priority, ['high', 'urgent', 'emergency'], true) ? 'rejected' : 'pending' }}" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">{{ $complaint->priority }}</span></div>
                <div><span class="text-muted">Location:</span> <strong style="color: var(--text-primary);">{{ $complaint->flat?->flat_number ?? 'No flat linked' }}</strong></div>
                <div><span class="text-muted">Filed On:</span> <strong style="color: var(--text-primary);">{{ $complaint->created_at?->format('M d, Y') ?? '-' }}</strong></div>
            </div>

            <p style="font-size: 0.95rem; line-height: 1.6; color: var(--text-secondary); margin-bottom: 0;">
                {{ $complaint->description }}
            </p>
        </div>

        <div class="card">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Discussion History</h3>

            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                {{-- These messages are shared with maintenance staff through the complaint_messages table. --}}
                @forelse ($complaint->messages as $message)
                    <div style="background-color: {{ $message->user_id === auth()->id() ? 'var(--primary-light)' : '#f1f5f9' }}; padding: 1rem; border-radius: var(--radius-md); align-self: {{ $message->user_id === auth()->id() ? 'flex-start' : 'flex-end' }}; max-width: 80%;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: var(--primary-color); margin-bottom: 0.25rem;">{{ $message->user?->name ?? 'User' }}</div>
                        <p style="font-size: 0.85rem; color: var(--text-primary); margin-bottom: 0;">{{ $message->message }}</p>
                        <div style="font-size: 0.7rem; color: var(--text-secondary); text-align: right; margin-top: 0.25rem;">{{ $message->created_at?->format('M d, Y H:i') }}</div>
                    </div>
                @empty
                    <p style="font-size:.85rem; color:var(--text-muted);">No replies posted yet.</p>
                @endforelse
            </div>

            {{-- Posting here calls ResidentPortalController@storeComplaintMessage so staff can read the extra details. --}}
            <form action="{{ route('resident.complaints.messages.store', $complaint) }}" method="POST" style="border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                @csrf
                <div class="form-group">
                    <label for="new-comment" class="form-label" style="font-size: 0.85rem;">Post Reply</label>
                    <textarea id="new-comment" name="message" class="form-control" rows="3" placeholder="Write a reply..." required>{{ old('message') }}</textarea>
                </div>
                <div style="text-align: right;">
                    <button type="submit" class="btn btn-primary btn-sm">Send Reply</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.25rem;">Repair Updates</h3>

            {{-- Staff remarks are saved as WorkOrderNote records and displayed here as resident-facing repair updates. --}}
            @forelse ($complaint->workOrders->flatMap->notes->sortByDesc('noted_at') as $note)
                <div style="border-top: 1px solid var(--border-color); padding: 0.9rem 0;">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
                        <strong style="color: var(--text-primary);">{{ $note->user?->name ?? 'Maintenance Staff' }}</strong>
                        <span class="badge badge-{{ $note->status === 'completed' ? 'resolved' : 'in-progress' }}" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">{{ str_replace('_', ' ', $note->status ?? 'updated') }}</span>
                    </div>
                    <p style="font-size: 0.9rem; line-height: 1.5; color: var(--text-secondary); margin-bottom: 0.35rem;">{{ $note->remarks }}</p>
                    <span class="text-muted text-xs">{{ $note->noted_at?->format('M d, Y H:i') }}</span>
                </div>
            @empty
                <p style="font-size:.85rem; color:var(--text-muted);">No repair updates from maintenance staff yet.</p>
            @endforelse
        </div>
    </div>

    <div style="grid-column: span 1; display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Ticket Timeline</h3>
            <ul class="steps-list" style="border: none; padding: 0; background: none; margin-bottom: 0;">
                <li class="step-item completed"><span class="step-badge">1</span><div><div class="step-title">Ticket Filed</div><div class="step-desc">{{ $complaint->created_at?->format('M d, Y H:i') ?? '-' }}</div></div></li>
                <li class="step-item {{ $assignedOrder ? 'completed' : 'active' }}"><span class="step-badge">2</span><div><div class="step-title">Technician Assigned</div><div class="step-desc">Manager dispatches maintenance staff.</div></div></li>
                <li class="step-item {{ in_array($status, ['in_progress', 'resolved'], true) ? 'completed' : '' }}"><span class="step-badge">3</span><div><div class="step-title">In Progress</div><div class="step-desc">Technician is resolving the issue.</div></div></li>
                <li class="step-item {{ $status === 'resolved' ? 'completed' : '' }}"><span class="step-badge">4</span><div><div class="step-title">Resolved / Complete</div><div class="step-desc">Repair completed and confirmed.</div></div></li>
            </ul>
        </div>

        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Assigned Technician</h3>
            @if ($assignedOrder?->assignedStaff)
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                    <div class="db-sidebar-avatar" style="width: 3rem; height: 3rem; background-color: var(--secondary-color); font-size: 1.1rem;">{{ strtoupper(substr($assignedOrder->assignedStaff->name, 0, 2)) }}</div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 0.95rem;">{{ $assignedOrder->assignedStaff->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $assignedOrder->assignedStaff->staffProfile?->staff_type ?? 'Maintenance Staff' }}</div>
                    </div>
                </div>
            @else
                <p style="font-size:.85rem; color:var(--text-muted);">No technician assigned yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
