@extends('layouts.dashboard')

@section('title', 'Resident Profile — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/manager/residents') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Residents Directory
    </a>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; width: 100%;">
        <h1 class="db-title" style="font-size: 1.5rem;">Resident Profile: John Doe</h1>
        <div>
            <span class="badge badge-approved" style="font-size: 0.85rem; padding: 0.4rem 1rem;">active / approved</span>
        </div>
    </div>
</div>

<div class="grid grid-3" style="align-items: start;">
    
    <!-- Left Column: Summary & Flat Metadata -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Avatar card -->
        <div class="card" style="text-align: center; padding: 2rem 1.5rem;">
            <div class="db-sidebar-avatar" style="width: 4.5rem; height: 4.5rem; font-size: 1.5rem; margin: 0 auto 1rem auto; background-color: var(--primary-color);">
                JD
            </div>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.25rem;">John Doe</h3>
            <span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d; font-size: 0.75rem;">Flat Owner Occupant</span>
            
            <div style="border-top: 1px solid var(--border-color); margin-top: 1.5rem; padding-top: 1rem; text-align: left; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <div><span class="text-muted">Email:</span> <strong style="color: var(--text-primary);">john@example.com</strong></div>
                <div><span class="text-muted">Phone:</span> <strong style="color: var(--text-primary);">+880 1711 223344</strong></div>
                <div><span class="text-muted">Assigned Flat:</span> <strong style="color: var(--primary-color);">Flat 3B (Tower 1)</strong></div>
                <div><span class="text-muted">Parking Slot:</span> <strong style="color: var(--text-primary);">Slot P-88</strong></div>
            </div>
        </div>

        <!-- Billing Summary ledger -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Account Ledger</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.85rem;">
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Total Dues Invoiced:</span>
                    <span style="font-weight: 600;">৳ 27,000</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">Total Paid:</span>
                    <span style="font-weight: 600; color: var(--color-approved);">৳ 22,500</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 0.75rem; margin-top: 0.25rem;">
                    <span class="text-muted">Outstanding Dues:</span>
                    <strong style="color: var(--color-rejected); font-size: 1rem;">৳ 4,500</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Associated Members, Vehicles, and Tickets -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        <!-- Flat Members list -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Associated Flat Members</h3>
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Relation</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600;">Jane Doe</td>
                        <td>Spouse</td>
                        <td>+880 1711 556677</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Jimmy Doe</td>
                        <td>Son (Minor)</td>
                        <td>N/A</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Vehicles list -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Registered Vehicles</h3>
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Model</th>
                        <th>Plate Number</th>
                        <th>RFID status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600;">Car (Sedan)</td>
                        <td>Toyota Premio (Silver)</td>
                        <td>Dhaka Metro G-11-2233</td>
                        <td><span class="badge badge-approved">Active</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Maintenance History list -->
        <div class="card">
            <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Active Maintenance Tickets</h3>
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Issue Summary</th>
                        <th>Urgency</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 700;">#T-2033</td>
                        <td style="font-weight: 600;">Bathroom pipe leakage in master washroom</td>
                        <td><span class="badge badge-rejected" style="font-size: 0.65rem; padding: 0.1rem 0.4rem;">high</span></td>
                        <td><span class="badge badge-in-progress">in progress</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
