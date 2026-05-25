@extends('layouts.admin')

@section('title', 'Testimonials Manager')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Customer Testimonials</h2>
        <p class="text-muted mb-0">Manage customer reviews, highlight specific flight route experiences, configure visual ratings, and feature testimonials.</p>
    </div>
    
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Add Testimonial
    </a>
</div>

<!-- Testimonials Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="testimonials-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 60px;">Sort</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Review Quote</th>
                    <th>Flight Route</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testimonials as $testimonial)
                <tr>
                    <td class="font-monospace small text-muted text-center fw-bold">
                        #{{ $testimonial->sort_order }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($testimonial->image)
                                <img src="{{ asset('storage/' . $testimonial->image) }}" alt="User" class="rounded-circle object-fit-cover shadow-sm" width="38" height="38">
                            @else
                                <div class="rounded-circle bg-navy text-gold border d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.8rem;">
                                    {{ substr($testimonial->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-navy small" style="line-height: 1.2;">{{ $testimonial->name }}</div>
                                <div class="text-muted small" style="font-size: 0.7rem;">
                                    @if($testimonial->designation)
                                        <span>{{ $testimonial->designation }}</span>
                                    @endif
                                    @if($testimonial->company)
                                        <span> at {{ $testimonial->company }}</span>
                                    @endif
                                    @if($testimonial->location)
                                        <span class="d-block text-muted"><i class="fa-solid fa-location-dot me-1"></i>{{ $testimonial->location }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-warning small d-flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fa-solid fa-star" style="font-size: 0.75rem;"></i>
                                @else
                                    <i class="fa-regular fa-star" style="font-size: 0.75rem;"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="small font-monospace text-muted mt-1" style="font-size: 0.65rem;">({{ $testimonial->rating }}/5 Stars)</div>
                    </td>
                    <td>
                        <div class="small text-muted text-truncate" style="max-width: 280px;" title="{{ $testimonial->review }}">
                            &ldquo;{{ $testimonial->review }}&rdquo;
                        </div>
                    </td>
                    <td>
                        @if($testimonial->flight_route)
                            <span class="badge bg-royal bg-opacity-10 text-royal font-monospace" style="font-size: 0.7rem;"><i class="fa-solid fa-plane-departure me-1"></i>{{ $testimonial->flight_route }}</span>
                        @else
                            <span class="text-muted small">None</span>
                        @endif
                    </td>
                    <td>
                        @if($testimonial->is_featured)
                            <span class="badge bg-warning text-navy rounded-pill px-2 py-1"><i class="fa-solid fa-star me-1"></i>Featured</span>
                        @else
                            <span class="text-muted small">Standard</span>
                        @endif
                    </td>
                    <td>
                        @if($testimonial->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit Testimonial">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Testimonial">
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
        var table = $('#testimonials-table').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search reviews..."
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this testimonial?")) {
                form.submit();
            }
        });
    });
</script>
@endsection
