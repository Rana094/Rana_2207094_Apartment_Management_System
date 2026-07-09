@extends('layouts.dashboard')

@section('title', 'Unit Registry — Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Apartment Unit Registry</h1>
        <p class="db-subtitle">List and manage all registered flats, blocks, floors, and occupancy statuses.</p>
    </div>
    
    <a href="{{ url('/manager/flats/create') }}" class="btn btn-primary">Register New Unit</a>
</div>

<!-- Flats Table -->
<div class="table-responsive">
    <div class="table-toolbar">
        <div class="table-toolbar-left">
            <input type="text" class="form-control" placeholder="Search by flat name..." style="max-width: 250px;">
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Building Blocks</option>
                <option value="building_a">Building A</option>
                <option value="building_b">Building B</option>
            </select>
            <select class="form-control form-select" style="max-width: 180px;">
                <option value="">All Occupancies</option>
                <option value="owner">Occupied by Owner</option>
                <option value="tenant">Occupied by Tenant</option>
                <option value="vacant">Vacant</option>
            </select>
        </div>
    </div>
    
    <table class="db-table">
        <thead>
            <tr>
                <th>Unit / Flat No</th>
                <th>Building Block</th>
                <th>Floor Level</th>
                <th>Square Footage</th>
                <th>Occupancy Status</th>
                <th>Assigned Resident</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: 700;">Flat 3B</td>
                <td>Building A (Tower 1)</td>
                <td>3rd Floor</td>
                <td>1,650 Sq Ft</td>
                <td><span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d;">occupied (owner)</span></td>
                <td style="font-weight: 600;"><a href="{{ url('/manager/residents/1') }}">John Doe</a></td>
                <td style="text-align: right;">
                    <a href="{{ url('/manager/flats/1/edit') }}" class="btn btn-outline btn-sm">Edit Flat</a>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">Flat 5A</td>
                <td>Building A (Tower 1)</td>
                <td>5th Floor</td>
                <td>1,650 Sq Ft</td>
                <td><span class="badge badge-unpaid" style="background-color: var(--secondary-light); color: var(--secondary-color);">occupied (tenant)</span></td>
                <td style="font-weight: 600;"><a href="{{ url('/manager/residents/2') }}">Karim Alvi</a></td>
                <td style="text-align: right;">
                    <a href="{{ url('/manager/flats/2/edit') }}" class="btn btn-outline btn-sm">Edit Flat</a>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 700;">Flat 4D</td>
                <td>Building B (Tower 2)</td>
                <td>4th Floor</td>
                <td>1,800 Sq Ft</td>
                <td><span class="badge badge-pending" style="background-color: #f3f4f6; color: var(--text-secondary);">vacant</span></td>
                <td class="text-muted">None Assigned</td>
                <td style="text-align: right;">
                    <a href="{{ url('/manager/flats/3/edit') }}" class="btn btn-outline btn-sm">Edit Flat</a>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="table-pagination">
        <div class="pagination-info">Showing <strong>3</strong> units of <strong>120</strong> total registered units</div>
        <div class="pagination-btns">
            <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
            <button type="button" class="btn btn-outline btn-sm">Next</button>
        </div>
    </div>
</div>
@endsection
