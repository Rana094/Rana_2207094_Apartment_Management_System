@extends('layouts.dashboard')

@section('title', 'Update Work Order #T-2033 — Nestora')

@section('content')
<div class="db-header">
    <a href="{{ url('/maintenance/orders/2033') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 0.85rem; height: 0.85rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
        Back to Ticket Details
    </a>
    <h1 class="db-title">Update Work Order Status</h1>
    <p class="db-subtitle">Transition task status, provide repair remarks, and attach completion photos for manager verification.</p>
</div>

<div style="max-width: 680px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">Progress Update Report</h3>
        
        <form action="{{ url('/maintenance/dashboard') }}" method="GET" enctype="multipart/form-data">
            <!-- Hidden session success flag -->
            <input type="hidden" name="update_logged" value="1">
            
            <!-- Reference -->
            <div class="form-group">
                <label class="form-label">Job Reference</label>
                <input type="text" class="form-control" value="Bathroom pipe leakage (#T-2033) — Flat 3B" readonly style="background-color: var(--bg-main); font-weight: 600;">
            </div>

            <!-- Status Select -->
            <div class="form-group">
                <label for="work-status" class="form-label">Update Task Status</label>
                <select id="work-status" name="status" class="form-control form-select" required>
                    <option value="in_progress">Keep in Progress</option>
                    <option value="resolved" selected>Mark Resolved / Completed</option>
                </select>
            </div>

            <!-- Resolution Remarks -->
            <div class="form-group">
                <label for="work-remarks" class="form-label">Resolution Details & Remarks</label>
                <textarea id="work-remarks" name="remarks" class="form-control" rows="4" placeholder="Describe what repairs were done (e.g. replaced the basin joint washer, tested water pressure for 10 minutes and no leakage was found)..." required></textarea>
            </div>

            <!-- Completion proof photo uploader -->
            <div class="form-group">
                <label class="form-label">Attach Completion Proof Photo</label>
                <div class="file-upload-wrapper" id="file-drop-area" style="padding: 1.5rem;">
                    <input type="file" id="work-photo" name="completion_photo" class="file-upload-input" accept="image/*" required>
                    <div class="file-upload-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 2rem; height: 2rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span style="font-weight: 700; font-size: 0.9rem; margin-top: 0.25rem;">Browse repair photo</span>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">JPG, JPEG or PNG formats allowed (Max. 3MB)</span>
                        <span id="file-chosen-name" style="font-size: 0.8rem; color: var(--primary-color); font-weight: 700; display: none; margin-top: 0.5rem;"></span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <a href="{{ url('/maintenance/orders/2033') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">
                    Submit Completion Details
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('work-photo');
        const fileNameSpan = document.getElementById('file-chosen-name');
        
        if (fileInput && fileNameSpan) {
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
