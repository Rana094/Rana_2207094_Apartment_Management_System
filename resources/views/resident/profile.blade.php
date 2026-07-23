@extends('layouts.dashboard')

@php
    $role = $user->role;
    $isResident = $role === 'resident';
    $profileRoute = $isResident ? route('resident.profile.update') : route('maintenance.profile.update');
    $passwordRoute = $isResident ? route('resident.profile.password.update') : route('maintenance.profile.password.update');
    $flat = $isResident ? $profile?->flat : null;
@endphp

@section('title', 'Profile Settings - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Profile Settings</h1>
    <p class="db-subtitle">Update your account details and password credentials.</p>
</div>

@if (session('status'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom:1rem;">{{ $errors->first() }}</div>
@endif

<div class="grid grid-3" style="align-items: start; gap: 2rem;">
    <div class="card" style="grid-column: span 1; text-align: center; padding: 2.5rem 1.5rem;">
        <div class="db-sidebar-avatar" style="width: 5rem; height: 5rem; font-size: 1.75rem; margin: 0 auto 1rem auto; background-color: var(--primary-color);">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <h3 style="font-size: 1.2rem; margin-bottom: 0.25rem;">{{ $user->name }}</h3>
        <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">{{ ucfirst($role) }} Account</p>

        <span class="badge badge-approved" style="font-size: 0.75rem; padding: 0.25rem 0.75rem;">{{ str_replace('_', ' ', $user->status) }}</span>

        <div style="border-top: 1px solid var(--border-color); margin-top: 2rem; padding-top: 1.5rem; text-align: left; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <div><span class="text-muted">Account Email:</span> <strong style="color: var(--text-primary);">{{ $user->email }}</strong></div>
            <div><span class="text-muted">Register Date:</span> <strong style="color: var(--text-primary);">{{ $user->created_at?->format('M d, Y') }}</strong></div>
            @if ($isResident)
                <div><span class="text-muted">Linked Unit:</span> <strong style="color: var(--primary-color);">{{ $flat?->building?->name ?? 'No building' }}, {{ $flat?->flat_number ?? 'No flat' }}</strong></div>
            @else
                <div><span class="text-muted">Staff Type:</span> <strong style="color: var(--primary-color);">{{ $profile?->staff_type ?? 'Staff' }}</strong></div>
            @endif
        </div>
    </div>

    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; margin-bottom:1.25rem;">
                <div>
                    <h3 style="font-size: 1.2rem; margin-bottom: .25rem;">Edit Profile</h3>
                    <p style="font-size:.9rem; color:var(--text-secondary); margin:0;">Update your basic contact information and emergency contact details.</p>
                </div>
                <span class="badge badge-approved">Editable</span>
            </div>

            <form action="{{ $profileRoute }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="prof-name" class="form-label">Full Name</label>
                        <input type="text" id="prof-name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="prof-email" class="form-label">Email Address</label>
                        <input type="email" id="prof-email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="prof-phone" class="form-label">Phone Number</label>
                        <input type="tel" id="prof-phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" {{ $isResident ? 'required' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="prof-flat" class="form-label">{{ $isResident ? 'Linked Unit' : 'Employee Code' }}</label>
                        <input type="text" id="prof-flat" class="form-control" value="{{ $isResident ? (($flat?->building?->name ?? 'No building').', '.($flat?->flat_number ?? 'No flat')) : ($profile?->employee_code ?? '-') }}" readonly style="background-color: var(--bg-main); color: var(--text-secondary);">
                    </div>
                </div>

                @if ($isResident)
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label for="em-name" class="form-label">Emergency Contact Name</label>
                            <input type="text" id="em-name" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $profile?->emergency_contact_name) }}">
                        </div>
                        <div class="form-group">
                            <label for="em-phone" class="form-label">Emergency Contact Phone</label>
                            <input type="tel" id="em-phone" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $profile?->emergency_contact_phone) }}">
                        </div>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary btn-sm">
                    Save Changes
                </button>
            </form>
        </div>

        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Change Password</h3>

            <form action="{{ $passwordRoute }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="pass-curr" class="form-label">Current Password</label>
                    <input type="password" id="pass-curr" name="current_password" class="form-control" required>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="pass-new" class="form-label">New Password</label>
                        <input type="password" id="pass-new" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="pass-conf" class="form-label">Confirm New Password</label>
                        <input type="password" id="pass-conf" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-sm">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
