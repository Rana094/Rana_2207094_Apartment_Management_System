@extends('layouts.dashboard')

@section('title', 'Registration Documents - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Registration Documents</h1>
    <p class="db-subtitle">Review resident signup files and documents uploaded from resident portals.</p>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Resident Signup Files</h3>
    <div class="table-responsive">
        <table class="db-table">
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Resident Type</th>
                    <th>Requested Flat</th>
                    <th>Account Status</th>
                    <th>Submitted</th>
                    <th style="text-align: right;">File</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registrationDocuments as $resident)
                    <tr>
                        <td>
                            <strong>{{ $resident->name }}</strong>
                            <div class="text-muted text-xs">{{ $resident->email }}</div>
                        </td>
                        <td>{{ ucfirst($resident->resident_type ?: 'resident') }}</td>
                        <td>{{ $resident->flat_info ?: 'Not provided' }}</td>
                        <td><span class="badge badge-pending-verification">{{ str_replace('_', ' ', ucfirst($resident->status)) }}</span></td>
                        <td>{{ $resident->created_at?->format('M d, Y') }}</td>
                        <td style="text-align: right;">
                            @if ($resident->file_available)
                                <a href="{{ $resident->signupDocumentUrl() }}" class="btn btn-outline btn-sm">Download</a>
                            @else
                                <span class="text-muted text-xs">File unavailable</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                            No signup documents have been submitted.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($registrationDocuments->hasPages())
        <div class="table-pagination" style="margin-top: 1rem;">{{ $registrationDocuments->withQueryString()->links() }}</div>
    @endif
</div>

<div class="card">
    <h3 style="font-size: 1.15rem; margin-bottom: 1rem;">Resident Uploaded Documents</h3>
    <div class="table-responsive">
        <table class="db-table">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Resident</th>
                    <th>Flat</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Uploaded</th>
                    <th style="text-align: right;">File</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($residentDocuments as $document)
                    <tr>
                        <td><strong>{{ $document->title }}</strong></td>
                        <td>{{ $document->user?->name ?? 'Unknown resident' }}</td>
                        <td>{{ $document->flat?->flat_number ?? 'Not assigned' }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($document->type)) }}</td>
                        <td><span class="badge badge-pending-verification">{{ str_replace('_', ' ', ucfirst($document->status)) }}</span></td>
                        <td>{{ $document->created_at?->format('M d, Y') }}</td>
                        <td style="text-align: right;">
                            @if ($document->file_available)
                                @if ($document->isPreviewable())
                                    <a href="{{ $document->previewUrl() }}" class="btn btn-outline btn-sm" target="_blank" rel="noopener">Open</a>
                                @endif
                                <a href="{{ $document->secureUrl() }}" class="btn btn-outline btn-sm">Download</a>
                            @else
                                <span class="text-muted text-xs">File unavailable</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                            No resident documents have been uploaded.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($residentDocuments->hasPages())
        <div class="table-pagination" style="margin-top: 1rem;">{{ $residentDocuments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
