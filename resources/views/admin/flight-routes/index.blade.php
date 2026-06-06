@extends('layouts.admin')

@section('title', 'Flight Routes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Flight Routes</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Flight Routes</h2>
        <p class="text-muted mb-0">Manage route pages for origin-to-destination flight corridors with SEO-optimized content.</p>
    </div>
    <a href="{{ route('admin.flight-routes.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Add New Route
    </a>
</div>

<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="routes-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Route</th>
                    <th>Airports</th>
                    <th>Price From</th>
                    <th>Duration</th>
                    <th>Type</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($routes as $route)
                <tr>
                    <td>
                        @if($route->featured_image)
                            <img src="{{ asset('storage/' . $route->featured_image) }}" alt="Route" class="rounded object-fit-cover shadow-sm" width="55" height="40">
                        @else
                            <div class="rounded bg-light text-muted d-flex align-items-center justify-content-center border" style="width:55px;height:40px;font-size:0.7rem;">
                                <i class="fa-solid fa-plane text-navy"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-navy">{{ $route->origin_city }} <i class="fa-solid fa-arrow-right mx-1 text-gold" style="font-size:0.75rem;"></i> {{ $route->destination_city }}</div>
                        <div class="small text-muted font-monospace" style="font-size:0.72rem;">/flight-routes/{{ $route->slug }}</div>
                    </td>
                    <td class="small text-muted">
                        @if($route->origin_airport_code || $route->destination_airport_code)
                            <span class="font-monospace fw-semibold text-navy">{{ $route->origin_airport_code ?: '—' }}</span>
                            <i class="fa-solid fa-arrow-right mx-1" style="font-size:0.65rem;"></i>
                            <span class="font-monospace fw-semibold text-navy">{{ $route->destination_airport_code ?: '—' }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="fw-bold font-monospace text-navy">
                        @if($route->starting_price > 0)
                            <span class="text-gold">${{ number_format($route->starting_price, 0) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $route->flight_duration ?: '—' }}</td>
                    <td>
                        @if($route->is_domestic)
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1" style="font-size:0.7rem;"><i class="fa-solid fa-flag-usa me-1"></i>Domestic</span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1" style="font-size:0.7rem;"><i class="fa-solid fa-earth-americas me-1"></i>International</span>
                        @endif
                    </td>
                    <td>
                        @if($route->is_featured)
                            <span class="badge bg-warning text-navy rounded-pill px-2 py-1"><i class="fa-solid fa-star me-1"></i>Featured</span>
                        @else
                            <span class="text-muted small">Standard</span>
                        @endif
                    </td>
                    <td>
                        @if($route->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <a href="{{ route('admin.flight-routes.edit', $route->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit Route">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.flight-routes.destroy', $route->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Route">
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
        $('#routes-table').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 25,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search flight routes..."
            }
        });

        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            if (confirm("Delete this flight route? It will be soft-deleted.")) {
                this.submit();
            }
        });
    });
</script>
@endsection
