@extends('layouts.dashboard')

@section('title', 'File Complaint Ticket — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/resident/complaints') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Complaints
    </a>
    <h1 class="db-title">File Maintenance Complaint</h1>
    <p class="db-subtitle">Report a maintenance or repair issue to the building staff.</p>
</div>

<div style="max-width: 720px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">New Ticket Form</h3>
        
        {{-- This form submits to ResidentPortalController@storeComplaint, where StoreComplaintRequest validates the ticket fields. --}}
        <form action="{{ route('resident.complaints.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Subject -->
            <div class="form-group">
                <label for="comp-title" class="form-label">Subject / Issue Summary</label>
                <input type="text" id="comp-title" name="title" class="form-control" placeholder="e.g. Master bathroom faucet leakage" required>
            </div>

            <div class="grid grid-3">
                <!-- Category -->
                <div class="form-group">
                    <label for="comp-cat" class="form-label">Issue Category</label>
                    <select id="comp-cat" name="category" class="form-control form-select" required>
                        <option value="" disabled selected>Select category...</option>
                        <option value="plumbing">Plumbing / Leakage</option>
                        <option value="electrical">Electrical / Power</option>
                        <option value="carpentry">Carpentry / Furniture</option>
                        <option value="elevator">Elevator / Lift</option>
                        <option value="other">General / Common Area</option>
                    </select>
                </div>

                <!-- Urgency -->
                <div class="form-group">
                    <label for="comp-urgency" class="form-label">Urgency Level</label>
                    {{-- The request class maps this urgency input to the complaint priority saved in the database. --}}
                    <select id="comp-urgency" name="urgency" class="form-control form-select" required>
                        <option value="low">Low (Routine)</option>
                        <option value="medium" selected>Medium (Standard)</option>
                        <option value="high">High (Urgent Repair)</option>
                    </select>
                </div>

                <!-- Location -->
                <div class="form-group">
                    <label for="comp-location" class="form-label">Location</label>
                    <select id="comp-location" name="location" class="form-control form-select" required>
                        <option value="my_flat" selected>My Flat (Inside)</option>
                        <option value="corridor">Corridor / Lift Lobby</option>
                        <option value="parking">Parking Slot Area</option>
                        <option value="other">Other Common Area</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="comp-desc" class="form-label">Detailed Description</label>
                <textarea id="comp-desc" name="description" class="form-control" rows="5" placeholder="Describe the problem, when it started, and exact room details to help the technician..." required></textarea>
            </div>

            <!-- Photo Upload Placeholder -->
            <div class="form-group">
                <label class="form-label">Attach Photo of Issue <span class="text-muted" style="font-weight: normal;">(Optional)</span></label>
                <div class="file-upload-wrapper" id="file-drop-area" style="padding: 1.5rem;">
                    <input type="file" id="comp-photo" name="attachment_photo" class="file-upload-input" accept="image/*">
                    <div class="file-upload-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 2rem; height: 2rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                        <span style="font-weight: 600; font-size: 0.9rem;">Browse problem photo</span>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">JPG, JPEG or PNG formats (Max. 4MB)</span>
                        <span id="file-chosen-name" style="font-size: 0.8rem; color: var(--primary-color); font-weight: 700; display: none; margin-top: 0.5rem;"></span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <a href="{{ url('/resident/complaints') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Submit Complaint Ticket</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('comp-photo');
        const fileNameSpan = document.getElementById('file-chosen-name');
        
        if (fileInput && fileNameSpan) {
            // Frontend-only helper: shows the chosen file name before Laravel receives the form submission.
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameSpan.textContent = 'Selected Image: ' + this.files[0].name;
                    fileNameSpan.style.display = 'block';
                } else {
                    fileNameSpan.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
