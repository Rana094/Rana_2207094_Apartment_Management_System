@extends('layouts.dashboard')

@section('title', 'My Flat Details — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Flat Details</h1>
    <p class="db-subtitle">Overview of your assigned unit, family members, and registered vehicles.</p>
</div>

<!-- Grid Layout -->
<div class="grid grid-3" style="align-items: start;">
    
    <!-- Left Column: Unit Metadata Details -->
    <div class="card" style="grid-column: span 1;">
        <div style="text-align: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
            <div class="stat-icon primary" style="width: 4rem; height: 4rem; margin: 0 auto 1rem auto; background-color: var(--primary-light); color: var(--primary-color); border-radius: var(--radius-lg);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 2rem; height: 2rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21" />
                </svg>
            </div>
            <h2 style="font-size: 1.5rem; margin-bottom: 0.25rem;">Flat 3B</h2>
            <span class="badge badge-approved">owner occupied</span>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.9rem;">
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Building Block:</span>
                <span style="font-weight: 600;">Building A (Tower 1)</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Floor Level:</span>
                <span style="font-weight: 600;">3rd Floor</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Square Footage:</span>
                <span style="font-weight: 600;">1,650 Sq Ft</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Bedrooms / Baths:</span>
                <span style="font-weight: 600;">3 Bed / 3 Bath</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 0.5rem;">
                <span class="text-muted">Parking Slot:</span>
                <span style="font-weight: 700; color: var(--secondary-color);">Slot P-88</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Members and Vehicles (Span 2) -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Flat Member Directory Card -->
        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem;">Flat Members</h3>
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Relationship</th>
                        <th>Contact Number</th>
                        <th>NID Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600;">John Doe<div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Primary Account Holder</div></td>
                        <td>Self</td>
                        <td>+880 1711 223344</td>
                        <td><span class="badge badge-approved">verified</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Jane Doe</td>
                        <td>Spouse</td>
                        <td>+880 1711 556677</td>
                        <td><span class="badge badge-approved">verified</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Jimmy Doe</td>
                        <td>Son (Minor)</td>
                        <td>N/A</td>
                        <td><span class="badge badge-pending-verification">pending birth cert</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Vehicle Registrations Card -->
        <div class="card">
            <h3 style="font-size: 1.2rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                <span>Registered Vehicles</span>
                <button type="button" class="btn btn-outline btn-sm" onclick="alert('Mock: Add vehicle request popup.');">Add Vehicle</button>
            </h3>
            
            <table class="db-table" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th>Vehicle Type</th>
                        <th>Model & Color</th>
                        <th>License Plate</th>
                        <th>RFID Tag Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <!-- Car Icon SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; color: var(--primary-color);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.825-13.125A1.125 1.125 0 0 0 19.5 3h-15a1.125 1.125 0 0 0-1.115.956l-.825 13.125A1.125 1.125 0 0 0 3.682 18.75m16.5-4.5H3.682" />
                            </svg>
                            Car (Sedan)
                        </td>
                        <td>Toyota Premio (Silver)</td>
                        <td>Dhaka Metro G-11-2233</td>
                        <td><span class="badge badge-approved">active rfid</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <!-- Motorbike Icon SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; color: var(--secondary-color);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3v18M18.75 3v18M5.25 3v18" />
                            </svg>
                            Motorbike
                        </td>
                        <td>Yamaha FZS (Black)</td>
                        <td>Dhaka Metro H-44-5566</td>
                        <td><span class="badge badge-pending">tag pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
