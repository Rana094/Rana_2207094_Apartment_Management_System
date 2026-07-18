@extends('layouts.dashboard')

@section('title', 'Update Work Order #T-'.$workOrder->id.' - Nestora')

@section('content')
<div class="db-header">
    <a href="{{ route('maintenance.show', $workOrder) }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        Back to Ticket Details
    </a>
    <h1 class="db-title">Update Work Order Status</h1>
    <p class="db-subtitle">Transition task status, provide repair remarks, and attach completion proof for manager verification.</p>
</div>

<div style="max-width: 680px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">Progress Update Report</h3>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('maintenance.orders.update', $workOrder) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Job Reference</label>
                <input type="text" class="form-control" value="{{ $workOrder->title }} (#T-{{ $workOrder->id }}) - {{ $workOrder->complaint?->flat?->flat_number ?? 'No flat' }}" readonly style="background-color: var(--bg-main); font-weight: 600;">
            </div>

            <div class="form-group">
                <label for="work-status" class="form-label">Update Task Status</label>
                <select id="work-status" name="status" class="form-control form-select" required>
                    <option value="in_progress" @selected(old('status', $workOrder->status) === 'in_progress')>Keep in Progress</option>
                    <option value="completed" @selected(old('status', $workOrder->status) === 'completed')>Mark Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="work-remarks" class="form-label">Resolution Details & Remarks</label>
                <textarea id="work-remarks" name="remarks" class="form-control" rows="4" placeholder="Describe what repairs were done..." required>{{ old('remarks') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Attach Completion Proof</label>
                <div class="file-upload-wrapper" id="file-drop-area" style="padding: 1.5rem;">
                    <input type="file" id="work-photo" name="completion_photo" class="file-upload-input" accept=".pdf,.png,.jpg,.jpeg">
                    <div class="file-upload-placeholder">
                        <x-icon name="uplaod" alt="" size="2rem" />
                        <span style="font-weight: 700; font-size: 0.9rem; margin-top: 0.25rem;">Browse repair proof</span>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">PDF, JPG, JPEG or PNG formats allowed</span>
                        <span id="file-chosen-name" style="font-size: 0.8rem; color: var(--primary-color); font-weight: 700; display: none; margin-top: 0.5rem;"></span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <a href="{{ route('maintenance.show', $workOrder) }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Submit Update</button>
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
                    fileNameSpan.textContent = 'Selected File: ' + this.files[0].name;
                    fileNameSpan.style.display = 'block';
                } else {
                    fileNameSpan.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
