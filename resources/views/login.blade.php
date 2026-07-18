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
    .password-field {
        position: relative;
    }
    .password-field .form-control {
        padding-right: 4.25rem;
    }
    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: 0;
        color: var(--primary-color);
        cursor: pointer;
        font-family: inherit;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.25rem;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Enter your credentials to access your Nestora dashboard.</p>
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

            <div class="form-group">
                <label for="login-email" class="form-label">Email Address</label>
                <input type="email" id="login-email" name="email" class="form-control" placeholder="e.g. ullas@gmail.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>
            
            <div class="form-group">
                <label for="login-password" class="form-label">Password</label>
                <div class="password-field">
                    <input type="password" id="login-password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" class="password-toggle" id="password-toggle" aria-label="Show password" aria-pressed="false">Show</button>
                </div>
            </div>
            
            <div class="form-options">
                <label class="remember-label">
                    <input type="checkbox" name="remember" class="form-checkbox">
                    Remember me
                </label>
                <a href="{{ route('contact') }}" style="font-weight: 500; font-size: 0.85rem;">Forgot Password?</a>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 1.5rem;">
                Sign In
            </button>
            
            <div style="text-align: center; font-size: 0.9rem; color: var(--text-secondary);">
                Don't have a resident account? <a href="{{ url('/register') }}" style="font-weight: 600;">Sign Up Here</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('login-password');
        const toggle = document.getElementById('password-toggle');

        if (!passwordInput || !toggle) {
            return;
        }

        toggle.addEventListener('click', function() {
            const isVisible = passwordInput.type === 'text';

            passwordInput.type = isVisible ? 'password' : 'text';
            toggle.textContent = isVisible ? 'Show' : 'Hide';
            toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            toggle.setAttribute('aria-pressed', isVisible ? 'false' : 'true');
        });
    });
</script>
@endsection
