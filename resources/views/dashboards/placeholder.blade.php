@extends('layouts.public')

@section('title', $title . ' - Nestora')

@section('content')
<div style="min-height: 70vh; padding: 4rem 1.5rem; background: var(--bg-main);">
    <div class="container" style="max-width: 760px;">
        <h1 style="font-size: 2rem; margin-bottom: 1rem;">{{ $title }}</h1>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">{{ $message }}</p>
        <div style="background: #fff; border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem;">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            <p><strong>Status:</strong> {{ str_replace('_', ' ', ucfirst($user->status)) }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline">Log Out</button>
        </form>
    </div>
</div>
@endsection
