@extends('layouts.dashboard')

@section('title', 'My Documents Directory — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Document Directory</h1>
    <p class="db-subtitle">Upload and manage personal verification documents, flat deeds, lease agreements, and billing history.</p>
</div>

<div class="grid grid-3" style="align-items: start;">
    <!-- Left Column: Upload Document Form (1 Column) -->
    <div class="card" style="grid-column: span 1;">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">Upload Document</h3>
        
        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="doc-title" class="form-label">Document Title</label>
                <input type="text" id="doc-title" name="title" class="form-control" placeholder="e.g. Passport Copy, July Gas Bill" required>
            </div>
            
            <div class="form-group">
                <label for="doc-cat" class="form-label">Document Category</label>
                <select id="doc-cat" name="category" class="form-control form-select" required>
                    <option value="" disabled selected>Select category...</option>
                    <option value="identity">Identity Proof (NID / Passport)</option>
                    <option value="contract">Lease / Rental Agreement</option>
                    <option value="utility">Utility / Service Bill Archive</option>
                    <option value="other">Other Miscellaneous</option>
                </select>
            </div>
            
            <!-- Mini File Upload box -->
            <div class="form-group">
                <label class="form-label">Choose Document File</label>
                <div class="file-upload-wrapper" id="file-drop-area" style="padding: 1rem;">
                    <input type="file" id="doc-file" name="document_file" class="file-upload-input" accept=".pdf,.png,.jpg,.jpeg" required>
                    <div class="file-upload-placeholder" style="gap: 0.25rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span style="font-weight: 600; font-size: 0.8rem;">Select PDF / Image</span>
                        <span id="file-chosen-name" style="font-size: 0.75rem; color: var(--primary-color); font-weight: 700; display: none;"></span>
                    </div>
                </div>
            </div>
            
            <button type="button" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="alert('Document upload request submitted.');">
                Upload File
            </button>
        </form>
    </div>

    <!-- Right Column: Document List Table (2 Columns) -->
    <div class="table-responsive" style="grid-column: span 2;">
        <div class="table-toolbar">
            <div class="table-toolbar-left">
                <select class="form-control form-select" style="max-width: 200px;">
                    <option value="">All Document Categories</option>
                    <option value="identity">Identity Proofs</option>
                    <option value="contract">Lease Contracts</option>
                    <option value="utility">Utility Bills</option>
                </select>
            </div>
        </div>
        
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
                <tr>
                    <td style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <!-- PDF Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; color: #dc2626; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12h9m-9-3h9m-9-3h9m-9 12H5.625c-.621 0-1.125-.504-1.125-1.125V3.375c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125V11.25a9 9 0 0 0-9 9Z" />
                        </svg>
                        NID_Copy_JohnDoe.pdf
                    </td>
                    <td>Identity Proof</td>
                    <td>July 01, 2026</td>
                    <td>1.2 MB</td>
                    <td><span class="badge badge-approved">verified</span></td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem;">
                            <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="alert('Downloading NID document...');">Download</button>
                            <button type="button" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem;" onclick="showConfirmModal('Delete Document?', 'Delete NID_Copy_JohnDoe.pdf? Verified status will be lost.', function(){ alert('Document deleted.'); }, true)">Delete</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.25rem; height: 1.25rem; color: #dc2626; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12h9m-9-3h9m-9-3h9m-9 12H5.625c-.621 0-1.125-.504-1.125-1.125V3.375c0-.621.504-1.125 1.125-1.125h12.75c.621 0 1.125.504 1.125 1.125V11.25a9 9 0 0 0-9 9Z" />
                        </svg>
                        Lease_Agreement_3B.pdf
                    </td>
                    <td>Rental Lease</td>
                    <td>July 01, 2026</td>
                    <td>3.4 MB</td>
                    <td><span class="badge badge-approved">verified</span></td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem;">
                            <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="alert('Downloading Lease document...');">Download</button>
                            <button type="button" class="btn btn-danger btn-sm" style="padding: 0.25rem 0.5rem;" onclick="showConfirmModal('Delete Lease Agreement?', 'Delete lease copy from directory?', function(){ alert('Document deleted.'); }, true)">Delete</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="table-pagination">
            <div class="pagination-info">Showing <strong>2</strong> documents</div>
            <div class="pagination-btns">
                <button type="button" class="btn btn-outline btn-sm" disabled>Previous</button>
                <button type="button" class="btn btn-outline btn-sm" disabled>Next</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('doc-file');
        const fileNameSpan = document.getElementById('file-chosen-name');
        
        if (fileInput && fileNameSpan) {
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
