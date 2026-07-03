@extends('layouts.public')

@section('title', 'Registration Pending Approval — Nestora')

@section('content')
<style>
    .status-wrapper {
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        background-color: var(--bg-main);
    }
    .status-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 540px;
        padding: 3rem 2.5rem;
        text-align: center;
        box-shadow: var(--shadow-lg);
    }
    .status-icon-wrapper {
        width: 4.5rem;
        height: 4.5rem;
        border-radius: var(--radius-full);
        background-color: var(--bg-pending-verify);
        color: var(--color-pending-verify);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem auto;
        animation: pulse-border 2s infinite;
    }
    .status-icon-wrapper svg {
        width: 2.25rem;
        height: 2.25rem;
    }
    
    @keyframes pulse-border {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.2);
        }
        50% {
            box-shadow: 0 0 0 12px rgba(79, 70, 229, 0);
        }
    }
    
    .status-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .status-desc {
        font-size: 0.95rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    /* Steps Progress visual list */
    .steps-list {
        text-align: left;
        background-color: var(--bg-main);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        margin-bottom: 2rem;
        list-style: none;
    }
    .step-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .step-item:last-child {
        margin-bottom: 0;
    }
    .step-badge {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: var(--radius-full);
        background-color: var(--border-hover);
        color: var(--text-secondary);
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .step-item.completed .step-badge {
        background-color: var(--bg-approved);
        color: var(--color-approved);
    }
    .step-item.active .step-badge {
        background-color: var(--primary-color);
        color: #ffffff;
    }
    .step-title {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.15rem;
        color: var(--text-primary);
    }
    .step-desc {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
</style>

<div class="status-wrapper">
    <div class="status-card">
        <!-- Progress Icon -->
        <div class="status-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        
        <h1 class="status-title">Account Registration Submitted</h1>
        <p class="status-desc">
            Your account is waiting for email verification and manager approval. Once verified and approved by the building management, your portal access will be unlocked.
        </p>
        
        <!-- Registration Progress steps -->
        <ul class="steps-list">
            <!-- Step 1: Signup Form -->
            <li class="step-item completed">
                <span class="step-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" style="width: 0.875rem; height: 0.875rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </span>
                <div>
                    <div class="step-title">Form Submitted</div>
                    <div class="step-desc">Your flat request has been logged successfully.</div>
                </div>
            </li>
            
            <!-- Step 2: Email Verification -->
            <li class="step-item active">
                <span class="step-badge">2</span>
                <div>
                    <div class="step-title">Email Verification Pending</div>
                    <div class="step-desc">Please click the verification link sent to your email address.</div>
                </div>
            </li>
            
            <!-- Step 3: Manager Approval -->
            <li class="step-item">
                <span class="step-badge">3</span>
                <div>
                    <div class="step-title">Manager Approval Queue</div>
                    <div class="step-desc">The management team will verify your flat alignment documents.</div>
                </div>
            </li>
        </ul>
        
        <!-- Interactive Mock Actions -->
        <div class="flex flex-col gap-3">
            <a href="#" class="btn btn-primary" style="justify-content: center;" onclick="alert('Verification email has been resent to your inbox!'); return false;">
                Resend Verification Email
            </a>
            
            <div style="display: flex; gap: 1rem; width: 100%;">
                <a href="{{ url('/') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">
                    Back to Home
                </a>
                <a href="{{ url('/login') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">
                    Return to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
