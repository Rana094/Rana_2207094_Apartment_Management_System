@extends('layouts.dashboard')

@section('title', 'Notices - Nestora')

@section('content')
<div class="db-header"><h1 class="db-title">Broadcast Society Announcements</h1><p class="db-subtitle">Publish notices to resident notification feeds.</p></div>
@if(session('status'))<div class="alert alert-success" style="margin-bottom:1rem;">{{ session('status') }}</div>@endif
@if($errors->any())<div class="alert alert-danger" style="margin-bottom:1rem;">{{ $errors->first() }}</div>@endif
<div class="grid grid-3" style="align-items:start;">
    <div class="card">
        <h3 style="margin-bottom:1rem;">Create Announcement</h3>
        <form method="POST" action="{{ route('manager.notices.store') }}">@csrf
            <div class="form-group"><label class="form-label">Title</label><input name="title" class="form-control" value="{{ old('title') }}" required></div>
            <div class="form-group"><label class="form-label">Audience / Category</label><select name="category" class="form-control form-select"><option value="all">All Residents</option><option value="maintenance">Maintenance</option><option value="meeting">Meeting</option><option value="emergency">Emergency</option></select></div>
            <div class="form-group"><label class="form-label">Content</label><textarea name="content" class="form-control" rows="5" required>{{ old('content') }}</textarea></div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Broadcast Notice</button>
        </form>
    </div>
    <div style="grid-column:span 2;display:flex;flex-direction:column;gap:1rem;">
        @forelse($notices as $notice)
            <div class="card-static"><div style="display:flex;justify-content:space-between;gap:1rem;"><h4>{{ $notice->title }}</h4><span class="badge badge-approved">{{ $notice->audience }}</span></div><p style="margin:.75rem 0;">{{ $notice->body }}</p><div style="display:flex;justify-content:space-between;align-items:center;"><span class="text-muted text-xs">{{ $notice->published_at?->format('M d, Y H:i') }}</span><form id="delete-notice-{{ $notice->id }}" method="POST" action="{{ route('manager.notices.destroy',$notice) }}">@csrf @method('DELETE')<button type="button" class="btn btn-danger btn-sm" onclick="showConfirmModal('Delete notice?', 'This announcement will be removed.', function(){ document.getElementById('delete-notice-{{ $notice->id }}').submit(); }, true)">Delete</button></form></div></div>
        @empty<div class="card-static">No notices published.</div>@endforelse
        <div class="pagination-btns">@if($notices->previousPageUrl())<a href="{{ $notices->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>@endif @if($notices->nextPageUrl())<a href="{{ $notices->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>@endif</div>
    </div>
</div>
@endsection
