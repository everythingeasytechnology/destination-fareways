@extends('layouts.admin')

@section('title', 'Airports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Airports</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Airports</h2>
        <p class="text-muted mb-0">Manage airport master data including IATA codes and city mappings.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.airports.sample') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-file-arrow-down me-2"></i>Sample Excel
        </a>
        <a href="{{ route('admin.airports.import.form') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fa-solid fa-file-import me-2"></i>Import Airports
        </a>
        <a href="{{ route('admin.airports.create') }}" class="btn btn-action rounded-pill px-4">
            <i class="fa-solid fa-plus me-2"></i>Add Airport
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card premium-card border-0 shadow-sm p-4">
    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.airports.index') }}" class="row g-2 mb-4">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by IATA, city, airport name, state..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        @if(request('search') || request('status'))
        <div class="col-auto">
            <a href="{{ route('admin.airports.index') }}" class="btn btn-outline-secondary">Clear</a>
        </div>
        @endif
    </form>

    <div class="table-responsive">
        <table id="airports-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>State Code</th>
                    <th>IATA Code</th>
                    <th>City</th>
                    <th>Airport Name</th>
                    <th>Status</th>
                    <th class="text-end" style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($airports as $airport)
                <tr>
                    <td class="text-muted small">{{ $airport->id }}</td>
                    <td>
                        @if($airport->state_code)
                            <span class="badge bg-light text-navy border font-monospace fw-bold">{{ $airport->state_code }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary font-monospace fw-bold px-2 py-1" style="font-size:0.85rem;">{{ $airport->iata_code }}</span>
                    </td>
                    <td class="fw-semibold text-navy">{{ $airport->city }}</td>
                    <td class="text-muted">{{ $airport->airport_name }}</td>
                    <td>
                        @if($airport->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <a href="{{ route('admin.airports.edit', $airport->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.airports.destroy', $airport->id) }}" method="POST" class="d-inline delete-confirm-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fa-solid fa-plane-slash fa-2x mb-3 opacity-25"></i>
                        <p class="mb-0">No airports found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($airports->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <p class="text-muted small mb-0">Showing {{ $airports->firstItem() }}–{{ $airports->lastItem() }} of {{ $airports->total() }} airports</p>
        {{ $airports->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('#airports-table').DataTable({
        paging:     false,
        info:       false,
        searching:  false,
        ordering:   true,
        order:      [[4, 'asc']],
        responsive: true,
    });

    $('.delete-confirm-form').on('submit', function (e) {
        e.preventDefault();
        if (confirm('Delete this airport? This action cannot be undone.')) {
            this.submit();
        }
    });
});
</script>
@endsection
