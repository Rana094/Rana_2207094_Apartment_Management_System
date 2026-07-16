@extends('layouts.dashboard')

@php($isEdit = isset($flat) && $flat instanceof \App\Models\Flat)

@section('title', $isEdit ? 'Edit Unit - Nestora' : 'Register Unit - Nestora')

@section('content')
<div class="db-header">
    <a href="{{ route('manager.flats.index') }}" style="font-size: 0.85rem; font-weight: 600;">Back to Unit Registry</a>
    <h1 class="db-title">{{ $isEdit ? 'Modify Flat Details' : 'Register New Flat Unit' }}</h1>
</div>

<div style="max-width: 720px; margin: 0 auto;">
    <div class="card">
        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1rem;">{{ $errors->first() }}</div>
        @endif

        <form action="{{ $isEdit ? route('manager.flats.update', $flat) : route('manager.flats.store') }}" method="POST">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="form-group">
                <label for="building-id" class="form-label">Building</label>
                <select id="building-id" name="building_id" class="form-control form-select" required>
                    @foreach ($buildings as $building)
                        <option value="{{ $building->id }}" @selected((string) old('building_id', $isEdit ? $flat->building_id : '') === (string) $building->id)>{{ $building->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="flat-no" class="form-label">Flat / Unit Number</label>
                    <input type="text" id="flat-no" name="number" class="form-control" value="{{ old('number', $isEdit ? $flat->flat_number : '') }}" required>
                </div>
                <div class="form-group">
                    <label for="flat-block" class="form-label">Block / Tower</label>
                    <input type="text" id="flat-block" name="block" class="form-control" value="{{ old('block', $isEdit ? $flat->block : '') }}">
                </div>
            </div>

            <div class="grid grid-3">
                <div class="form-group">
                    <label for="flat-floor" class="form-label">Floor</label>
                    <input type="number" id="flat-floor" name="floor" class="form-control" min="0" value="{{ old('floor', $isEdit ? $flat->floor : '') }}">
                </div>
                <div class="form-group">
                    <label for="flat-size" class="form-label">Square Footage</label>
                    <input type="number" id="flat-size" name="size" class="form-control" min="0" step="0.01" value="{{ old('size', $isEdit ? $flat->area_sqft : '') }}">
                </div>
                <div class="form-group">
                    <label for="flat-beds" class="form-label">Bedrooms</label>
                    <input type="number" id="flat-beds" name="beds" class="form-control" min="0" value="{{ old('beds', $isEdit ? $flat->bedrooms : 0) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="flat-occupancy" class="form-label">Occupancy</label>
                <select id="flat-occupancy" name="occupancy" class="form-control form-select">
                    @foreach (['vacant' => 'Vacant', 'owner' => 'Occupied by Owner', 'tenant' => 'Occupied by Tenant'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('occupancy', $isEdit ? ($flat->status === 'vacant' ? 'vacant' : ($flat->type ?? 'owner')) : 'vacant') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('manager.flats.index') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">{{ $isEdit ? 'Update Unit' : 'Register Unit' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
