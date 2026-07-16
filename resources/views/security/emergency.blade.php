@extends('layouts.dashboard')

@section('title', 'Security Emergency Panel - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title" style="color: var(--color-emergency);">Security Emergency Panel</h1>
    <p class="db-subtitle">Monitor resident alerts and dispatch a gate emergency to building management.</p>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ $errors->first() }}</div>
@endif

<div class="card" style="margin-bottom: 2rem; border: 2px solid #fda4af;">
    <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">Dispatch Gate Emergency</h3>
    <form action="{{ route('security.emergency.store') }}" method="POST">
        @csrf
        <div class="grid grid-2">
            <div class="form-group">
                <label for="gate-emergency-type" class="form-label">Alert Type</label>
                <select id="gate-emergency-type" name="type" class="form-control form-select" required>
                    <option value="security">Security breach or suspicious person</option>
                    <option value="fire">Fire or smoke</option>
                    <option value="power">Power failure or trapped elevator</option>
                    <option value="medical">Medical emergency at gate</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gate-emergency-message" class="form-label">Details</label>
                <textarea id="gate-emergency-message" name="message" class="form-control" rows="3" maxlength="1000" placeholder="Describe the gate incident.">{{ old('message') }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-danger" style="background-color: var(--color-emergency); border-color: var(--color-emergency);">Dispatch Emergency Alert</button>
    </form>
</div>

<div class="table-responsive">
    <table class="db-table">
        <thead><tr><th>Alert</th><th>Flat</th><th>Resident</th><th>Type</th><th>Details</th><th>Created</th><th>Status</th><th style="text-align: right;">Action</th></tr></thead>
        <tbody>
            @forelse ($emergencies as $emergency)
                <tr>
                    <td><strong>#AL-{{ $emergency->id }}</strong></td>
                    <td>{{ $emergency->flat?->flat_number ?? '-' }}</td>
                    <td>{{ $emergency->resident?->name ?? '-' }}</td>
                    <td>{{ ucfirst($emergency->type) }}</td>
                    <td>{{ $emergency->message ?: '-' }}</td>
                    <td>{{ $emergency->created_at?->format('M d, Y H:i') }}</td>
                    <td><span class="badge badge-{{ $emergency->status === 'resolved' ? 'resolved' : 'emergency' }}">{{ str_replace('_', ' ', ucfirst($emergency->status)) }}</span></td>
                    <td style="text-align: right;">
                        @if ($emergency->status !== 'resolved')
                            <form method="POST" action="{{ route('security.emergency.status', $emergency) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Resolve Alert</button>
                            </form>
                        @else
                            <span class="text-muted text-xs">Resolved</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align: center; padding: 2rem;">No emergency alerts.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if ($emergencies->hasPages())
        <div class="table-pagination" style="margin-top: 1rem;">{{ $emergencies->links() }}</div>
    @endif
</div>
@endsection
