@extends('layouts.dashboard')

@section('title', 'My Documents Directory - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Document Directory</h1>
    <p class="db-subtitle">Upload and manage personal verification documents, flat deeds, lease agreements, and billing history.</p>
</div>

<div class="grid grid-3" style="align-items: start;">
    <div class="card" style="grid-column: span 1;">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">Upload Document</h3>

        {{-- ResidentPortalController@storeDocument validates the upload and stores the file privately with FileUploadService. --}}
        <form action="{{ route('resident.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="doc-title" class="form-label">Document Title</label>
                <input type="text" id="doc-title" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Passport Copy, July Gas Bill" required>
            </div>

            <div class="form-group">
                <label for="doc-cat" class="form-label">Document Category</label>
                <select id="doc-cat" name="category" class="form-control form-select" required>
                    <option value="" disabled @selected(! old('category'))>Select category...</option>
                    <option value="identity" @selected(old('category') === 'identity')>Identity Proof (NID / Passport)</option>
                    <option value="contract" @selected(old('category') === 'contract')>Lease / Rental Agreement</option>
                    <option value="utility" @selected(old('category') === 'utility')>Utility / Service Bill Archive</option>
                    <option value="other" @selected(old('category') === 'other')>Other Miscellaneous</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Choose Document File</label>
                <div class="file-upload-wrapper" id="file-drop-area" style="padding: 1rem;">
                    <input type="file" id="doc-file" name="document_file" class="file-upload-input" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" required>
                    <div class="file-upload-placeholder" style="gap: 0.25rem;">
                        <x-icon name="uplaod" alt="" size="1.5rem" />
                        <span style="font-weight: 600; font-size: 0.8rem;">Select PDF / Word / Image</span>
                        <span id="file-chosen-name" style="font-size: 0.75rem; color: var(--primary-color); font-weight: 700; display: none;"></span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                Upload File
            </button>
        </form>
    </div>

    <div class="table-responsive" style="grid-column: span 2;">
        <table class="db-table">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Category</th>
                    <th>Upload Date</th>
                    <th>File Size</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- These links use protected FileAccessController routes, so files are not exposed from the public folder. --}}
                @forelse ($documents as $document)
                    <tr>
                        <td style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <x-icon name="document" alt="" size="1.25rem" />
                            {{ $document->title }}
                        </td>
                        <td>{{ str_replace('_', ' ', ucfirst($document->type)) }}</td>
                        <td>{{ $document->created_at?->format('M d, Y') }}</td>
                        <td>{{ number_format(($document->file_size ?? 0) / 1024, 1) }} KB</td>
                        <td><span class="badge badge-{{ $document->status === 'verified' ? 'approved' : 'pending-verification' }}">{{ str_replace('_', ' ', $document->status) }}</span></td>
                        <td style="text-align: right;">
                            @if ($document->isPreviewable())
                                <a href="{{ $document->previewUrl() }}" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" target="_blank" rel="noopener">Open</a>
                            @endif
                            <a href="{{ $document->secureUrl() }}" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;">Download</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No documents uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($documents->hasPages())
            <div class="table-pagination" style="margin-top: 1rem;">{{ $documents->links() }}</div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('doc-file');
        const fileNameSpan = document.getElementById('file-chosen-name');

        if (fileInput && fileNameSpan) {
            // UI feedback only; Laravel still validates file type, size, and storage after submit.
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileNameSpan.textContent = 'Selected: ' + this.files[0].name;
                    fileNameSpan.style.display = 'block';
                } else {
                    fileNameSpan.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
