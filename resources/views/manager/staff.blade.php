@extends('layouts.dashboard')

@section('title', 'Society Staff Directory — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Staff Roster & Technicians</h1>
        <p class="db-subtitle">Roster list of electricians, plumbers, security guards, cleaners, and supervisors.</p>
    </div>
    
    <button type="button" class="btn btn-primary" onclick="alert('Mock: Add new staff member form.');">Add Staff Member</button>
</div>

<!-- Staff Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Roles</option>
                <option value="plumber">Plumber</option>
                <option value="electrician">Electrician</option>
                <option value="guard">Security Guard</option>
                <option value="cleaner">Cleaner</option>
            </select>
            <select class="form-control form-select" style="max-width: 200px;">
                <option value="">All Statuses</option>
                <option value="on_duty">On Duty</option>
                <option value="off_duty">Off Duty</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Role / Specialty</th>
                <th>Contact Phone</th>
                <th>Duty Status</th>
                <th>Active Work Orders</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 600;">Ali Khan</td>
                <td>Plumbing Specialist</td>
                <td>+880 1711 098765</td>
                <td><span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d;">on duty</span></td>
                <td style="font-weight: 700; text-align: center;">0 Tasks</td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="showConfirmModal('Delete Staff Member?', 'Remove Ali Khan from the registry?', function(){ alert('Staff member removed.'); }, true)">Remove</button>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Hasan Kabir</td>
                <td>Electrical Specialist</td>
                <td>+880 1812 098766</td>
                <td><span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d;">on duty</span></td>
                <td style="font-weight: 700; text-align: center;">1 Task</td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="showConfirmModal('Delete Staff Member?', 'Remove Hasan Kabir?', function(){ alert('Staff member removed.'); }, true)">Remove</button>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Abul Kalam</td>
                <td>Security Guard (Main Gate)</td>
                <td>+880 1511 098767</td>
                <td><span class="badge badge-unpaid" style="background-color: #f3f4f6; color: var(--text-secondary);">off duty</span></td>
                <td style="font-weight: 700; text-align: center;">0 Tasks</td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="showConfirmModal('Delete Staff Member?', 'Remove Abul Kalam?', function(){ alert('Staff member removed.'); }, true)">Remove</button>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>3</strong> registered staff members</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
        </div>
    </div>
</div>
@endsection
