@extends('layouts.dashboard')

@section('title', 'Society Polls & Voting — Nestora')

@section('content')
<style>
    /* Poll Result progress bar styling */
    .poll-bar-wrapper {
        margin-bottom: 1rem;
    }
    .poll-bar-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    .poll-bar-bg {
        width: 100%;
        height: 10px;
        background-color: var(--border-color);
        border-radius: var(--radius-full);
        overflow: hidden;
    }
    .poll-bar-fill {
        height: 100%;
        background-color: var(--primary-color);
        border-radius: var(--radius-full);
    }
</style>

<div class="db-header">
    <h1 class="db-title">Polls and Democratic Voting</h1>
    <p class="db-subtitle">Participate in building elections, facility upgrades decisions, and policy referendums.</p>
</div>

<div class="grid grid-2" style="align-items: start;">
    
    <!-- Active Polls Section -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; color: var(--text-primary);">Active Polls</h2>
        
        <!-- Active Poll Card -->
        <div class="card" id="active-poll-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <span class="badge badge-emergency" style="font-size: 0.7rem; animation: none;">voting open</span>
                <span class="text-muted" style="font-size: 0.75rem;">Closes: July 15, 2026</span>
            </div>
            
            <h3 style="font-size: 1.1rem; line-height: 1.4; margin-bottom: 1rem;">Should we allocate service funds to install rooftop solar panels to power common lobby lights?</h3>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem;">Estimated initial setup cost is ৳2,00,000, which will reduce monthly common area electric bills by approximately 35% in the long run.</p>
            
            <!-- Voting Options Form -->
            <form action="#" method="POST" id="voting-form">
                @csrf
                <div class="form-group" style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <label class="remember-label" style="background-color: var(--bg-main); border: 1px solid var(--border-color); padding: 0.75rem 1rem; border-radius: var(--radius-md); font-weight: 600;">
                        <input type="radio" name="vote_option" value="yes" class="form-checkbox" style="margin-right: 0.5rem;">
                        Yes, I approve the installation.
                    </label>
                    <label class="remember-label" style="background-color: var(--bg-main); border: 1px solid var(--border-color); padding: 0.75rem 1rem; border-radius: var(--radius-md); font-weight: 600;">
                        <input type="radio" name="vote_option" value="no" class="form-checkbox" style="margin-right: 0.5rem;">
                        No, I do not approve.
                    </label>
                    <label class="remember-label" style="background-color: var(--bg-main); border: 1px solid var(--border-color); padding: 0.75rem 1rem; border-radius: var(--radius-md); font-weight: 600;">
                        <input type="radio" name="vote_option" value="abstain" class="form-checkbox" style="margin-right: 0.5rem;">
                        Abstain / Undecided.
                    </label>
                </div>
                
                <button type="button" class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="submitVote()">
                    Cast Secret Ballot Vote
                </button>
            </form>
            
            <!-- Hidden results showing after vote is cast -->
            <div id="cast-success-state" style="display: none; text-align: center; padding: 1.5rem 0;">
                <div class="badge badge-approved" style="padding: 0.5rem 1rem; margin-bottom: 1rem; font-size: 0.85rem;">Vote Cast Successfully</div>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">Thank you for participating! Results will be broadcasted once voting closes on July 15.</p>
            </div>
        </div>
    </div>

    <!-- Closed Polls Results Section -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; color: var(--text-primary);">Past Poll Results</h2>
        
        <!-- Closed Poll Card 1 -->
        <div class="card-static">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <span class="badge badge-approved" style="background-color: var(--border-color); color: var(--text-secondary);">closed</span>
                <span class="text-muted" style="font-size: 0.75rem;">Ended: June 10, 2026</span>
            </div>
            
            <h3 style="font-size: 1.05rem; margin-bottom: 1.25rem;">Proposal to upgrade basement CCTV camera coverage.</h3>
            
            <!-- Result 1: Approve -->
            <div class="poll-bar-wrapper">
                <div class="poll-bar-label">
                    <span>Option: Yes (Approve upgrade)</span>
                    <span>72% (108 Votes)</span>
                </div>
                <div class="poll-bar-bg">
                    <div class="poll-bar-fill" style="width: 72%; background-color: var(--secondary-color);"></div>
                </div>
            </div>

            <!-- Result 2: Reject -->
            <div class="poll-bar-wrapper">
                <div class="poll-bar-label">
                    <span>Option: No (Keep existing)</span>
                    <span>28% (42 Votes)</span>
                </div>
                <div class="poll-bar-bg">
                    <div class="poll-bar-fill" style="width: 28%; background-color: var(--text-muted);"></div>
                </div>
            </div>
            
            <div style="font-size: 0.75rem; color: var(--text-muted); text-align: right; border-top: 1px solid var(--border-color); padding-top: 0.75rem; margin-top: 1rem;">
                Status: <strong>Approved by Board</strong> (Technician has been hired)
            </div>
        </div>
    </div>

</div>

<script>
    function submitVote() {
        const form = document.getElementById('voting-form');
        const success = document.getElementById('cast-success-state');
        const selected = document.querySelector('input[name="vote_option"]:checked');
        
        if (!selected) {
            alert('Please select an option to vote.');
            return;
        }

        if (form && success) {
            form.style.display = 'none';
            success.style.display = 'block';
        }
    }
</script>
@endsection
