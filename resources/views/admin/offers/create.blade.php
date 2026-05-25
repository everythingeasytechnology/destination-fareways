@extends('layouts.admin')

@section('title', 'Create Flight Offer')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.offers.index') }}" class="text-decoration-none text-muted">Offers</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Create Flight Offer</h2>
    <p class="text-muted mb-0">Design a premium flight package promotion card, configure flight specifications, and optimize metadata.</p>
</div>

<form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="offerTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab"><i class="fa-solid fa-plane-departure me-2"></i>General & Flight Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab"><i class="fa-solid fa-magnifying-glass me-2"></i>SEO & Socials</button>
                    </li>
                </ul>

                <div class="tab-content" id="offerTabContent">
                    
                    <!-- Tab 1: General & Flight Details -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">Offer Title</label>
                                    <input type="text" class="form-control px-3" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Flight Deal to London (NYC to LHR)" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="slug" class="form-label fw-bold">Auto-Generated URL Slug</label>
                                    <input type="text" class="form-control px-3 font-monospace" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. flight-deal-london-nyc-lhr" style="font-size: 0.85rem;">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="subtitle" class="form-label fw-bold">Promo Subtitle</label>
                                    <input type="text" class="form-control px-3" id="subtitle" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Save up to 25% on roundtrip flights this autumn">
                                </div>
                            </div>

                            <!-- Flight parameters -->
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="from_city" class="form-label fw-bold">Departure City</label>
                                    <input type="text" class="form-control px-3" id="from_city" name="from_city" value="{{ old('from_city') }}" placeholder="e.g. New York (NYC)" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="to_city" class="form-label fw-bold">Arrival City</label>
                                    <input type="text" class="form-control px-3" id="to_city" name="to_city" value="{{ old('to_city') }}" placeholder="e.g. London (LON)" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="airline" class="form-label fw-bold">Airlines Operating</label>
                                    <input type="text" class="form-control px-3" id="airline" name="airline" value="{{ old('airline') }}" placeholder="e.g. British Airways / Virgin">
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="original_price" class="form-label fw-bold">Original Price ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control px-3" id="original_price" name="original_price" value="{{ old('original_price') }}" placeholder="699.00" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="offer_price" class="form-label fw-bold">Offer Price ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control px-3" id="offer_price" name="offer_price" value="{{ old('offer_price') }}" placeholder="499.00" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="discount_label" class="form-label fw-bold">Discount Label</label>
                                    <input type="text" class="form-control px-3" id="discount_label" name="discount_label" value="{{ old('discount_label') }}" placeholder="e.g. Save $200 Instantly!">
                                </div>
                            </div>

                            <!-- Promo code & dates -->
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="promo_code" class="form-label fw-bold">Coupon Promo Code</label>
                                    <input type="text" class="form-control px-3 font-monospace" id="promo_code" name="promo_code" value="{{ old('promo_code') }}" placeholder="e.g. SAVE200">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="valid_from" class="form-label fw-bold">Valid From Date</label>
                                    <input type="text" class="form-control flatpickr-date px-3" id="valid_from" name="valid_from" value="{{ old('valid_from') }}" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="valid_until" class="form-label fw-bold">Valid Until Date</label>
                                    <input type="text" class="form-control flatpickr-date px-3" id="valid_until" name="valid_until" value="{{ old('valid_until') }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="short_desc" class="form-label fw-bold">Short Introduction Description</label>
                                    <textarea class="form-control px-3" id="short_desc" name="short_desc" rows="3" placeholder="Write a brief teaser description to display on cards list layout...">{{ old('short_desc') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Detailed Flight & Offer Description</label>
                                    <textarea class="form-control tinymce-editor" id="description" name="description" rows="10">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab 2: SEO Settings -->
                    <div class="tab-pane fade" id="seo" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_title" class="form-label fw-bold">Meta Title Tag</label>
                                    <input type="text" class="form-control px-3 seo-input" id="seo_title" name="seo_title" value="{{ old('seo_title') }}" placeholder="Optimize for search engine titles..." data-char-counter="seo-title-count" data-max="60">
                                    <div class="text-end small mt-1"><span id="seo-title-count">0</span> / 60 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_description" class="form-label fw-bold">Meta Description Tag</label>
                                    <textarea class="form-control px-3 seo-input" id="seo_description" name="seo_description" rows="3" placeholder="Write an engaging meta descriptor snippet..." data-char-counter="seo-desc-count" data-max="160">{{ old('seo_description') }}</textarea>
                                    <div class="text-end small mt-1"><span id="seo-desc-count">0</span> / 160 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label fw-bold">Focus SEO Keywords</label>
                                    <input type="text" class="form-control px-3" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords') }}" placeholder="e.g. flight deal, cheap flights, london travel">
                                </div>
                            </div>

                            <!-- Live Google snippet preview -->
                            <div class="col-12 my-4">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Google SEO Snippet Live Simulator</h6>
                                <div class="p-3 border rounded shadow-sm bg-white" style="font-family: Arial, sans-serif;">
                                    <div class="text-muted" style="font-size: 12px; margin-bottom: 2px;">
                                        https://destinationfareways.com/offers/<span id="prev-seo-slug" class="font-monospace">flight-deal-london</span>
                                    </div>
                                    <h5 id="prev-seo-title" class="text-primary mb-1 fw-normal" style="font-size: 19px; line-height: 1.3; cursor: pointer;">Flight Deal to London | Cheap Flights</h5>
                                    <p id="prev-seo-desc" class="text-dark small mb-0" style="font-size: 13px; line-height: 1.4; color: #4d5156 !important;">
                                        Enter description above to simulate this exact search snippet card rendering in live Google search index pages.
                                    </p>
                                </div>
                            </div>

                            <!-- Social OG image upload -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="og_image" class="form-label fw-bold">OG Share Card Image (1200x630px recommended)</label>
                                    <input type="file" class="form-control image-preview-trigger" id="og_image" name="og_image" data-preview-id="og-preview-box">
                                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                        <img src="" id="og-preview-box" alt="OG Preview" class="img-fluid h-100 d-none">
                                        <span class="text-muted small py-3" id="og-preview-placeholder">No image selected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Options Column -->
        <div class="col-12 col-xl-4">
            <!-- Publishing attributes -->
            <div class="card premium-card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-circle-check text-warning me-2"></i>Publish Settings
                </h5>

                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Display Status</label>
                    <select class="form-select px-3" id="status" name="status" required>
                        <option value="active">Active (Visible)</option>
                        <option value="inactive">Inactive (Hidden)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="is_featured" class="form-label fw-bold">Is Featured Promo Card?</label>
                    <select class="form-select px-3" id="is_featured" name="is_featured" required>
                        <option value="0">Standard Promo Card</option>
                        <option value="1" class="fw-bold text-warning bg-navy">🌟 Featured Promoted Offer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label fw-bold">Card Sort Priority Order</label>
                    <input type="number" class="form-control px-3" id="sort_order" name="sort_order" value="0" min="0" required>
                </div>
            </div>

            <!-- Upload Assets -->
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-regular fa-image text-warning me-2"></i>Promo Assets
                </h5>

                <div class="mb-4">
                    <label for="image" class="form-label fw-bold">List Cover Thumb (800x600px)</label>
                    <input type="file" class="form-control image-preview-trigger" id="image" name="image" data-preview-id="cover-preview-box">
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                        <img src="" id="cover-preview-box" alt="Cover Preview" class="img-fluid h-100 d-none">
                        <span class="text-muted small py-4" id="cover-preview-placeholder">No image selected</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="banner_image" class="form-label fw-bold">Main Details Banner (1920x600px)</label>
                    <input type="file" class="form-control image-preview-trigger" id="banner_image" name="banner_image" data-preview-id="banner-preview-box">
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 100px;">
                        <img src="" id="banner-preview-box" alt="Banner Preview" class="img-fluid h-100 d-none">
                        <span class="text-muted small py-3" id="banner-preview-placeholder">No image selected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-3 mt-4 text-end">
        <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary px-4 rounded-pill me-2">Cancel</a>
        <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Publish Offer</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto Slug Generation
        $('#title').on('input', function() {
            var slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
            $('#slug').val(slug);
            $('#prev-seo-slug').text(slug ? slug : 'flight-deal-london');
            
            // Set dynamic SEO Title initial fallback
            if ($('#seo_title').val() === '') {
                $('#prev-seo-title').text($(this).val());
            }
        });

        $('#slug').on('input', function() {
            var val = $(this).val();
            $('#prev-seo-slug').text(val ? val : 'flight-deal-london');
        });

        // SEO Character counters & Live Previews
        $('.seo-input').on('input', function() {
            var input = $(this);
            var val = input.val();
            var counterId = input.data('char-counter');
            var maxLen = input.data('max');
            var previewId = counterId.includes('title') ? 'prev-seo-title' : 'prev-seo-desc';
            
            $('#' + counterId).text(val.length);
            
            if (val.length > maxLen) {
                $('#' + counterId).removeClass('text-success').addClass('text-danger');
            } else {
                $('#' + counterId).removeClass('text-danger').addClass('text-success');
            }

            // Update simulator text
            if (counterId.includes('title')) {
                $('#' + previewId).text(val ? val : ($('#title').val() ? $('#title').val() : 'Flight Deal to London | Cheap Flights'));
            } else {
                $('#' + previewId).text(val ? val : 'Enter description above to simulate this exact search snippet card rendering in live Google search index pages.');
            }
        });

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
