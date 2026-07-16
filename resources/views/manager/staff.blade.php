@extends('layouts.dashboard')

@section('title', 'Staff Management - Nestora')

@section('content')
<div class="db-header"><h1 class="db-title">Staff Management</h1><p class="db-subtitle">Create maintenance and security accounts and manage the active directory.</p></div>
@if(session('status'))<div class="alert alert-success" style="margin-bottom:1rem;">{{ session('status') }}</div>@endif
@if($errors->any())<div class="alert alert-danger" style="margin-bottom:1rem;">{{ $errors->first() }}</div>@endif

<div class="card" style="margin-bottom:1.5rem;">
    <h3 style="margin-bottom:1rem;">Add Staff Member</h3>
    <form method="POST" action="{{ route('manager.staff.store') }}">
        @csrf
        <div class="grid grid-3">
            <div class="form-group"><label class="form-label">Full Name</label><input name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
            <div class="form-group"><label class="form-label">Phone</label><input name="phone" class="form-control" value="{{ old('phone') }}"></div>
            <div class="form-group"><label class="form-label">Portal Role</label><select name="role" class="form-control form-select" required><option value="staff">Maintenance</option><option value="security">Security</option></select></div>
            <div class="form-group"><label class="form-label">Staff Type</label><input name="staff_type" class="form-control" placeholder="Plumber, Electrician, Guard" value="{{ old('staff_type') }}" required></div>
            <div class="form-group"><label class="form-label">Employee Code</label><input name="employee_code" class="form-control" value="{{ old('employee_code') }}" required></div>
        </div>
        <button type="submit" class="btn btn-primary">Create Staff Account</button>
    </form>
</div>

<div class="table-responsive">
    <table class="db-table">
        <thead><tr><th>Name</th><th>Type</th><th>Role</th><th>Phone</th><th>Status</th><th style="text-align:right;">Action</th></tr></thead>
        <tbody>
            @forelse($staff as $member)
                <tr>
                    <td><strong>{{ $member->name }}</strong><div class="text-muted text-xs">{{ $member->email }}</div></td>
                    <td>{{ $member->staffProfile?->staff_type ?? '-' }}</td><td>{{ $member->role }}</td><td>{{ $member->phone ?? '-' }}</td>
                    <td><span class="badge badge-approved">{{ $member->status }}</span></td>
                    <td style="text-align:right;"><form id="remove-staff-{{ $member->id }}" method="POST" action="{{ route('manager.staff.destroy',$member) }}">@csrf @method('DELETE')<button type="button" class="btn btn-danger btn-sm" onclick="showConfirmModal('Remove staff member?', 'Remove {{ addslashes($member->name) }} from Nestora?', function(){ document.getElementById('remove-staff-{{ $member->id }}').submit(); }, true)">Remove</button></form></td>
                </tr>
            @empty<tr><td colspan="6" style="text-align:center;padding:2rem;">No staff accounts found.</td></tr>@endforelse
        </tbody>
    </table>
    <div class="table-pagination"><div class="pagination-info">{{ $staff->total() }} staff members</div><div class="pagination-btns">@if($staff->previousPageUrl())<a href="{{ $staff->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>@endif @if($staff->nextPageUrl())<a href="{{ $staff->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>@endif</div></div>
</div>
@endsection
