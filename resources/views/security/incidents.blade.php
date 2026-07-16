@extends('layouts.dashboard')

@section('title', 'Security Incidents Log - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Security Incidents Directory</h1>
    <p class="db-subtitle">Register and review security incident reports, noise complaints, or parking disputes.</p>
</div>

<div class="grid grid-3" style="align-items: start;">
    <div class="card" style="grid-column: span 1;">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">File Incident Report</h3>

        <form action="{{ route('security.incidents.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="inc-title" class="form-label">Incident Subject</label>
                <input type="text" id="inc-title" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="e.g. Unauthorized parking block" required>
            </div>

            <div class="form-group">
                <label for="inc-cat" class="form-label">Incident Category</label>
                <select id="inc-cat" name="category" class="form-control form-select" required>
                    <option value="parking" @selected(old('category', 'parking') === 'parking')>Parking Dispute / Blockage</option>
                    <option value="noise" @selected(old('category') === 'noise')>Noise Complaint / Disturbance</option>
                    <option value="theft" @selected(old('category') === 'theft')>Theft / Vandalism / Damage</option>
                    <option value="suspicious" @selected(old('category') === 'suspicious')>Suspicious Activity / Person</option>
                    <option value="other" @selected(old('category') === 'other')>Other Incident</option>
                </select>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="inc-date" class="form-label">Date</label>
                    <input type="date" id="inc-date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label for="inc-time" class="form-label">Time</label>
                    <input type="time" id="inc-time" name="time" class="form-control" value="{{ old('time', date('H:i')) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="inc-flat" class="form-label">Involved Flat Unit <span class="text-muted" style="font-weight: normal;">(Optional)</span></label>
                <select id="inc-flat" name="flat_id" class="form-control form-select">
                    <option value="" selected>Select involved flat...</option>
                    @foreach ($flats as $flat)
                        <option value="{{ $flat->id }}" @selected((string) old('flat_id') === (string) $flat->id)>{{ $flat->flat_number }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="inc-desc" class="form-label">Incident Details / Description</label>
                <textarea id="inc-desc" name="description" class="form-control" rows="4" placeholder="Describe the incident, names involved, and immediate actions taken by gate security..." required>{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                Log Incident Report
            </button>
        </form>
    </div>

    <div class="table-responsive" style="grid-column: span 2;">
        <table class="db-table">
            <thead>
                <tr>
                    <th>Incident Subject</th>
                    <th>Category</th>
                    <th>Date & Time</th>
                    <th>Involved Flat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($incidents as $incident)
                    <tr>
                        <td style="font-weight: 600;">
                            {{ $incident->subject }}
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ $incident->description }}</div>
                        </td>
                        <td>{{ ucfirst($incident->category) }}</td>
                        <td>{{ $incident->occurred_at?->format('M d, Y H:i') }}</td>
                        <td style="font-weight: 600;">{{ $incident->flat?->flat_number ?? '-' }}</td>
                        <td><span class="badge badge-in-progress">{{ $incident->status }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:2rem; color:var(--text-muted);">No security incidents logged yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($incidents->hasPages())
            <div class="table-pagination" style="margin-top: 1rem;">{{ $incidents->links() }}</div>
        @endif
    </div>
</div>
@endsection
