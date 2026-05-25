@extends('layouts.admin')

@section('title', 'Add Testimonial')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}" class="text-decoration-none text-muted">Testimonials</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Add Testimonial</h2>
    <p class="text-muted mb-0">Record a customer's success review. Specify their flight route, define their designation/location, and select their star-rating weight.</p>
</div>

<form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Reviewer Name</label>
                            <input type="text" class="form-control px-3" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. John Doe" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="flight_route" class="form-label fw-bold">Flight Experience Route (optional)</label>
                            <input type="text" class="form-control px-3" id="flight_route" name="flight_route" value="{{ old('flight_route') }}" placeholder="e.g. New York to Paris (JFK-CDG)">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="designation" class="form-label fw-bold">Designation (optional)</label>
                            <input type="text" class="form-control px-3" id="designation" name="designation" value="{{ old('designation') }}" placeholder="e.g. Managing Director">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="company" class="form-label fw-bold">Company (optional)</label>
                            <input type="text" class="form-control px-3" id="company" name="company" value="{{ old('company') }}" placeholder="e.g. TechCorp">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="location" class="form-label fw-bold">Location (optional)</label>
                            <input type="text" class="form-control px-3" id="location" name="location" value="{{ old('location') }}" placeholder="e.g. Boston, MA">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="review" class="form-label fw-bold">Customer Review Quote</label>
                            <textarea class="form-control px-3" id="review" name="review" rows="6" placeholder="Paste the customer's text review or testimonial quote here..." required>{{ old('review') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-12 col-xl-4">
            <!-- Parameters -->
            <div class="card premium-card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-sliders text-warning me-2"></i>Parameters
                </h5>

                <div class="mb-3">
                    <label for="rating" class="form-label fw-bold">Rating Score</label>
                    <select class="form-select px-3" id="rating" name="rating" required>
                        <option value="5">⭐⭐⭐⭐⭐ 5 Stars</option>
                        <option value="4">⭐⭐⭐⭐ 4 Stars</option>
                        <option value="3">⭐⭐⭐ 3 Stars</option>
                        <option value="2">⭐⭐ 2 Stars</option>
                        <option value="1">⭐ 1 Star</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="is_featured" class="form-label fw-bold">Featured status</label>
                    <select class="form-select px-3" id="is_featured" name="is_featured" required>
                        <option value="0">Standard Placement</option>
                        <option value="1">🌟 Promoted / Featured Spotlight</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label fw-bold">Sort Priority Order</label>
                    <input type="number" class="form-control px-3" id="sort_order" name="sort_order" value="0" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select class="form-select px-3" id="status" name="status" required>
                        <option value="active">Active (Visible)</option>
                        <option value="inactive">Inactive (Draft)</option>
                    </select>
                </div>
            </div>

            <!-- Profile Asset -->
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-regular fa-image text-warning me-2"></i>Reviewer Avatar
                </h5>

                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">Avatar Photo (square recommended)</label>
                    <input type="file" class="form-control image-preview-trigger" id="image" name="image" data-preview-id="avatar-preview-box">
                    <div class="mt-3 p-2 border rounded-circle mx-auto bg-light d-flex align-items-center justify-content-center overflow-hidden" style="width: 120px; height: 120px;">
                        <img src="" id="avatar-preview-box" alt="Avatar Preview" class="object-fit-cover w-100 h-100 d-none">
                        <span class="text-muted small text-center px-2 py-4" id="avatar-preview-placeholder"><i class="fa-regular fa-user fs-1 text-muted d-block mb-1"></i>No Image</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-3 mt-4 text-end">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary px-4 rounded-pill me-2">Cancel</a>
        <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Publish Testimonial</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Image file preview triggers
        $('.image-preview-trigger').on('change', function() {
            var file = this.files[0];
            var previewId = $(this).data('preview-id');
            var placeholderId = previewId.replace('-box', '-placeholder');
            
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewId).attr('src', e.target.result).removeClass('d-none');
                    $('#' + placeholderId).addClass('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                $('#' + previewId).addClass('d-none').attr('src', '');
                $('#' + placeholderId).removeClass('d-none');
            }
        });
    });
</script>
@endsection
