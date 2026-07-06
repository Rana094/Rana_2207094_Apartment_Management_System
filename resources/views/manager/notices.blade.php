@extends('layouts.dashboard')

@section('title', 'Society Notices & Broadcasting — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Broadcast Society Announcements</h1>
    <p class="db-subtitle">Post notifications, rules changes, and warnings to the resident portal dashboard board feeds.</p>
</div>

<div class="grid grid-3" style="align-items: start;">
    
    <!-- Left Column: Notice creation Form (1 Column) -->
    <div class="card" style="grid-column: span 1;">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem;">Create Announcement</h3>
        
        <form action="#" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="not-title" class="form-label">Notice Title</label>
                <input type="text" id="not-title" name="title" class="form-control" placeholder="e.g. Water tank cleaning schedule" required>
            </div>
            
            <div class="form-group">
                <label for="not-cat" class="form-label">Notice Category</label>
                <select id="not-cat" name="category" class="form-control form-select" required>
                    <option value="general" selected>General Society Info</option>
                    <option value="maintenance">Maintenance/Utility disruption</option>
                    <option value="meeting">Committee Meetups</option>
                    <option value="emergency">High-priority Alert</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="not-body" class="form-label">Announcement Content</label>
                <textarea id="not-body" name="content" class="form-control" rows="5" placeholder="Enter notice bulletin details..." required></textarea>
            </div>
            
            <button type="button" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="alert('Announcement posted successfully.');">
                Broadcast Notice
            </button>
        </form>
    </div>

    <!-- Right Column: Broadcast logs (2 Columns) -->
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: 1.5rem;">
        <h2 style="font-size: 1.2rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; color: var(--text-primary);">Broadcast Log</h2>
        
        <!-- Log card 1 -->
        <div class="card-static" style="display: flex; gap: 1rem; align-items: start;">
            <div style="width: 2.25rem; height: 2.25rem; background-color: var(--primary-light); color: var(--primary-color); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                📢
            </div>
            <div style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.5rem;">
                    <h4 style="font-weight: 700; font-size: 0.95rem; color: var(--text-primary); margin-bottom: 0.25rem;">Generator Repairs Scheduled</h4>
                    <span class="badge badge-pending" style="background-color: var(--secondary-light); color: var(--secondary-color); font-size: 0.65rem;">Maintenance</span>
                </div>
                <p style="font-size: 0.825rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0.5rem;">
                    The standby building generator will undergo repairs. Elevators will run on battery backup.
                </p>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--text-muted);">
                    <span>Posted on: July 3, 2026</span>
                    <button type="button" class="btn btn-danger btn-xs" style="padding: 0.1rem 0.4rem; font-size: 0.7rem;" onclick="showConfirmModal('Delete Notice?', 'Remove this notice announcement?', function(){ alert('Notice deleted.'); }, true)">Delete notice</button>
                </div>
            </div>
        </div>

        <!-- Log card 2 -->
        <div class="card-static" style="display: flex; gap: 1rem; align-items: start;">
            <div style="width: 2.25rem; height: 2.25rem; background-color: var(--primary-light); color: var(--primary-color); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                📢
            </div>
            <div style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 0.5rem;">
                    <h4 style="font-weight: 700; font-size: 0.95rem; color: var(--text-primary); margin-bottom: 0.25rem;">Q3 General Committee Meeting</h4>
                    <span class="badge badge-approved" style="background-color: #dcfce7; color: #15803d; font-size: 0.65rem;">Committee Meeting</span>
                </div>
                <p style="font-size: 0.825rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 0.5rem;">
                    Join the upcoming housing committee meeting in the community room to elect members.
                </p>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--text-muted);">
                    <span>Posted on: June 28, 2026</span>
                    <button type="button" class="btn btn-danger btn-xs" style="padding: 0.1rem 0.4rem; font-size: 0.7rem;" onclick="showConfirmModal('Delete Notice?', 'Remove this notice announcement?', function(){ alert('Notice deleted.'); }, true)">Delete notice</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
