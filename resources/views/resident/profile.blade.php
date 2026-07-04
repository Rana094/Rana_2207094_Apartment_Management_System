@extends('layouts.dashboard')

@section('title', 'Profile Settings — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Profile Settings</h1>
    <p class="db-subtitle">Update your personal registration details, password credentials, and emergency contacts.</p>
</div>

<div class="grid grid-3" style="align-items: start; gap: 2rem;">
    <!-- Left Column: Avatar & Summary Card (1 Column) -->
    <div class="card" style="grid-column: span 1; text-align: center; padding: 2.5rem 1.5rem;">
        <div class="db-sidebar-avatar" style="width: 5rem; height: 5rem; font-size: 1.75rem; margin: 0 auto 1rem auto; background-color: var(--primary-color);">
            RE
        </div>
        <h3 style="font-size: 1.2rem; margin-bottom: 0.25rem;">John Doe</h3>
        <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">Resident Account</p>
        
        <span class="badge badge-approved" style="font-size: 0.75rem; padding: 0.25rem 0.75rem;">verified member</span>
        
        <div style="border-top: 1px solid var(--border-color); margin-top: 2rem; padding-top: 1.5rem; text-align: left; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <div><span class="text-muted">Account Email:</span> <strong style="color: var(--text-primary);">john@example.com</strong></div>
            <div><span class="text-muted">Register Date:</span> <strong style="color: var(--text-primary);">July 01, 2026</strong></div>
            <div><span class="text-muted">Linked Unit:</span> <strong style="color: var(--primary-color);">Flat 3B</strong></div>
        </div>
    </div>

    <!-- Right Column: Edit Forms (2 Columns) -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <!-- General Info Form -->
        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">General Information</h3>
            
            <form action="#" method="POST">
                @csrf
                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="prof-name" class="form-label">Full Name</label>
                        <input type="text" id="prof-name" name="name" class="form-control" value="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="prof-email" class="form-label">Email Address</label>
                        <input type="email" id="prof-email" name="email" class="form-control" value="john@example.com" required>
                    </div>
                </div>
                
                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="prof-phone" class="form-label">Phone Number</label>
                        <input type="tel" id="prof-phone" name="phone" class="form-control" value="+880 1711 223344" required>
                    </div>
                    <div class="form-group">
                        <label for="prof-flat" class="form-label">Linked Unit (Read-only)</label>
                        <input type="text" id="prof-flat" class="form-control" value="Building A, Flat 3B" readonly style="background-color: var(--bg-main); color: var(--text-secondary);">
                    </div>
                </div>
                
                <button type="button" class="btn btn-primary btn-sm" onclick="alert('Profile details updated successfully.');">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Emergency Contact Form -->
        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Emergency Contacts</h3>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.25rem;">Details of a close relative or friend to contact in case of security alarms or medical issues.</p>
            
            <form action="#" method="POST">
                @csrf
                <div class="grid grid-3">
                    <div class="form-group">
                        <label for="em-name" class="form-label">Relative Full Name</label>
                        <input type="text" id="em-name" name="emergency_name" class="form-control" value="Jane Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="em-rel" class="form-label">Relationship</label>
                        <input type="text" id="em-rel" name="emergency_relation" class="form-control" value="Spouse" required>
                    </div>
                    <div class="form-group">
                        <label for="em-phone" class="form-label">Emergency Phone</label>
                        <input type="tel" id="em-phone" name="emergency_phone" class="form-control" value="+880 1711 556677" required>
                    </div>
                </div>
                
                <button type="button" class="btn btn-primary btn-sm" onclick="alert('Emergency contacts updated.');">
                    Update Contact Details
                </button>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Change Password</h3>
            
            <form action="#" method="POST">
                @csrf
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
                
                <button type="button" class="btn btn-primary btn-sm" onclick="alert('Password has been successfully changed.');">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
