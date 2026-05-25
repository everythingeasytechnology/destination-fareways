@extends('layouts.admin')

@section('title', 'Leads Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Leads</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Leads Management</h2>
        <p class="text-muted mb-0">Track and disposition leads coming from search engine marketing campaigns and direct flight lookup landings.</p>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.leads.export', ['status' => request('status')]) }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fa-solid fa-file-excel me-2"></i>Export to CSV
        </a>
    </div>
</div>

<!-- Status Filters Row -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.leads.index') }}" class="btn btn-sm px-3 rounded-pill {{ !request()->filled('status') ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                All Leads ({{ \App\Models\Lead::count() }})
            </a>
            <a href="{{ route('admin.leads.index', ['status' => 'new']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'new' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                🔴 New ({{ \App\Models\Lead::where('status', 'new')->count() }})
            </a>
            <a href="{{ route('admin.leads.index', ['status' => 'contacted']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'contacted' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                🔵 Contacted ({{ \App\Models\Lead::where('status', 'contacted')->count() }})
            </a>
            <a href="{{ route('admin.leads.index', ['status' => 'converted']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'converted' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                🟢 Converted ({{ \App\Models\Lead::where('status', 'converted')->count() }})
            </a>
            <a href="{{ route('admin.leads.index', ['status' => 'closed']) }}" class="btn btn-sm px-3 rounded-pill {{ request('status') === 'closed' ? 'btn-navy text-warning' : 'btn-light border text-navy' }}">
                ⚫ Closed ({{ \App\Models\Lead::where('status', 'closed')->count() }})
            </a>
        </div>
    </div>
</div>

<!-- Leads Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="leads-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Created</th>
                    <th>Customer Info</th>
                    <th>Route Plan</th>
                    <th>Cabin / Pax</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td class="font-monospace small text-muted">
                        {{ $lead->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td>
                        <div class="fw-bold text-navy">{{ $lead->name }}</div>
                        <div class="small text-muted">{{ $lead->email }}</div>
                        <div class="small text-success fw-bold"><i class="fa-solid fa-phone me-1"></i>{{ $lead->phone }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-navy">{{ $lead->from_city }}</span>
                            <i class="fa-solid fa-arrow-right text-muted small"></i>
                            <span class="fw-bold text-navy">{{ $lead->to_city }}</span>
                        </div>
                        <div class="small text-muted">
                            Depart: {{ $lead->travel_date ? $lead->travel_date->format('Y-m-d') : 'N/A' }}
                            @if($lead->return_date)
                            | Return: {{ $lead->return_date->format('Y-m-d') }}
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-navy border small mb-1">{{ ucwords(str_replace('_', ' ', $lead->cabin_class)) }}</span>
                        <div class="small text-muted">{{ $lead->passengers }} Pax · {{ ucwords(str_replace('_', ' ', $lead->trip_type)) }}</div>
                    </td>
                    <td class="small text-muted">
                        {{ $lead->source_page ?? 'Search Landing' }}
                    </td>
                    <td>
                        @if($lead->status === 'new')
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-exclamation me-1"></i>New</span>
                        @elseif($lead->status === 'contacted')
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5"><i class="fa-solid fa-phone me-1"></i>Contacted</span>
                        @elseif($lead->status === 'converted')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Converted</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Closed</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- View Button -->
                            <button type="button" class="btn btn-sm btn-light border text-navy view-lead-btn" data-id="{{ $lead->id }}" title="View Lead details">
                                <i class="fa-regular fa-eye"></i>
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Trash Lead">
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

<!-- Lead Detail Modal Shell -->
<div class="modal fade" id="leadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-3">
                <h5 class="modal-title fw-bold display-font"><i class="fa-solid fa-users-line me-2"></i>Inspect Landing Lead</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="lead-modal-body">
                <!-- Injected dynamically via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">Loading lead details from secure vault...</p>
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
        var table = $('#leads-table').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search leads database..."
            }
        });

        // Trigger AJAX Modal Load
        $('.view-lead-btn').on('click', function() {
            var leadId = $(this).data('id');
            var modalBody = $('#lead-modal-body');
            
            // Show loading placeholder
            modalBody.html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2">Loading lead details from secure vault...</p></div>');
            $('#leadModal').modal('show');

            // AJAX request to GET lead show partial view content
            $.ajax({
                url: "/admin/leads/" + leadId,
                method: "GET",
                success: function(htmlContent) {
                    modalBody.html(htmlContent);
                },
                error: function() {
                    modalBody.html('<div class="alert alert-danger text-center"><i class="fa-solid fa-circle-exclamation me-2"></i>Failed to fetch lead parameters. Please refresh and try again.</div>');
                }
            });
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to move this lead to trash? Soft deleted leads can be restored by system administrators.")) {
                form.submit();
            }
        });
    });
</script>
@endsection
