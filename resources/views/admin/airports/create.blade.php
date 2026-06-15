@extends('layouts.admin')

@section('title', 'Add Airport')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.airports.index') }}" class="text-decoration-none text-muted">Airports</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Airport</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Add Airport</h2>
    <p class="text-muted mb-0">Add a new airport entry to the master database.</p>
</div>

<form action="{{ route('admin.airports.store') }}" method="POST">
    @csrf

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-plane-arrival text-warning me-2"></i>Airport Details
                </h5>

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="iata_code" class="form-label fw-bold">IATA Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control px-3 font-monospace text-uppercase @error('iata_code') is-invalid @enderror"
                               id="iata_code" name="iata_code" value="{{ old('iata_code') }}"
                               placeholder="e.g. ATL" maxlength="10" required
                               style="letter-spacing:2px; font-size:1.1rem;">
                        @error('iata_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">3-letter airport identifier (e.g. ATL, LAX, JFK)</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="state_code" class="form-label fw-bold">State Code</label>
                        <input type="text" class="form-control px-3 font-monospace text-uppercase @error('state_code') is-invalid @enderror"
                               id="state_code" name="state_code" value="{{ old('state_code') }}"
                               placeholder="e.g. GA" maxlength="10">
                        @error('state_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="city" class="form-label fw-bold">City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control px-3 @error('city') is-invalid @enderror"
                               id="city" name="city" value="{{ old('city') }}"
                               placeholder="e.g. Atlanta" maxlength="100" required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="airport_name" class="form-label fw-bold">Airport Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control px-3 @error('airport_name') is-invalid @enderror"
                               id="airport_name" name="airport_name" value="{{ old('airport_name') }}"
                               placeholder="e.g. Hartsfield/Jackson Atlanta International" maxlength="255" required>
                        @error('airport_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-circle-check text-warning me-2"></i>Publish Settings
                </h5>

                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select class="form-select px-3 @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-3 mt-4 text-end">
        <a href="{{ route('admin.airports.index') }}" class="btn btn-outline-secondary px-4 rounded-pill me-2">Cancel</a>
        <button type="submit" class="btn btn-action px-5">
            <i class="fa-solid fa-floppy-disk me-2"></i>Save Airport
        </button>
    </div>
</form>
@endsection
