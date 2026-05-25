<div class="row g-3">
    <!-- Travel Details Card -->
    <div class="col-12 col-md-6">
        <h6 class="fw-bold text-navy border-bottom pb-1"><i class="fa-solid fa-plane-departure text-warning me-2"></i>Flight Parameters</h6>
        <table class="table table-sm table-borderless small mb-0">
            <tr>
                <td class="fw-bold text-muted" style="width: 120px;">From City:</td>
                <td class="fw-bold text-navy">{{ $lead->from_city }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">To City:</td>
                <td class="fw-bold text-navy">{{ $lead->to_city }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Trip Type:</td>
                <td><span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $lead->trip_type)) }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Cabin Class:</td>
                <td><span class="badge bg-navy text-warning">{{ ucwords(str_replace('_', ' ', $lead->cabin_class)) }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Passengers:</td>
                <td class="fw-bold text-navy">{{ $lead->passengers }} Pax</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Travel Date:</td>
                <td class="fw-bold text-navy"><i class="fa-regular fa-calendar-check me-1 text-primary"></i>{{ $lead->travel_date ? $lead->travel_date->format('M d, Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Return Date:</td>
                <td class="fw-bold text-navy">
                    @if($lead->return_date)
                        <i class="fa-regular fa-calendar-check me-1 text-info"></i>{{ $lead->return_date->format('M d, Y') }}
                    @else
                        <span class="text-muted italic">One Way Flight</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Contact Info -->
    <div class="col-12 col-md-6 border-start ps-md-3">
        <h6 class="fw-bold text-navy border-bottom pb-1"><i class="fa-solid fa-address-card text-primary me-2"></i>Contact & Source</h6>
        <table class="table table-sm table-borderless small mb-0">
            <tr>
                <td class="fw-bold text-muted" style="width: 120px;">Name:</td>
                <td class="fw-bold text-navy">{{ $lead->name }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Email:</td>
                <td><a href="mailto:{{ $lead->email }}" class="text-decoration-none fw-bold text-primary">{{ $lead->email }}</a></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Phone:</td>
                <td><a href="tel:{{ $lead->phone }}" class="text-decoration-none fw-bold text-success"><i class="fa-solid fa-phone me-1"></i>{{ $lead->phone }}</a></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Source Page:</td>
                <td class="text-navy font-monospace" style="font-size: 0.8rem;">{{ $lead->source_page ?? 'Direct Landing' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">IP Address:</td>
                <td class="text-muted font-monospace" style="font-size: 0.8rem;">{{ $lead->ip ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Created:</td>
                <td class="text-navy">{{ $lead->created_at->format('M d, Y H:i') }} <small class="text-muted">({{ $lead->created_at->diffForHumans() }})</small></td>
            </tr>
        </table>
    </div>

    <!-- Message -->
    @if($lead->message)
    <div class="col-12">
        <div class="p-2 border bg-light rounded">
            <span class="small fw-bold text-muted d-block"><i class="fa-regular fa-comment-dots me-1"></i>Customer Message:</span>
            <p class="small mb-0 text-navy">{{ $lead->message }}</p>
        </div>
    </div>
    @endif

    <!-- Admin Status Progression -->
    <div class="col-12 border-top pt-3 mt-2">
        <form action="{{ route('admin.leads.update', $lead->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="status" class="form-label fw-bold small">Disposition Status</label>
                    <select class="form-select form-select-sm" name="status" id="status" required>
                        <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>🔴 New Lead</option>
                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>🔵 Contacted</option>
                        <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>🟢 Converted (Sold)</option>
                        <option value="closed" {{ $lead->status === 'closed' ? 'selected' : '' }}>⚫ Closed (No Deal)</option>
                    </select>
                </div>
                <div class="col-12 col-md-8">
                    <label for="admin_notes" class="form-label fw-bold small">Follow-up Notes / Activity Logs</label>
                    <textarea class="form-control form-control-sm" name="admin_notes" id="admin_notes" rows="2" placeholder="e.g. Spoke to client, quoted $450 for roundtrip NYC-LON...">{{ $lead->admin_notes }}</textarea>
                </div>
            </div>
            
            <div class="text-end mt-3 border-top pt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-action px-4"><i class="fa-solid fa-floppy-disk me-1"></i>Update Disposition</button>
            </div>
        </form>
    </div>
</div>
