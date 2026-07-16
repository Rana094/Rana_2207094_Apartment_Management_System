@extends('layouts.dashboard')

@section('title', 'Assign Work Order - Nestora')

@section('content')
<div class="db-header"><a href="{{ route('manager.complaints.index') }}" style="font-size:.85rem;font-weight:600;">Back to Complaints</a><h1 class="db-title">Assign Repair Technician</h1></div>
<div style="max-width:680px;margin:0 auto;"><div class="card">
    @if($errors->any())<div class="alert alert-danger" style="margin-bottom:1rem;">{{ $errors->first() }}</div>@endif
    <form action="{{ route('manager.complaints.work-orders.store', $complaint) }}" method="POST">
        @csrf
        <div class="form-group"><label class="form-label">Issue</label><input class="form-control" value="{{ $complaint->title }} (#{{ $complaint->id }}) - {{ $complaint->flat?->flat_number ?? 'No flat' }}" readonly></div>
        <div class="form-group"><label for="assign-staff" class="form-label">Technician</label><select id="assign-staff" name="technician_id" class="form-control form-select" required><option value="">Select staff</option>@foreach($staff as $member)<option value="{{ $member->id }}" @selected(old('technician_id')==$member->id)>{{ $member->name }} - {{ $member->staffProfile?->staff_type ?? 'Maintenance' }}</option>@endforeach</select></div>
        <div class="grid grid-2">
            <div class="form-group"><label for="urgency" class="form-label">Urgency</label><select id="urgency" name="urgency" class="form-control form-select" required>@foreach(['low','medium','high','urgent','emergency'] as $value)<option value="{{ $value }}" @selected(old('urgency',$complaint->priority)===$value)>{{ ucfirst($value) }}</option>@endforeach</select></div>
            <div class="form-group"><label for="deadline" class="form-label">Deadline</label><input type="date" id="deadline" name="deadline" class="form-control" value="{{ old('deadline',date('Y-m-d',strtotime('+1 day'))) }}"></div>
        </div>
        <div class="form-group"><label for="instructions" class="form-label">Instructions</label><textarea id="instructions" name="instructions" class="form-control" rows="4">{{ old('instructions') }}</textarea></div>
        <div style="display:flex;gap:1rem;"><a href="{{ route('manager.complaints.index') }}" class="btn btn-outline" style="flex:1;justify-content:center;">Cancel</a><button type="submit" class="btn btn-primary" style="flex:2;justify-content:center;">Dispatch Technician</button></div>
    </form>
</div></div>
@endsection
