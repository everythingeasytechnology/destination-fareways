@extends('layouts.admin')

@section('title', 'Flight Offers')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Offers</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Flight Offers</h2>
        <p class="text-muted mb-0">Create and display promotional flight deals, discounts, and promo codes on key pages.</p>
    </div>
    
    <a href="{{ route('admin.offers.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Create New Offer
    </a>
</div>

<!-- Offers Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="offers-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 60px;">Sort</th>
                    <th>Cover</th>
                    <th>Offer Title</th>
                    <th>Route Info</th>
                    <th>Pricing</th>
                    <th>Validity</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
                <tr>
                    <td class="font-monospace small text-muted text-center fw-bold">
                        #{{ $offer->sort_order }}
                    </td>
                    <td>
                        @if($offer->image)
                            <img src="{{ asset('storage/' . $offer->image) }}" alt="Cover" class="rounded object-fit-cover shadow-sm" width="55" height="40">
                        @else
                            <div class="rounded bg-light text-muted d-flex align-items-center justify-content-center border" style="width: 55px; height: 40px; font-size: 0.75rem;">No Img</div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-navy">{{ $offer->title }}</div>
                        <div class="small text-muted">{{ $offer->subtitle ?? 'No subtitle' }}</div>
                        @if($offer->promo_code)
                            <span class="badge bg-warning text-navy font-monospace px-2 py-0.5" style="font-size: 0.7rem;">PROMO: {{ $offer->promo_code }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-1 fw-bold text-navy small">
                            <span>{{ $offer->from_city }}</span>
                            <i class="fa-solid fa-arrow-right text-muted" style="font-size: 0.7rem;"></i>
                            <span>{{ $offer->to_city }}</span>
                        </div>
                        <div class="small text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-plane-departure me-1"></i>{{ $offer->airline ?? 'Any Airline' }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-success">${{ $offer->offer_price }}</div>
                        <div class="small text-muted text-decoration-line-through">${{ $offer->original_price }}</div>
                        @if($offer->discount_label)
                            <span class="badge bg-success bg-opacity-10 text-success small" style="font-size: 0.65rem;">{{ $offer->discount_label }}</span>
                        @endif
                    </td>
                    <td class="small text-muted" style="font-size: 0.8rem;">
                        {{ $offer->valid_from ? $offer->valid_from->format('Y-m-d') : 'N/A' }} to<br>
                        {{ $offer->valid_until ? $offer->valid_until->format('Y-m-d') : 'N/A' }}
                    </td>
                    <td>
                        @if($offer->is_featured)
                            <span class="badge bg-warning text-navy rounded-pill px-2 py-1"><i class="fa-solid fa-star me-1"></i>Featured</span>
                        @else
                            <span class="text-muted small">Standard</span>
                        @endif
                    </td>
                    <td>
                        @if($offer->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.offers.edit', $offer->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit Offer">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Offer">
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
        var table = $('#offers-table').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search flight offers..."
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this offer? It will be soft deleted.")) {
                form.submit();
            }
        });
    });
</script>
@endsection
