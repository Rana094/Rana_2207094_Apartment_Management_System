@extends('layouts.public')

@section('title', 'Log In — Nestora Portal')

@section('content')
<style>
    .auth-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        background-color: var(--bg-main);
    }
    .auth-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 480px;
        padding: 2.5rem;
        box-shadow: var(--shadow-lg);
    }
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-header h1 {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }
    
    /* Role Selector Tabs */
    .role-tabs {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background-color: #f1f5f9;
        border-radius: var(--radius-md);
        padding: 0.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
    }
    .role-tab-btn {
        background: none;
        border: none;
        padding: 0.5rem 0.25rem;
        font-family: inherit;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-secondary);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition-fast);
        text-align: center;
    }
    .role-tab-btn.active {
        background-color: #ffffff;
        color: var(--primary-color);
        box-shadow: var(--shadow-sm);
    }
    
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }
    .remember-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        cursor: pointer;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Enter your credentials to access your Nestora dashboard.</p>
        </div>
        
        <!-- Role-Aware Tabs -->
        <div class="role-tabs" role="tablist">
            <button type="button" class="role-tab-btn active" data-role="resident" aria-selected="true" role="tab">
                Resident
            </button>
            <button type="button" class="role-tab-btn" data-role="manager" aria-selected="false" role="tab">
                Manager
            </button>
            <button type="button" class="role-tab-btn" data-role="security" aria-selected="false" role="tab">
                Security
            </button>
            <button type="button" class="role-tab-btn" data-role="staff" aria-selected="false" role="tab">
                Staff
            </button>
        </div>
        
        @if ($errors->any())
            <div style="background: var(--bg-rejected); color: var(--color-rejected); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div style="background: var(--bg-approved); color: var(--color-approved); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" id="login-form">
            @csrf
            <!-- Hidden Role Input to pass role selection to backend -->
            <input type="hidden" name="role" id="selected-role" value="{{ old('role', 'resident') }}">
            
            <div class="form-group">
                <label for="login-email" class="form-label">Email Address</label>
                <input type="email" id="login-email" name="email" class="form-control" placeholder="e.g. resident@nestora.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>
            
            <div class="form-group">
                <label for="login-password" class="form-label">Password</label>
                <input type="password" id="login-password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
            </div>
            
            <div class="form-options">
                <label class="remember-label">
                    <input type="checkbox" name="remember" class="form-checkbox">
                    Remember me
                </label>
                <a href="#" style="font-weight: 500; font-size: 0.85rem;">Forgot Password?</a>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 1.5rem;">
                Sign In as <span id="btn-role-text" style="text-transform: capitalize; margin-left: 0.25rem;">Resident</span>
            </button>
            
            <div style="text-align: center; font-size: 0.9rem; color: var(--text-secondary);">
                Don't have a resident account? <a href="{{ url('/register') }}" style="font-weight: 600;">Sign Up Here</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.role-tab-btn');
        const roleInput = document.getElementById('selected-role');
        const btnRoleText = document.getElementById('btn-role-text');
        const emailInput = document.getElementById('login-email');
        
        // Placeholder helper to show role specific email placeholder
        const rolePlaceholders = {
            resident: 'resident@nestora.com',
            manager: 'manager@nestora.com',
            security: 'security@nestora.com',
            staff: 'staff@nestora.com'
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active classes
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                
                // Add active to clicked tab
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                
                const selectedRole = this.getAttribute('data-role');
                roleInput.value = selectedRole;
                btnRoleText.textContent = selectedRole;
                
                // Update email placeholder for easier demonstration
                emailInput.placeholder = 'e.g. ' + rolePlaceholders[selectedRole];
            });
        });
    });
</script>
@endsection
