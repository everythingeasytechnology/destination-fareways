@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Newsletter</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Newsletter Subscribers</h2>
        <p class="text-muted mb-0">Manage customer subscriptions, review campaign lists, and manually opt-out profiles.</p>
    </div>
    
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.newsletter.export') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fa-solid fa-file-excel me-2"></i>Export to CSV
        </a>
    </div>
</div>

<!-- Subscribers Stats Widget -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card premium-card border-0 shadow-sm p-3 bg-white d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" style="width: 50px; height: 50px;">
                <i class="fa-solid fa-envelope fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold text-navy mb-0">{{ \App\Models\NewsletterSubscriber::where('status', 'active')->count() }}</h5>
                <span class="small text-muted">Active Subscriptions</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card premium-card border-0 shadow-sm p-3 bg-white d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger" style="width: 50px; height: 50px;">
                <i class="fa-solid fa-envelope-circle-check fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold text-navy mb-0">{{ \App\Models\NewsletterSubscriber::where('status', 'unsubscribed')->count() }}</h5>
                <span class="small text-muted">Unsubscribed Lists</span>
            </div>
        </div>
    </div>
</div>

<!-- Subscribers Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="subscribers-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Subscribed Date</th>
                    <th>Email Address</th>
                    <th>Name</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscribers as $sub)
                <tr>
                    <td class="font-monospace small text-muted">
                        {{ $sub->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="fw-bold text-navy">
                        {{ $sub->email }}
                    </td>
                    <td>
                        {{ $sub->name ?? 'N/A' }}
                    </td>
                    <td class="font-monospace small text-muted">
                        {{ $sub->ip ?? 'Unknown' }}
                    </td>
                    <td>
                        @if($sub->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Unsubscribed</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Unsubscribe Option -->
                            @if($sub->status === 'active')
                            <form action="{{ route('admin.newsletter.unsubscribe', $sub->id) }}" method="POST" class="d-inline opt-out-form-confirm">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light border text-warning" title="Unsubscribe Profile">
                                    <i class="fa-solid fa-user-slash"></i> Opt-out
                                </button>
                            </form>
                            @endif

                            <!-- Delete Form -->
                            <form action="{{ route('admin.newsletter.destroy', $sub->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete subscriber">
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
        var table = $('#subscribers-table').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search subscribers list..."
            }
        });

        // Confirmation dialog for unsubscribes
        $('.opt-out-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to unsubscribe this email address from the newsletter?")) {
                form.submit();
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to permanently delete this subscriber? This action cannot be undone.")) {
                form.submit();
            }
        });
    });
</script>
@endsection
