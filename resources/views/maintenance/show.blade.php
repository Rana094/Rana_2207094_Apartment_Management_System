@extends('layouts.dashboard')

@section('title', 'Work Order #T-'.$workOrder->id.' - Nestora')

@section('content')
@php
    $flat = $workOrder->complaint?->flat;
    $resident = $workOrder->complaint?->resident;
    $status = $workOrder->status;
@endphp

<div class="db-header">
    <a href="{{ route('maintenance.dashboard') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        Back to Workspace
    </a>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; width: 100%;">
        <h1 class="db-title" style="font-size: 1.5rem;">Work Order #T-{{ $workOrder->id }}</h1>
        <span class="badge badge-{{ $status === 'completed' ? 'completed' : 'in-progress' }}" style="font-size: 0.85rem; padding: 0.4rem 1rem;">{{ str_replace('_', ' ', $status) }}</span>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
@endif

<div class="grid grid-3" style="align-items: start;">
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">{{ $workOrder->title }}</h3>

            <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem; font-size: 0.85rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; flex-wrap: wrap;">
                <div><span class="text-muted">Target Flat:</span> <strong style="color: var(--primary-color);">{{ $flat?->flat_number ?? 'No flat' }} ({{ $flat?->building?->name ?? 'No building' }})</strong></div>
                <div><span class="text-muted">Urgency:</span> <span class="badge badge-{{ in_array($workOrder->priority, ['high', 'urgent', 'emergency'], true) ? 'rejected' : 'pending' }}" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">{{ $workOrder->priority }}</span></div>
                <div><span class="text-muted">Assigned Date:</span> <strong style="color: var(--text-primary);">{{ $workOrder->created_at?->format('M d, Y') ?? '-' }}</strong></div>
                <div><span class="text-muted">Deadline Date:</span> <strong style="color: var(--color-rejected);">{{ $workOrder->due_at?->format('M d, Y') ?? '-' }}</strong></div>
            </div>

            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Job Description:</h4>
            <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-secondary); margin-bottom: 1.5rem;">
                {{ $workOrder->complaint?->description ?? 'No complaint description provided.' }}
            </p>

            <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Manager Dispatch Instructions:</h4>
            <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-secondary); margin-bottom: 0; font-style: italic; background-color: var(--bg-main); padding: 1rem; border-radius: var(--radius-md);">
                {{ $workOrder->instructions ?? 'No manager instructions provided.' }}
            </p>
        </div>

        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">Resident Information</h3>
            <div class="grid grid-2" style="font-size: 0.875rem;">
                <div>
                    <span class="text-muted">Primary Resident:</span>
                    <div style="font-weight: 700; color: var(--text-primary); font-size: 1rem; margin-top: 0.25rem;">{{ $resident?->name ?? 'No resident linked' }}</div>
                </div>
                <div>
                    <span class="text-muted">Contact Phone:</span>
                    <div style="font-weight: 700; color: var(--primary-color); font-size: 1rem; margin-top: 0.25rem;">{{ $resident?->phone ?? 'No phone' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">Resident Complaint Messages</h3>
            @forelse ($workOrder->complaint?->messages ?? collect() as $message)
                <div style="border-top: 1px solid var(--border-color); padding: 0.75rem 0;">
                    <strong>{{ $message->user?->name ?? 'Resident' }}</strong>
                    <p style="font-size: 0.85rem; margin: 0.25rem 0; color: var(--text-secondary);">{{ $message->message }}</p>
                    <span class="text-muted text-xs">{{ $message->created_at?->format('M d, Y H:i') }}</span>
                </div>
            @empty
                <p class="text-muted" style="font-size: 0.9rem;">No resident messages posted yet.</p>
            @endforelse
        </div>
    </div>

    <div style="grid-column: span 1; display: flex; flex-direction: column; gap: 2rem;">
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem; text-align: left;">Task Execution</h3>
            @if ($workOrder->status !== 'completed')
                <a href="{{ route('maintenance.update', $workOrder) }}" class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 0.75rem; font-weight: 700;">Update Task Status</a>
            @endif
            <a href="{{ route('maintenance.dashboard') }}" class="btn btn-outline" style="width: 100%; justify-content: center;">Back to Dashboard</a>
        </div>

        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem;">Repair Notes</h3>
            @forelse ($workOrder->notes as $note)
                <div style="border-top: 1px solid var(--border-color); padding: 0.75rem 0;">
                    <strong>{{ str_replace('_', ' ', $note->status) }}</strong>
                    <p style="font-size: 0.85rem; margin: 0.25rem 0;">{{ $note->remarks }}</p>
                    <span class="text-muted text-xs">{{ $note->noted_at?->format('M d, Y H:i') }}</span>
                </div>
            @empty
                <p class="text-muted" style="font-size: 0.9rem;">No repair notes submitted yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
