@extends('layouts.dashboard')

@section('title', 'Manage Referendums & Polls — Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Manage Society Referendums</h1>
    <p class="db-subtitle">Create new ballot proposals, inspect voter turnout, and close voting cycles.</p>
</div>

<div class="grid grid-2" style="align-items: start;">
    
    <!-- Left Column: Create Poll Form -->
    <div class="card">
        <h3 style="margin-bottom: 1.25rem;">New Referendum Proposal</h3>
        
        <form action="#" method="POST">
            @csrf
            
            <!-- Question -->
            <div class="form-group">
                <label for="poll-question" class="form-label">Ballot Question</label>
                <textarea id="poll-question" name="question" class="form-control" rows="4" placeholder="e.g. Should we authorize a 10% increase in the maintenance budget to upgrade gym equipment?" required></textarea>
            </div>

            <!-- Options -->
            <div class="form-group">
                <label class="form-label">Voting Options</label>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <input type="text" class="form-control" value="Option 1: Yes, I approve the upgrade" readonly style="background-color: var(--bg-main);">
                    <input type="text" class="form-control" value="Option 2: No, I reject the upgrade" readonly style="background-color: var(--bg-main);">
                    <input type="text" class="form-control" placeholder="Add custom option (e.g. Abstain)...">
                </div>
            </div>

            <!-- End Date -->
            <div class="form-group">
                <label for="poll-end" class="form-label">Ballot Closing Date</label>
                <input type="date" id="poll-end" name="closes_at" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
            </div>

            <button type="button" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="alert('Mock Referendum proposal published.');">
                Publish Referendum Ballot
            </button>
        </form>
    </div>

    <!-- Right Column: Current referendums catalog -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <h2 style="font-size: 1.2rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; color: var(--text-primary);">Active & Past Ballots</h2>
        
        <!-- Active Poll Card -->
        <div class="card-static">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span class="badge badge-emergency" style="background-color: #dbeafe; color: #2563eb; animation: none;">voting active</span>
                <span style="font-size: 0.75rem; color: var(--text-secondary);">Closes: July 15, 2026</span>
            </div>
            
            <h4 style="font-size: 0.95rem; line-height: 1.4; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Should we install solar panels on the rooftop?</h4>
            <div style="font-size: 0.8rem; color: var(--text-secondary);">Total Turnout: <strong>89 of 111 Residents Voted</strong> (80.1%)</div>
        </div>

        <!-- Closed Poll Card -->
        <div class="card-static">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span class="badge badge-approved" style="background-color: var(--border-color); color: var(--text-secondary);">closed</span>
                <span style="font-size: 0.75rem; color: var(--text-secondary);">Ended: June 10, 2026</span>
            </div>
            
            <h4 style="font-size: 0.95rem; line-height: 1.4; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Proposal to upgrade basement CCTV camera coverage.</h4>
            <div style="font-size: 0.8rem; color: var(--text-secondary);">Winner Option: <strong>Yes (72%)</strong> — Total votes: 150</div>
        </div>
    </div>

</div>
@endsection
