@extends('layouts.dashboard')

@section('title', 'Visitor Passes - Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Visitor Passes</h1>
        <p class="db-subtitle">Pre-approve guests, delivery riders, and home service providers to simplify entrance gate checks.</p>
    </div>

    <a href="{{ route('resident.visitors.create') }}" class="btn btn-primary">Create Visitor Pass</a>
</div>

<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Pass Code</th>
                <th>Visitor Name</th>
                <th>Purpose</th>
                <th>Phone Number</th>
                <th>Scheduled Date</th>
                <th>Check-in/out</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($visitors as $visitor)
                <tr>
                    <td><code style="background-color: var(--primary-light); color: var(--primary-color); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-weight: 700; font-size: 0.85rem;">{{ $visitor->access_code }}</code></td>
                    <td style="font-weight: 600;">{{ $visitor->visitor_name }}</td>
                    <td>{{ $visitor->purpose ?? '-' }}</td>
                    <td>{{ $visitor->visitor_phone ?? '-' }}</td>
                    <td>{{ $visitor->visit_date?->format('M d, Y') }}</td>
                    <td>
                        @if ($visitor->checked_in_at || $visitor->checked_out_at)
                            <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600;">
                                In: {{ $visitor->checked_in_at?->format('H:i') ?? '-' }}<br>
                                Out: {{ $visitor->checked_out_at?->format('H:i') ?? '-' }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ in_array($visitor->status, ['checked_out', 'cancelled'], true) ? 'completed' : 'pending-verification' }}">{{ str_replace('_', ' ', $visitor->status) }}</span></td>
                    <td style="text-align: right;">
                        @if (! $visitor->checked_in_at && ! in_array($visitor->status, ['cancelled', 'checked_out'], true))
                            <form id="cancel-visitor-{{ $visitor->id }}" method="POST" action="{{ route('resident.visitors.cancel', $visitor) }}">
                                @csrf
                                <button type="button" class="btn btn-outline btn-sm" onclick="showConfirmModal('Cancel Pass?', 'Cancel this visitor pass code?', function(){ document.getElementById('cancel-visitor-{{ $visitor->id }}').submit(); }, true)">Cancel Pass</button>
                            </form>
                        @else
                            <span class="text-muted text-xs">Closed</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:2rem; color:var(--text-muted);">No visitor passes created yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($visitors->hasPages())
        <div class="table-pagination" style="margin-top: 1rem;">{{ $visitors->links() }}</div>
    @endif
</div>
@endsection
