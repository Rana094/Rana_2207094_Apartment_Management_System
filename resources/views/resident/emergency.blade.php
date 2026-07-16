@extends('layouts.dashboard')

@section('title', 'Emergency Request - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title" style="color: var(--color-emergency);">Emergency Request</h1>
    <p class="db-subtitle">Immediately notify building management and gate security about an urgent incident.</p>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom: 1rem;">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ $errors->first() }}</div>
@endif

<div class="grid grid-2" style="align-items: start;">
    <div class="card" style="border: 2px solid #fda4af;">
        <h3 style="font-size: 1.2rem; margin-bottom: .5rem;">Send Emergency Alert</h3>
        <p class="text-muted" style="margin-bottom: 1.25rem;">Use this only when immediate assistance is required. For ordinary repairs, submit a maintenance complaint.</p>

        <form action="{{ route('resident.emergency.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="emergency-type" class="form-label">Emergency Category</label>
                <select id="emergency-type" name="type" class="form-control form-select" required>
                    @foreach ([
                        'medical' => 'Medical emergency',
                        'fire' => 'Fire or smoke',
                        'security' => 'Security threat or intruder',
                        'leak' => 'Severe gas or water leak',
                        'general' => 'Other urgent emergency',
                    ] as $value => $label)
                        <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="emergency-message" class="form-label">Details</label>
                <textarea id="emergency-message" name="message" class="form-control" rows="4" maxlength="1000" placeholder="Briefly describe what happened and where help is needed.">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center; background-color: var(--color-emergency); border-color: var(--color-emergency);">
                Send Emergency Alert
            </button>
        </form>

        <div style="border-top: 1px solid var(--border-color); margin-top: 1.5rem; padding-top: 1rem; display: flex; justify-content: space-between;">
            <span>National Emergency Service</span>
            <strong style="color: var(--color-emergency);">999</strong>
        </div>
    </div>

    <div class="card">
        <h3 style="font-size: 1.2rem; margin-bottom: 1rem;">My Recent Alerts</h3>
        <div class="table-responsive">
            <table class="db-table">
                <thead><tr><th>Alert</th><th>Type</th><th>Sent</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($emergencyRequests as $emergency)
                        <tr>
                            <td><strong>#AL-{{ $emergency->id }}</strong><div class="text-muted text-xs">{{ $emergency->message ?: 'No additional details' }}</div></td>
                            <td>{{ ucfirst($emergency->type) }}</td>
                            <td>{{ $emergency->created_at?->format('M d, Y H:i') }}</td>
                            <td><span class="badge badge-{{ $emergency->status === 'resolved' ? 'resolved' : 'emergency' }}">{{ str_replace('_', ' ', ucfirst($emergency->status)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">No emergency alerts submitted.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
