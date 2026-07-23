@extends('layouts.dashboard')

@section('title', 'Visitor Check-Out - Nestora')

@section('content')
<div class="db-header">
    <a href="{{ route('security.dashboard') }}" style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">
        Back to Gate Terminal
    </a>
    <h1 class="db-title">Visitor Check-Out Exit Registry</h1>
    <p class="db-subtitle">Register visitor exits and release their entry gate pass codes.</p>
</div>

<div class="panic-container" style="max-width: 720px; margin: 0 auto;">
    <div class="card">
        <h3 style="margin-bottom: 1.25rem; font-size: 1.15rem; text-align: center;">Find Checked-In Visitor</h3>

        {{-- GET lookup loads an active visitor by passcode before allowing the checkout POST. --}}
        <form action="{{ route('security.checkout') }}" method="GET" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="text" name="passcode" class="form-control" placeholder="Enter visitor passcode" value="{{ request('passcode') }}" required style="font-weight: 700; text-transform: uppercase; text-align: center;">
            <button type="submit" class="btn btn-primary">Lookup</button>
        </form>

        @if ($visitor && $visitor->checked_in_at && ! $visitor->checked_out_at)
            <div style="background-color: var(--bg-main); border: 1px solid var(--border-color); padding: 1.5rem; border-radius: var(--radius-md); text-align: left;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <div>
                        <strong style="font-size: 1.15rem; color: var(--text-primary);">{{ $visitor->visitor_name }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ $visitor->purpose ?? 'Visitor' }}</div>
                    </div>
                    <span class="badge badge-unpaid" style="background-color: var(--secondary-light); color: var(--secondary-color); font-size: 0.8rem;">Currently Inside</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.85rem; color: var(--text-secondary);">
                    <div>Destination Unit: <strong style="color: var(--text-primary);">{{ $visitor->flat?->flat_number ?? '-' }}</strong></div>
                    <div>Check-In Timestamp: <strong style="color: var(--text-primary);">{{ $visitor->checked_in_at?->format('M d, Y H:i') }}</strong></div>
                    <div>Phone Number: <strong style="color: var(--text-primary);">{{ $visitor->visitor_phone ?? '-' }}</strong></div>
                </div>

                {{-- Checkout updates the visitor request and adds a check_out visitor_logs row. --}}
                <form action="{{ route('security.checkout.store') }}" method="POST" style="margin-top: 1.5rem;">
                    @csrf
                    <input type="hidden" name="passcode" value="{{ $visitor->access_code }}">
                    <button type="submit" class="btn btn-danger" style="width: 100%; justify-content: center; font-weight: 700; background-color: var(--color-emergency); border-color: var(--color-emergency);">
                        Approve Check-Out & Register Exit
                    </button>
                </form>
            </div>
        @elseif ($visitor && $visitor->checked_out_at)
            <div class="alert alert-success" style="margin-bottom: 0; text-align: center;">
                Visitor already checked out at {{ $visitor->checked_out_at?->format('M d, Y H:i') }}.
            </div>
        @elseif(request('passcode'))
            <div class="alert alert-danger" style="margin-bottom: 0; text-align: center;">
                No active check-in record found for this passcode.
            </div>
        @else
            <p style="font-size: 0.85rem; color: var(--text-secondary); text-align: center; margin: 2rem 0;">
                Input a visitor passcode to load their active entry file.
            </p>
        @endif

        @if ($insideVisitors->isNotEmpty())
            <div style="margin-top: 2rem;">
                <h4 style="margin-bottom: .75rem;">Currently Inside</h4>
                <div style="display:flex; flex-direction:column; gap:.5rem;">
                    {{-- This quick list is built from VisitorRequest rows that have checked_in_at but no checked_out_at. --}}
                    @foreach ($insideVisitors as $insideVisitor)
                        <a href="{{ route('security.checkout', ['passcode' => $insideVisitor->access_code]) }}" class="card-static" style="text-decoration:none; color:inherit;">
                            <strong>{{ $insideVisitor->visitor_name }}</strong>
                            <span style="color: var(--text-muted); font-size:.8rem;">{{ $insideVisitor->access_code }} - {{ $insideVisitor->flat?->flat_number ?? '-' }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
