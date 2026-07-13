@extends('layouts.dashboard')

@section('title', 'Gate Emergency Panel — Nestora')

@section('content')
<style>
    .panic-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .panic-card {
        background-color: #fff;
        border: 2px solid #fda4af;
        border-radius: var(--radius-lg);
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: var(--shadow-lg);
        background: radial-gradient(circle at 50% 10%, rgba(244, 63, 94, 0.05) 0%, rgba(255, 255, 255, 0) 80%);
    }
    .panic-btn-giant {
        width: 10rem;
        height: 10rem;
        border-radius: var(--radius-full);
        background-color: var(--color-emergency);
        color: #ffffff;
        border: 8px solid #ffe4e6;
        font-size: 1.25rem;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.5rem;
        margin: 1.5rem auto;
        box-shadow: 0 10px 25px -5px rgba(225, 29, 72, 0.4);
        transition: var(--transition-normal);
        animation: pulse-border-emergency 1.5s infinite;
    }
    .panic-btn-giant:hover {
        transform: scale(1.05);
        background-color: #be123c;
    }
    @keyframes pulse-border-emergency {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(225, 29, 72, 0.4), 0 10px 25px -5px rgba(225, 29, 72, 0.4);
        }
        50% {
            box-shadow: 0 0 0 20px rgba(225, 29, 72, 0), 0 10px 25px -5px rgba(225, 29, 72, 0.4);
        }
    }
</style>

<div class="db-header">
    <h1 class="db-title" style="color: var(--color-emergency);">Gate Emergency Alarm Dispatch</h1>
    <p class="db-subtitle">Trigger an instant priority alarm to the Manager's Office and sound the local gate lobby sirens.</p>
</div>

<div class="panic-container">
    <div class="panic-card">
        <div style="background-color: var(--bg-emergency); color: var(--color-emergency); width: 3rem; height: 3rem; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
            <!-- Alert Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 1.5rem; height: 1.5rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>

        <h3 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 0.5rem;">Instant Panic Dispatch</h3>
        <p style="font-size: 0.875rem; color: var(--text-secondary); max-width: 440px; margin: 0 auto 1.5rem auto;">
            Select the emergency category below. Pressing the button immediately updates manager dashboards and signals gate lock security overrides.
        </p>

        <form action="#" method="POST" id="panic-form">
            @csrf
            
            <div class="form-group" style="max-width: 320px; margin: 0 auto 1.5rem auto;">
                <label for="emerg-type" class="form-label">Gate Alert Type</label>
                <select id="emerg-type" name="type" class="form-control form-select" style="text-align: center;" required>
                    <option value="security" selected>Security Breach / Suspicious Persons</option>
                    <option value="fire">Fire / Smoke at Complex</option>
                    <option value="power">Power Grid Failure / Elevator Trapped</option>
                    <option value="medical">Medical emergency at Lobby</option>
                </select>
            </div>
            
            <!-- Giant Panic Button -->
            <button type="button" class="panic-btn-giant" onclick="triggerPanicAlert()">
                🚨 DISPATCH
            </button>
        </form>

        <!-- Hidden Alert State after trigger -->
        <div id="panic-alert-state" style="display: none; padding: 1.5rem 0;">
            <div class="badge badge-emergency" style="padding: 0.75rem 1.5rem; border-radius: var(--radius-md); font-size: 1rem; margin-bottom: 1.5rem;">
                🚨 GATE SIREN ACTIVE
            </div>
            <p style="font-weight: 700; font-size: 1.1rem; color: var(--text-primary); margin-bottom: 0.5rem;">Manager Office Alerted</p>
            <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; max-width: 400px; margin: 0 auto 1.5rem auto;">
                Sirens are broadcasting at the primary supervisor board. Keep the main vehicle barrier closed and standby for police or emergency dispatch instructions.
            </p>
            <button type="button" class="btn btn-outline btn-sm" onclick="cancelPanicAlert()" style="border-color: var(--color-emergency); color: var(--color-emergency);">
                Deactivate Siren / Reset Alarm
            </button>
        </div>
        
        <!-- Quick Call helplines directory -->
        <div style="border-top: 1px solid var(--border-color); padding-top: 2rem; margin-top: 2rem; text-align: left; font-size: 0.85rem;">
            <h4 style="font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;">Society Helplines Directory:</h4>
            <div class="grid grid-2" style="gap: 1rem;">
                <div style="background-color: var(--bg-main); padding: 0.75rem 1rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-weight: 700; color: var(--text-primary);">Emergency Dispatch:</span>
                    <strong style="color: var(--color-emergency); font-size: 1rem;">999</strong>
                </div>
                <div style="background-color: var(--bg-main); padding: 0.75rem 1rem; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-weight: 700; color: var(--text-primary);">Supervisor Office:</span>
                    <strong style="color: var(--primary-color); font-size: 1rem;">+880 1700 000001</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function triggerPanicAlert() {
        const form = document.getElementById('panic-form');
        const alertState = document.getElementById('panic-alert-state');
        
        if (form && alertState) {
            form.style.display = 'none';
            alertState.style.display = 'block';
        }
    }

    function cancelPanicAlert() {
        const form = document.getElementById('panic-form');
        const alertState = document.getElementById('panic-alert-state');
        
        if (form && alertState) {
            alertState.style.display = 'none';
            form.style.display = 'block';
            alert('Alarm deactivated successfully.');
        }
    }
</script>
@endsection
