@extends('layouts.public')

@section('title', 'Forgot Password - Nestora')

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
</style>

<section class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Forgot Password</h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Enter your account email and we will send a secure password reset link.</p>
        </div>

        @if (session('status'))
            <div style="background: var(--bg-approved); color: var(--color-approved); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background: var(--bg-rejected); color: var(--color-rejected); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. ullas@nestora.com" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-bottom: 1rem;">
                Send Reset Link
            </button>

            <div style="text-align:center; font-size:.9rem;">
                <a href="{{ route('login') }}" style="font-weight:600;">Back to Login</a>
            </div>
        </form>
    </div>
</section>
@endsection
