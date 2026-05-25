@extends('layouts.admin')

@section('title', 'Flight Enquiries')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Flight Enquiries</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Flight Enquiries Management</h2>
        <p class="text-muted mb-0">Track and fulfill customized flight itineraries, manage quotations, and follow-up on customer flight bookings.</p>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.enquiries.export', ['status' => request('status')]) }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fa-solid fa-file-excel me-2"></i>Export to CSV
        </a>
    </div>
</div>

<!-- Status Filters Pipeline Row -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm px-3 rounded-pill {{ !request()->filled('status') ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                All Enquiries ({{ \App\Models\FlightEnquiry::count() }})
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'new']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'new' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                🔴 New ({{ \App\Models\FlightEnquiry::where('status', 'new')->count() }})
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'reviewed']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'reviewed' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                🟡 Reviewed ({{ \App\Models\FlightEnquiry::where('status', 'reviewed')->count() }})
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'quoted']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'quoted' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                🔵 Quoted ({{ \App\Models\FlightEnquiry::where('status', 'quoted')->count() }})
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'booked']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'booked' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                🟢 Booked ({{ \App\Models\FlightEnquiry::where('status', 'booked')->count() }})
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'cancelled']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'cancelled' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                ⚫ Cancelled ({{ \App\Models\FlightEnquiry::where('status', 'cancelled')->count() }})
            </a>
        </div>
    </div>
</div>

<!-- Enquiries Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="enquiries-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Submitted</th>
                    <th>Customer Name</th>
                    <th>Itinerary (Airports)</th>
                    <th>Cabin & Pax</th>
                    <th>Airline Preference</th>
                    <th>Budget</th>
                    <th>Pipeline Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enquiries as $enquiry)
                <tr>
                    <td class="font-monospace small text-muted">
                        {{ $enquiry->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td>
                        <div class="fw-bold text-navy">{{ $enquiry->name }}</div>
                        <div class="small text-muted">{{ $enquiry->email }}</div>
                        <div class="small text-success fw-bold"><i class="fa-solid fa-phone me-1"></i>{{ $enquiry->phone }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-navy font-monospace">{{ $enquiry->from_airport }}</span>
                            <i class="fa-solid fa-plane text-muted small"></i>
                            <span class="fw-bold text-navy font-monospace">{{ $enquiry->to_airport }}</span>
                        </div>
                        <div class="small text-muted">
                            Depart: {{ $enquiry->departure_date ? $enquiry->departure_date->format('Y-m-d') : 'N/A' }}
                            @if($enquiry->return_date)
                            | Return: {{ $enquiry->return_date->format('Y-m-d') }}
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-navy border small mb-1">{{ ucwords(str_replace('_', ' ', $enquiry->cabin_class)) }}</span>
                        <div class="small text-muted">
                            {{ $enquiry->adults }} A
                            @if($enquiry->children > 0) / {{ $enquiry->children }} C @endif
                            @if($enquiry->infants > 0) / {{ $enquiry->infants }} I @endif
                            · {{ ucwords(str_replace('_', ' ', $enquiry->trip_type)) }}
                        </div>
                    </td>
                    <td class="small text-navy fw-bold">
                        <i class="fa-solid fa-plane-departure text-muted me-1"></i>{{ $enquiry->preferred_airline ?? 'Flexible' }}
                    </td>
                    <td class="small text-success fw-bold">
                        {{ $enquiry->budget ? '$'.$enquiry->budget : 'Flexible' }}
                    </td>
                    <td>
                        @if($enquiry->status === 'new')
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-exclamation me-1"></i>New</span>
                        @elseif($enquiry->status === 'reviewed')
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-dot me-1"></i>Reviewed</span>
                        @elseif($enquiry->status === 'quoted')
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5"><i class="fa-solid fa-paper-plane me-1"></i>Quoted</span>
                        @elseif($enquiry->status === 'booked')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Booked</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Cancelled</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- View Button -->
                            <button type="button" class="btn btn-sm btn-light border text-navy view-enquiry-btn" data-id="{{ $enquiry->id }}" title="Inspect Enquiry Details">
                                <i class="fa-regular fa-eye"></i>
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.enquiries.destroy', $enquiry->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Trash Enquiry">
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

<!-- Enquiry Detail Modal Shell -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-3">
                <h5 class="modal-title fw-bold display-font"><i class="fa-solid fa-plane-up me-2"></i>Inspect Customized Flight Enquiry</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="enquiry-modal-body">
                <!-- Injected dynamically via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">Loading enquiry parameters from secure database...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        var table = $('#enquiries-table').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search flight database..."
            }
        });

        // Trigger AJAX Modal Load
        $('.view-enquiry-btn').on('click', function() {
            var enquiryId = $(this).data('id');
            var modalBody = $('#enquiry-modal-body');
            
            // Show loading placeholder
            modalBody.html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2">Loading enquiry parameters from secure database...</p></div>');
            $('#enquiryModal').modal('show');

            // AJAX request to GET enquiry show content
            $.ajax({
                url: "/admin/enquiries/" + enquiryId,
                method: "GET",
                success: function(htmlContent) {
                    modalBody.html(htmlContent);
                },
                error: function() {
                    modalBody.html('<div class="alert alert-danger text-center"><i class="fa-solid fa-circle-exclamation me-2"></i>Failed to fetch flight enquiry parameters. Please refresh and try again.</div>');
                }
            });
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to move this flight enquiry to trash? Soft deleted records can be recovered by system administrators.")) {
                form.submit();
            }
        });
    });
</script>
@endsection
