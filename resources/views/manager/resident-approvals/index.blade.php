@extends('layouts.public')

@section('title', 'Resident Approvals - Nestora')

@section('content')
<div style="min-height: 70vh; padding: 4rem 1.5rem; background: var(--bg-main);">
    <div class="container">
        <h1 style="font-size: 2rem; margin-bottom: 1rem;">Resident Approvals</h1>

        @if (session('status'))
            <div style="background: var(--bg-approved); color: var(--color-approved); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem;">
                {{ session('status') }}
            </div>
        @endif

        <div style="background: #fff; border: 1px solid var(--border-color); border-radius: var(--radius-lg); overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 1rem;">Resident</th>
                        <th style="padding: 1rem;">Flat</th>
                        <th style="padding: 1rem;">Type</th>
                        <th style="padding: 1rem;">Status</th>
                        <th style="padding: 1rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($residents as $resident)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem;">
                                <strong>{{ $resident->name }}</strong><br>
                                <span style="color: var(--text-secondary);">{{ $resident->email }}</span>
                            </td>
                            <td style="padding: 1rem;">{{ $resident->flat_info }}</td>
                            <td style="padding: 1rem;">{{ ucfirst((string) $resident->resident_type) }}</td>
                            <td style="padding: 1rem;">{{ str_replace('_', ' ', ucfirst($resident->status)) }}</td>
                            <td style="padding: 1rem;">
                                <form method="POST" action="{{ route('manager.resident-approvals.approve', $resident) }}" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('manager.resident-approvals.reject', $resident) }}" style="display: inline-block; margin-left: .5rem;">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-sm">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 1.5rem; color: var(--text-secondary);">No resident approvals are pending.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $residents->links() }}
        </div>
    </div>
</div>
@endsection
