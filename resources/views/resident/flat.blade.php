@extends('layouts.dashboard')

@section('title', 'My Flat Details - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Flat Details</h1>
    <p class="db-subtitle">Overview of your assigned unit, family members, and registered vehicles.</p>
</div>

<div class="grid grid-3" style="align-items: start;">
    <div class="card" style="grid-column: span 1;">
        <div style="text-align: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
            <div class="stat-icon primary" style="width: 4rem; height: 4rem; margin: 0 auto 1rem auto; background-color: var(--primary-light); color: var(--primary-color); border-radius: var(--radius-lg);">
                <x-icon name="house" alt="" size="2rem" />
            </div>
            <h2 style="font-size: 1.5rem; margin-bottom: 0.25rem;">{{ $flat?->flat_number ?? 'No Flat Assigned' }}</h2>
            <span class="badge badge-approved">{{ $profile?->resident_type ?? 'resident' }}</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.9rem;">
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Building Block:</span>
                <span style="font-weight: 600;">{{ $flat?->building?->name ?? '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Floor Level:</span>
                <span style="font-weight: 600;">{{ $flat?->floor ?? '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Square Footage:</span>
                <span style="font-weight: 600;">{{ $flat?->area_sqft ? number_format($flat->area_sqft).' Sq Ft' : '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Bedrooms:</span>
                <span style="font-weight: 600;">{{ $flat?->bedrooms ?? '-' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 0.5rem;">
                <span class="text-muted">Status:</span>
                <span style="font-weight: 700; color: var(--secondary-color);">{{ $flat?->status ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Flat Members</h3>
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Relationship</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600;">{{ auth()->user()->name }}<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Primary Account Holder</div></td>
                        <td>Self</td>
                        <td>{{ auth()->user()->phone ?? '-' }}</td>
                        <td><span class="badge badge-approved">{{ auth()->user()->status }}</span></td>
                    </tr>
                    @foreach ($members as $member)
                        <tr>
                            <td style="font-weight: 600;">{{ $member->name }}</td>
                            <td>{{ $member->relationship ?? '-' }}</td>
                            <td>{{ $member->phone ?? '-' }}</td>
                            <td><span class="badge badge-approved">registered</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Add Vehicle</h3>
            <form action="{{ route('resident.vehicles.store') }}" method="POST">
                @csrf
                <div class="grid grid-3">
                    <div class="form-group">
                        <label class="form-label" for="vehicle-type">Vehicle Type</label>
                        <select id="vehicle-type" name="vehicle_type" class="form-control form-select" required>
                            <option value="car">Car</option>
                            <option value="motorbike">Motorbike</option>
                            <option value="bicycle">Bicycle</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="vehicle-registration">License Plate</label>
                        <input id="vehicle-registration" name="registration_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="vehicle-slot">Parking Slot</label>
                        <input id="vehicle-slot" name="parking_slot" class="form-control">
                    </div>
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label" for="vehicle-brand">Brand</label>
                        <input id="vehicle-brand" name="brand" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="vehicle-model">Model / Color</label>
                        <input id="vehicle-model" name="model" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Submit Vehicle</button>
            </form>
        </div>

        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Registered Vehicles</h3>

            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Vehicle Type</th>
                        <th>Model</th>
                        <th>License Plate</th>
                        <th>Parking Slot</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicles as $vehicle)
                        <tr>
                            <td style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                                <x-icon name="car" alt="" size="1.25rem" />
                                {{ ucfirst($vehicle->vehicle_type) }}
                            </td>
                            <td>{{ trim(($vehicle->brand ? $vehicle->brand.' ' : '').($vehicle->model ?? '')) ?: '-' }}</td>
                            <td>{{ $vehicle->registration_number }}</td>
                            <td>{{ $vehicle->parking_slot ?? '-' }}</td>
                            <td><span class="badge badge-{{ $vehicle->status === 'active' ? 'approved' : 'pending' }}">{{ $vehicle->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:2rem; color:var(--text-muted);">No registered vehicles yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
