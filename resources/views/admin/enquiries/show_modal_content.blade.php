<div class="row g-3">
    <!-- Flight Segment Details Card -->
    <div class="col-12 col-md-6">
        <h6 class="fw-bold text-navy border-bottom pb-1"><i class="fa-solid fa-plane-up text-warning me-2"></i>Flight Parameters</h6>
        <table class="table table-sm table-borderless small mb-0">
            <tr>
                <td class="fw-bold text-muted" style="width: 130px;">From Airport:</td>
                <td class="fw-bold text-navy font-monospace">{{ $enquiry->from_airport }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">To Airport:</td>
                <td class="fw-bold text-navy font-monospace">{{ $enquiry->to_airport }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Trip Type:</td>
                <td><span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $enquiry->trip_type)) }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Cabin Class:</td>
                <td><span class="badge bg-navy text-warning">{{ ucwords(str_replace('_', ' ', $enquiry->cabin_class)) }}</span></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Passengers:</td>
                <td class="fw-bold text-navy">
                    {{ $enquiry->adults }} Adults
                    @if($enquiry->children > 0) · {{ $enquiry->children }} Children @endif
                    @if($enquiry->infants > 0) · {{ $enquiry->infants }} Infants @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Departing:</td>
                <td class="fw-bold text-navy"><i class="fa-regular fa-calendar me-1 text-primary"></i>{{ $enquiry->departure_date ? $enquiry->departure_date->format('M d, Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Returning:</td>
                <td class="fw-bold text-navy">
                    @if($enquiry->return_date)
                        <i class="fa-regular fa-calendar me-1 text-info"></i>{{ $enquiry->return_date->format('M d, Y') }}
                    @else
                        <span class="text-muted italic">One Way flight</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Booking Preferences -->
    <div class="col-12 col-md-6 border-start ps-md-3">
        <h6 class="fw-bold text-navy border-bottom pb-1"><i class="fa-solid fa-sliders text-primary me-2"></i>Preferences & Metadata</h6>
        <table class="table table-sm table-borderless small mb-0">
            <tr>
                <td class="fw-bold text-muted" style="width: 130px;">Customer Name:</td>
                <td class="fw-bold text-navy">{{ $enquiry->name }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Email:</td>
                <td><a href="mailto:{{ $enquiry->email }}" class="text-decoration-none fw-bold text-primary">{{ $enquiry->email }}</a></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Phone:</td>
                <td><a href="tel:{{ $enquiry->phone }}" class="text-decoration-none fw-bold text-success"><i class="fa-solid fa-phone me-1"></i>{{ $enquiry->phone }}</a></td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Preferred Airline:</td>
                <td class="fw-bold text-navy"><i class="fa-solid fa-plane me-1 text-secondary"></i>{{ $enquiry->preferred_airline ?? 'Flexible / Any Airline' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Budget Limit:</td>
                <td class="fw-bold text-success">{{ $enquiry->budget ? '$'.$enquiry->budget : 'Flexible' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">IP Address:</td>
                <td class="text-muted font-monospace" style="font-size: 0.8rem;">{{ $enquiry->ip ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td class="fw-bold text-muted">Date Submitted:</td>
                <td class="text-navy">{{ $enquiry->created_at->format('M d, Y H:i') }} <small class="text-muted">({{ $enquiry->created_at->diffForHumans() }})</small></td>
            </tr>
        </table>
    </div>

    <!-- Special Requests -->
    @if($enquiry->special_requests)
    <div class="col-12">
        <div class="p-2 border bg-light rounded">
            <span class="small fw-bold text-muted d-block"><i class="fa-regular fa-comment-dots me-1"></i>Special Requests / Notes:</span>
            <p class="small mb-0 text-navy">{{ $enquiry->special_requests }}</p>
        </div>
    </div>
    @endif

    <!-- Workflow Status Actions -->
    <div class="col-12 border-top pt-3 mt-2">
        <form action="{{ route('admin.enquiries.update', $enquiry->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="status" class="form-label fw-bold small">Workflow Stage</label>
                    <select class="form-select form-select-sm" name="status" id="status" required>
                        <option value="new" {{ $enquiry->status === 'new' ? 'selected' : '' }}>🔴 New Submission</option>
                        <option value="reviewed" {{ $enquiry->status === 'reviewed' ? 'selected' : '' }}>🟡 Reviewed / Searching</option>
                        <option value="quoted" {{ $enquiry->status === 'quoted' ? 'selected' : '' }}>🔵 Quotation Sent</option>
                        <option value="booked" {{ $enquiry->status === 'booked' ? 'selected' : '' }}>🟢 Booking Confirmed</option>
                        <option value="cancelled" {{ $enquiry->status === 'cancelled' ? 'selected' : '' }}>⚫ Cancelled / No Interest</option>
                    </select>
                </div>
                <div class="col-12 col-md-8">
                    <label for="admin_notes" class="form-label fw-bold small">Internal Staff Notes / Pricing Calculations</label>
                    <textarea class="form-control form-control-sm" name="admin_notes" id="admin_notes" rows="2" placeholder="Enter quote flight numbers, pricing quotes, or call updates...">{{ $enquiry->admin_notes }}</textarea>
                </div>
            </div>
            
            <div class="text-end mt-3 border-top pt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-action px-4"><i class="fa-solid fa-floppy-disk me-1"></i>Save Progress</button>
            </div>
        </form>
    </div>
</div>
