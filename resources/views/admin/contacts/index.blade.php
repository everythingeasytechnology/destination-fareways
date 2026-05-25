@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Contact Messages</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Contact Messages</h2>
    <p class="text-muted mb-0">Review general support enquiries, custom feedback, and dispatch direct responses to customers.</p>
</div>

<!-- Messages Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="contacts-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sender Info</th>
                    <th>Subject</th>
                    <th>Message Snippet</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $msg)
                <!-- Gold row highlight if unread -->
                <tr class="{{ !$msg->is_read ? 'table-warning-gold' : '' }}" id="row-msg-{{ $msg->id }}">
                    <td class="font-monospace small text-muted">
                        {{ $msg->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td>
                        <div class="fw-bold text-navy">{{ $msg->name }}</div>
                        <div class="small text-muted">{{ $msg->email }}</div>
                        @if($msg->phone)
                        <div class="small text-success fw-bold"><i class="fa-solid fa-phone me-1"></i>{{ $msg->phone }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-navy text-truncate" style="max-width: 180px;" title="{{ $msg->subject }}">{{ $msg->subject }}</div>
                    </td>
                    <td class="small text-muted">
                        <div class="text-truncate" style="max-width: 320px;" title="{{ $msg->message }}">{{ Str::limit($msg->message, 80) }}</div>
                    </td>
                    <td>
                        @if($msg->status === 'new')
                            <span class="badge bg-danger rounded-pill px-3 py-1.5 status-badge" id="badge-msg-{{ $msg->id }}"><i class="fa-solid fa-circle-exclamation me-1"></i>Unread</span>
                        @elseif($msg->status === 'read')
                            <span class="badge bg-primary rounded-pill px-3 py-1.5 status-badge" id="badge-msg-{{ $msg->id }}"><i class="fa-solid fa-envelope-open me-1"></i>Read</span>
                        @else
                            <span class="badge bg-success rounded-pill px-3 py-1.5 status-badge" id="badge-msg-{{ $msg->id }}"><i class="fa-solid fa-circle-check me-1"></i>Replied</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- View Button -->
                            <button type="button" class="btn btn-sm btn-light border text-navy view-msg-btn" data-id="{{ $msg->id }}" title="Read & Reply">
                                <i class="fa-regular fa-envelope"></i> Read
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.contacts.destroy', $msg->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Trash Message">
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

<!-- Message Detail Modal Shell -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-navy text-white py-3">
                <h5 class="modal-title fw-bold display-font"><i class="fa-solid fa-envelope-open-text me-2"></i>Inspect Customer Support Message</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="contact-modal-body">
                <!-- Injected dynamically via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">Loading secure message details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium custom styling for unread highlight */
    .table-warning-gold {
        background-color: rgba(245, 158, 11, 0.08) !important;
        border-left: 4px solid var(--gold) !important;
        transition: all 0.3s ease;
    }
    .table-warning-gold:hover {
        background-color: rgba(245, 158, 11, 0.12) !important;
    }
</style>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        var table = $('#contacts-table').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            pageLength: 25,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search message transcripts..."
            }
        });

        // Trigger AJAX Modal Load & mark as Read
        $('.view-msg-btn').on('click', function() {
            var msgId = $(this).data('id');
            var modalBody = $('#contact-modal-body');
            var row = $('#row-msg-' + msgId);
            var badge = $('#badge-msg-' + msgId);
            
            // Show loading placeholder
            modalBody.html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2">Loading secure message details...</p></div>');
            $('#contactModal').modal('show');

            // AJAX request to GET message details
            $.ajax({
                url: "/admin/contacts/" + msgId,
                method: "GET",
                success: function(htmlContent) {
                    modalBody.html(htmlContent);
                    
                    // Instantly remove gold highlight and update unread badge in DOM on success!
                    if (row.hasClass('table-warning-gold')) {
                        row.removeClass('table-warning-gold');
                        badge.removeClass('bg-danger').addClass('bg-primary').html('<i class="fa-solid fa-envelope-open me-1"></i>Read');
                    }
                },
                error: function() {
                    modalBody.html('<div class="alert alert-danger text-center"><i class="fa-solid fa-circle-exclamation me-2"></i>Failed to fetch support message. Please refresh and try again.</div>');
                }
            });
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this message? Deleted messages are archived and soft deleted.")) {
                form.submit();
            }
        });
    });
</script>
@endsection
