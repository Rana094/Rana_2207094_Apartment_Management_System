@extends('layouts.dashboard')

@section('title', 'Repair History - Nestora')

@section('content')
<div class="db-header">
    <h1 class="db-title">Repair History Archive</h1>
    <p class="db-subtitle">Review historical logs of resolved maintenance tickets and repair jobs completed by you.</p>
</div>

<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Destination Unit</th>
                <th>Subject</th>
                <th>Completion Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($completedOrders as $order)
                <tr>
                    <td style="font-weight: 700;">#T-{{ $order->id }}</td>
                    <td style="font-weight: 700;">
                        {{ $order->complaint?->flat?->flat_number ?? 'No flat' }}
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ $order->complaint?->flat?->building?->name ?? 'No building' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 700;">{{ $order->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $order->complaint?->category ?? 'Maintenance' }}</div>
                    </td>
                    <td>{{ $order->completed_at?->format('M d, Y') ?? '-' }}</td>
                    <td><span class="badge badge-pending" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">{{ $order->priority }}</span></td>
                    <td><span class="badge badge-resolved">resolved</span></td>
                    <td style="text-align: right;">
                        <a href="{{ route('maintenance.show', $order) }}" class="btn btn-outline btn-sm">View Record</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td style="font-weight: 700;">#T-1804</td>
                    <td style="font-weight: 700;">
                        Demo Unit
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">Open an assigned work order to create real history.</div>
                    </td>
                    <td>
                        <div style="font-weight: 700;">Sample completed repair</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Maintenance</div>
                    </td>
                    <td>-</td>
                    <td><span class="badge badge-pending" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">normal</span></td>
                    <td><span class="badge badge-resolved">resolved</span></td>
                    <td style="text-align: right;">
                        <a href="{{ route('maintenance.dashboard') }}" class="btn btn-outline btn-sm">View Record</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($completedOrders->hasPages())
        <div class="table-pagination" style="margin-top: 1rem;">{{ $completedOrders->links() }}</div>
    @endif
</div>
@endsection
