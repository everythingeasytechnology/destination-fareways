<div class="row g-3">
    <!-- Message Info -->
    <div class="col-12 col-md-6">
        <h6 class="fw-bold text-navy border-bottom pb-1"><i class="fa-solid fa-circle-info text-warning me-2"></i>Sender Information</h6>
        <table class="table table-sm table-borderless small mb-0">
            <tr>
                <td class="fw-bold text-muted" style="width: 100px;">Name:</td>
                <td class="fw-bold text-navy">{{ $contact->name }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Email:</td>
                <td><a href="mailto:{{ $contact->email }}" class="text-decoration-none fw-bold text-primary">{{ $contact->email }}</a></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Phone:</td>
                <td>
                    @if($contact->phone)
                        <a href="tel:{{ $contact->phone }}" class="text-decoration-none fw-bold text-success"><i class="fa-solid fa-phone me-1"></i>{{ $contact->phone }}</a>
                    @else
                        <span class="text-muted small">Not provided</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Metadata -->
    <div class="col-12 col-md-6 border-start ps-md-3">
        <h6 class="fw-bold text-navy border-bottom pb-1"><i class="fa-solid fa-network-wired text-primary me-2"></i>Network Details</h6>
        <table class="table table-sm table-borderless small mb-0">
            <tr>
                <td class="fw-bold text-muted" style="width: 100px;">IP Address:</td>
                <td class="text-muted font-monospace" style="font-size: 0.8rem;">{{ $contact->ip ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Submitted:</td>
                <td class="text-navy">{{ $contact->created_at->format('M d, Y H:i') }} <small class="text-muted">({{ $contact->created_at->diffForHumans() }})</small></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Status:</td>
                <td>
                    @if($contact->status === 'new')
                        <span class="badge bg-danger">New / Unread</span>
                    @elseif($contact->status === 'read')
                        <span class="badge bg-primary">Read</span>
                    @else
                        <span class="badge bg-success">Replied</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Subject & Message -->
    <div class="col-12">
        <div class="p-3 border rounded bg-light">
            <span class="small fw-bold text-muted d-block font-monospace mb-1">Subject: {{ $contact->subject }}</span>
            <span class="small fw-bold text-muted d-block border-bottom pb-1"><i class="fa-regular fa-envelope-open me-1"></i>Customer Message:</span>
            <p class="small mb-0 text-navy mt-2" style="white-space: pre-wrap; line-height: 1.5;">{{ $contact->message }}</p>
        </div>
    </div>

    <!-- Reply Section -->
    <div class="col-12 border-top pt-3 mt-2">
        <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="admin_reply" class="form-label fw-bold text-navy"><i class="fa-solid fa-reply text-success me-2"></i>Send Email Reply</label>
                <textarea class="form-control" name="admin_reply" id="admin_reply" rows="4" placeholder="Type your professional reply here... This will be sent directly to the customer's email address." required>{{ $contact->admin_reply }}</textarea>
            </div>
            
            <div class="text-end mt-3 border-top pt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-action px-4"><i class="fa-solid fa-paper-plane me-1"></i>Dispatch Reply</button>
            </div>
        </form>
    </div>
</div>
