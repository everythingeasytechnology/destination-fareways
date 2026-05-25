@extends('layouts.admin')

@section('title', 'Destinations Manager')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Destinations</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Destinations Manager</h2>
        <p class="text-muted mb-0">Create and organize travel locations, starting prices, best time to visit, and galleries.</p>
    </div>
    
    <a href="{{ route('admin.destinations.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Add Destination
    </a>
</div>

<!-- Destinations Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="destinations-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 60px;">Sort</th>
                    <th>Image</th>
                    <th>Destination Name</th>
                    <th>Region / Airport</th>
                    <th>Pricing</th>
                    <th>Attributes</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($destinations as $destination)
                <tr>
                    <td class="font-monospace small text-muted text-center fw-bold">
                        #{{ $destination->sort_order }}
                    </td>
                    <td>
                        @if($destination->featured_image)
                            <img src="{{ asset('storage/' . $destination->featured_image) }}" alt="Featured" class="rounded object-fit-cover shadow-sm" width="55" height="40">
                        @else
                            <div class="rounded bg-light text-muted d-flex align-items-center justify-content-center border" style="width: 55px; height: 40px; font-size: 0.75rem;">No Img</div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-navy">{{ $destination->name }}</div>
                        <div class="small text-muted font-monospace" style="font-size: 0.75rem;">/destinations/{{ $destination->slug }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-navy small">{{ $destination->country }}</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">
                            @if($destination->state)
                                <span>{{ $destination->state }}, </span>
                            @endif
                            @if($destination->airport_code)
                                <span class="badge bg-light text-navy border font-monospace" style="font-size: 0.65rem;">{{ $destination->airport_code }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($destination->starting_price)
                            <div class="fw-bold text-success">${{ number_format($destination->starting_price, 2) }}</div>
                            <div class="text-muted small" style="font-size: 0.7rem;">starting at</div>
                        @else
                            <span class="text-muted small">Call for Price</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <div>
                                @if($destination->is_domestic)
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-0.5" style="font-size: 0.65rem;"><i class="fa-solid fa-house-chimney me-1"></i>Domestic</span>
                                @else
                                    <span class="badge bg-royal bg-opacity-10 text-royal px-2 py-0.5" style="font-size: 0.65rem;"><i class="fa-solid fa-plane-departure me-1"></i>International</span>
                                @endif
                            </div>
                            <div>
                                @if($destination->is_popular)
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-0.5" style="font-size: 0.65rem;"><i class="fa-solid fa-fire me-1"></i>Popular</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($destination->is_featured)
                            <span class="badge bg-warning text-navy rounded-pill px-2 py-1"><i class="fa-solid fa-star me-1"></i>Featured</span>
                        @else
                            <span class="text-muted small">Standard</span>
                        @endif
                    </td>
                    <td>
                        @if($destination->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.destinations.edit', $destination->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit Destination">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.destinations.destroy', $destination->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Destination">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        var table = $('#destinations-table').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search destinations..."
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this destination? It will be soft deleted.")) {
                form.submit();
            }
        });
    });
</script>
@endsection
