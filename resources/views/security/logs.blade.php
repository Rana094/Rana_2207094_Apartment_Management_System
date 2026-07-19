@extends('layouts.dashboard')

@section('title', 'Visitor Logs Registry - Nestora')

@section('content')
<div class="db-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 class="db-title">Visitor Logs Directory</h1>
        <p class="db-subtitle">Complete historical log of all visitor entries and exits.</p>
    </div>

    <a href="{{ route('security.checkin') }}" class="btn btn-primary">Register Walk-In Visitor</a>
</div>

<div class="table-responsive">
    <table class="db-table">
        <thead>
            <tr>
                <th>Visitor</th>
                <th>Destination Unit</th>
                <th>Category</th>
                <th>Pass Code</th>
                <th>Timestamp</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            {{-- SecurityPortalController@logs paginates real VisitorLog records, not static sample rows. --}}
            @forelse ($logs as $log)
                @php($visitor = $log->visitorRequest)
                @php($isCheckIn = $log->event_type === 'check_in')
                @php($isInside = $visitor && $visitor->checked_in_at && ! $visitor->checked_out_at)
                <tr>
                    <td style="font-weight: 600;">
                        {{ $log->visitor_name }}
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ $log->visitor_phone ?? '-' }}</div>
                    </td>
                    <td style="font-weight: 600;">
                        {{ $log->flat?->building?->name ? $log->flat->building->name.' - ' : '' }}{{ $log->flat?->flat_number ? 'Flat '.$log->flat->flat_number : 'Unassigned' }}
                        @if ($visitor?->resident)
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ $visitor->resident->name }}</div>
                        @endif
                    </td>
                    <td>{{ ucfirst(strtok((string) $log->purpose, ':') ?: 'visitor') }}</td>
                    <td>
                        @if ($log->access_code)
                            <code style="background-color: var(--bg-main); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm); font-weight: 700;">{{ $log->access_code }}</code>
                        @else
                            <span class="text-muted text-xs">No code</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600;">
                            {{ $isCheckIn ? 'In' : 'Out' }}: {{ $log->occurred_at?->format('M d, Y h:i A') ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @if ($isInside)
                            <span class="badge badge-unpaid" style="background-color: var(--secondary-light); color: var(--secondary-color);">inside</span>
                        @elseif ($isCheckIn)
                            <span class="badge badge-approved">checked in</span>
                        @else
                            <span class="badge badge-completed">checked out</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        {{-- Active visitors can be checked out directly from the log row using the same passcode lookup route. --}}
                        @if ($isInside && $log->access_code)
                            <a href="{{ route('security.checkout', ['passcode' => $log->access_code]) }}" class="btn btn-outline btn-sm">Check-Out</a>
                        @else
                            <span class="text-muted text-xs">Closed</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        No visitor logs found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="table-pagination">
        <div class="pagination-info">
            Showing <strong>{{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }}</strong>
            records of <strong>{{ $logs->total() }}</strong> total visitor entries
        </div>
        <div class="pagination-btns">
            @if ($logs->previousPageUrl())
                <a href="{{ $logs->previousPageUrl() }}" class="btn btn-outline btn-sm">Previous</a>
            @endif
            @if ($logs->nextPageUrl())
                <a href="{{ $logs->nextPageUrl() }}" class="btn btn-outline btn-sm">Next</a>
            @endif
        </div>
    </div>
</div>
@endsection
