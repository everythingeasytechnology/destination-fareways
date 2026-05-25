@extends('layouts.admin')

@section('title', 'Edit Destination')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.destinations.index') }}" class="text-decoration-none text-muted">Destinations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Edit Destination</h2>
    <p class="text-muted mb-0">Modify travel location details, append new pictures to the image gallery, remove specific photos, and adjust metadata settings.</p>
</div>

<form action="{{ route('admin.destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="destinationTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab"><i class="fa-solid fa-earth-americas me-2"></i>Location Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="gallery-tab" data-bs-toggle="pill" data-bs-target="#gallery" type="button" role="tab"><i class="fa-regular fa-images me-2"></i>Image Gallery</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab"><i class="fa-solid fa-magnifying-glass me-2"></i>SEO & Socials</button>
                    </li>
                </ul>

                <div class="tab-content" id="destinationTabContent">
                    
                    <!-- Tab 1: Location Details -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">Destination / City Name</label>
                                    <input type="text" class="form-control px-3" id="name" name="name" value="{{ old('name', $destination->name) }}" placeholder="e.g. Honolulu" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label fw-bold">URL Slug</label>
                                    <input type="text" class="form-control px-3 font-monospace" id="slug" name="slug" value="{{ old('slug', $destination->slug) }}" placeholder="e.g. honolulu" style="font-size: 0.85rem;" required>
                                </div>
                            </div>

                            <!-- Geographical Info -->
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="country" class="form-label fw-bold">Country</label>
                                    <input type="text" class="form-control px-3" id="country" name="country" value="{{ old('country', $destination->country) }}" placeholder="e.g. United States" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label fw-bold">State / Province (optional)</label>
                                    <input type="text" class="form-control px-3" id="state" name="state" value="{{ old('state', $destination->state) }}" placeholder="e.g. Hawaii">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="airport_code" class="form-label fw-bold">Primary Airport Code</label>
                                    <input type="text" class="form-control px-3 font-monospace" id="airport_code" name="airport_code" value="{{ old('airport_code', $destination->airport_code) }}" placeholder="e.g. HNL" style="text-transform: uppercase;">
                                </div>
                            </div>

                            <!-- Highlights & Pricing -->
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="starting_price" class="form-label fw-bold">Starting Price Indicator ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control px-3" id="starting_price" name="starting_price" value="{{ old('starting_price', $destination->starting_price) }}" placeholder="299.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="best_time_to_visit" class="form-label fw-bold">Best Time to Visit</label>
                                    <input type="text" class="form-control px-3" id="best_time_to_visit" name="best_time_to_visit" value="{{ old('best_time_to_visit', $destination->best_time_to_visit) }}" placeholder="e.g. April to October">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="climate" class="form-label fw-bold">Climate Type</label>
                                    <input type="text" class="form-control px-3" id="climate" name="climate" value="{{ old('climate', $destination->climate) }}" placeholder="e.g. Tropical / Marine">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="short_desc" class="form-label fw-bold">Short Introduction Description</label>
                                    <textarea class="form-control px-3" id="short_desc" name="short_desc" rows="3" placeholder="Write a brief summary to show on grid tiles/lists (max 500 chars)...">{{ old('short_desc', $destination->short_desc) }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Detailed Destination Information Travel Guide</label>
                                    <textarea class="form-control tinymce-editor" id="description" name="description" rows="12">{{ old('description', $destination->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Image Gallery -->
                    <div class="tab-pane fade" id="gallery" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="gallery-input" class="form-label fw-bold"><i class="fa-regular fa-folder-open text-warning me-2"></i>Append Gallery Images</label>
                                    <p class="text-muted small mb-3">Choose one or more photos to append to the active destination gallery. Your old gallery items will be maintained below unless you delete them individually.</p>
                                    <input type="file" class="form-control" id="gallery-input" name="gallery[]" multiple accept="image/*">
                                </div>
                            </div>
                            
                            <!-- Existing & Selected Gallery Grid Preview -->
                            <div class="col-12 my-3">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Active Gallery Images</h6>
                                <div id="gallery-preview-grid" class="row g-3">
                                    @if($destination->gallery && count($destination->gallery) > 0)
                                        @foreach($destination->gallery as $image)
                                            <div class="col-6 col-sm-4 col-md-3 gallery-item-card">
                                                <div class="position-relative border rounded overflow-hidden shadow-sm bg-white" style="height: 110px;">
                                                    <img src="{{ asset('storage/' . $image) }}" alt="Gallery Thumb" class="object-fit-cover w-100 h-100">
                                                    
                                                    <!-- Hidden input to submit existing images -->
                                                    <input type="hidden" name="existing_gallery[]" value="{{ $image }}">
                                                    
                                                    <!-- Delete Button overlay -->
                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-1 d-flex align-items-center justify-content-center remove-gallery-btn" title="Delete Photo" style="width: 24px; height: 24px; font-size: 0.65rem;">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12 text-center py-4 border rounded bg-light" id="gallery-placeholder">
                                            <i class="fa-regular fa-images fs-1 text-muted d-block mb-2"></i>
                                            <span class="text-muted small">No images in this destination's gallery yet. Select files above to upload.</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab 3: SEO Settings & Schema Markup -->
                    <div class="tab-pane fade" id="seo" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_title" class="form-label fw-bold">Meta Title Tag</label>
                                    <input type="text" class="form-control px-3 seo-input" id="seo_title" name="seo_title" value="{{ old('seo_title', $destination->seo_title) }}" placeholder="Optimize for search engine titles..." data-char-counter="seo-title-count" data-max="60">
                                    <div class="text-end small mt-1"><span id="seo-title-count">{{ strlen($destination->seo_title ?? '') }}</span> / 60 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_description" class="form-label fw-bold">Meta Description Tag</label>
                                    <textarea class="form-control px-3 seo-input" id="seo_description" name="seo_description" rows="3" placeholder="Write an engaging meta descriptor snippet..." data-char-counter="seo-desc-count" data-max="160">{{ old('seo_description', $destination->seo_description) }}</textarea>
                                    <div class="text-end small mt-1"><span id="seo-desc-count">{{ strlen($destination->seo_description ?? '') }}</span> / 160 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label fw-bold">Focus SEO Keywords</label>
                                    <input type="text" class="form-control px-3" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $destination->seo_keywords) }}" placeholder="e.g. flights to honolulu, hawaii vacation, honolulu travel guide">
                                </div>
                            </div>

                            <!-- Live Google snippet preview -->
                            <div class="col-12 my-4">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Google SEO Snippet Live Simulator</h6>
                                <div class="p-3 border rounded shadow-sm bg-white" style="font-family: Arial, sans-serif;">
                                    <div class="text-muted" style="font-size: 12px; margin-bottom: 2px;">
                                        https://destinationfareways.com/destinations/<span id="prev-seo-slug" class="font-monospace">{{ $destination->slug ?? 'honolulu' }}</span>
                                    </div>
                                    <h5 id="prev-seo-title" class="text-primary mb-1 fw-normal" style="font-size: 19px; line-height: 1.3; cursor: pointer;">{{ $destination->seo_title ?? ($destination->name ?? 'Honolulu') }}</h5>
                                    <p id="prev-seo-desc" class="text-dark small mb-0" style="font-size: 13px; line-height: 1.4; color: #4d5156 !important;">
                                        {{ $destination->seo_description ?? 'Enter description above to simulate this exact search snippet card rendering in live Google search index pages.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Custom JSON-LD schema block -->
                            <div class="col-12 mt-4 border-top pt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="schema_markup" class="form-label fw-bold mb-0">Custom JSON-LD Schema (optional)</label>
                                    <button type="button" id="generate-dest-schema" class="btn btn-sm btn-outline-primary px-3 rounded-pill" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-magic me-1"></i>Generate Default Place Schema
                                    </button>
                                </div>
                                <textarea class="form-control px-3 font-monospace text-navy" id="schema_markup" name="schema_markup" rows="8" placeholder='<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TouristDestination",
  ...
}
</script>' style="font-size: 0.8rem; background-color: #f8fafc;">{{ old('schema_markup', $destination->schema_markup) }}</textarea>
                                <div id="schema-validation-msg" class="small mt-2 d-none"></div>
                            </div>

                            <!-- Social OG image upload -->
                            <div class="col-12 mt-3">
                                <div class="mb-3">
                                    <label for="og_image" class="form-label fw-bold">OG Share Card Image (1200x630px recommended)</label>
                                    <input type="file" class="form-control image-preview-trigger" id="og_image" name="og_image" data-preview-id="og-preview-box">
                                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                        @if($destination->og_image)
                                            <img src="{{ asset('storage/' . $destination->og_image) }}" id="og-preview-box" alt="OG Preview" class="img-fluid h-100">
                                            <span class="text-muted small py-3 d-none" id="og-preview-placeholder">No image selected</span>
                                        @else
                                            <img src="" id="og-preview-box" alt="OG Preview" class="img-fluid h-100 d-none">
                                            <span class="text-muted small py-3" id="og-preview-placeholder">No image selected</span>
                                        @endif
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
                        <option value="active" {{ $destination->status === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                        <option value="inactive" {{ $destination->status === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="is_domestic" class="form-label fw-bold">Flight Class Category</label>
                    <select class="form-select px-3" id="is_domestic" name="is_domestic" required>
                        <option value="0" {{ !$destination->is_domestic ? 'selected' : '' }}>✈️ International Destination</option>
                        <option value="1" {{ $destination->is_domestic ? 'selected' : '' }}>🏡 Domestic Destination</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="is_featured" class="form-label fw-bold">Featured status</label>
                    <select class="form-select px-3" id="is_featured" name="is_featured" required>
                        <option value="0" {{ !$destination->is_featured ? 'selected' : '' }}>Standard Placement</option>
                        <option value="1" {{ $destination->is_featured ? 'selected' : '' }}>🌟 Promoted / Featured Spotlight</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="is_popular" class="form-label fw-bold">Mark as Highly Popular?</label>
                    <select class="form-select px-3" id="is_popular" name="is_popular" required>
                        <option value="0" {{ !$destination->is_popular ? 'selected' : '' }}>Standard Demand</option>
                        <option value="1" {{ $destination->is_popular ? 'selected' : '' }}>🔥 Trending / High Booking Volume</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label fw-bold">Sort Priority Order</label>
                    <input type="number" class="form-control px-3" id="sort_order" name="sort_order" value="{{ old('sort_order', $destination->sort_order) }}" min="0" required>
                </div>
            </div>

            <!-- Upload Assets -->
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-regular fa-image text-warning me-2"></i>Cover & Banners
                </h5>

                <div class="mb-4">
                    <label for="featured_image" class="form-label fw-bold">Featured Card Image (800x600px)</label>
                    <input type="file" class="form-control image-preview-trigger" id="featured_image" name="featured_image" data-preview-id="cover-preview-box">
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                        @if($destination->featured_image)
                            <img src="{{ asset('storage/' . $destination->featured_image) }}" id="cover-preview-box" alt="Cover Preview" class="img-fluid h-100">
                            <span class="text-muted small py-4 d-none" id="cover-preview-placeholder">No image selected</span>
                        @else
                            <img src="" id="cover-preview-box" alt="Cover Preview" class="img-fluid h-100 d-none">
                            <span class="text-muted small py-4" id="cover-preview-placeholder">No image selected</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label for="banner_image" class="form-label fw-bold">Main Banner Image (1920x600px)</label>
                    <input type="file" class="form-control image-preview-trigger" id="banner_image" name="banner_image" data-preview-id="banner-preview-box">
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 100px;">
                        @if($destination->banner_image)
                            <img src="{{ asset('storage/' . $destination->banner_image) }}" id="banner-preview-box" alt="Banner Preview" class="img-fluid h-100">
                            <span class="text-muted small py-3 d-none" id="banner-preview-placeholder">No image selected</span>
                        @else
                            <img src="" id="banner-preview-box" alt="Banner Preview" class="img-fluid h-100 d-none">
                            <span class="text-muted small py-3" id="banner-preview-placeholder">No image selected</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-3 mt-4 text-end">
        <a href="{{ route('admin.destinations.index') }}" class="btn btn-outline-secondary px-4 rounded-pill me-2">Cancel</a>
        <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle custom slug modifications
        $('#name').on('input', function() {
            if ($('#slug').val() === '') {
                var slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
                $('#slug').val(slug);
                $('#prev-seo-slug').text(slug ? slug : 'honolulu');
            }
            if ($('#seo_title').val() === '') {
                $('#prev-seo-title').text($(this).val());
            }
        });

        $('#slug').on('input', function() {
            var val = $(this).val();
            $('#prev-seo-slug').text(val ? val : 'honolulu');
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
                $('#' + previewId).text(val ? val : ($('#name').val() ? $('#name').val() : 'Honolulu | Cheap Flights'));
            } else {
                $('#' + previewId).text(val ? val : 'Enter description above to simulate this exact search snippet card rendering in live Google search index pages.');
            }
        });

        // Single Image file preview triggers
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
            }
        });

        // Dynamic Removal of Existing Gallery Items on click (without refreshing the page!)
        $(document).on('click', '.remove-gallery-btn', function() {
            var card = $(this).closest('.gallery-item-card');
            
            if (confirm("Are you sure you want to remove this photo? It will be deleted permanently when you save changes.")) {
                card.remove();
                
                // If there are no images left in the grid, add the placeholder
                if ($('#gallery-preview-grid').children().length === 0) {
                    $('#gallery-preview-grid').append(`<div class="col-12 text-center py-4 border rounded bg-light" id="gallery-placeholder">
                                            <i class="fa-regular fa-images fs-1 text-muted d-block mb-2"></i>
                                            <span class="text-muted small">No images in this destination's gallery yet. Select files above to upload.</span>
                                        </div>`);
                }
            }
        });

        // Append Newly Uploaded Gallery Items to the bottom of the grid as previews
        $('#gallery-input').on('change', function() {
            var files = this.files;
            var previewGrid = $('#gallery-preview-grid');
            
            // Remove the placeholder if it is currently displayed
            $('#gallery-placeholder').remove();

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();
                
                reader.onload = (function(theFile) {
                    return function(e) {
                        var html = `
                            <div class="col-6 col-sm-4 col-md-3 new-gallery-upload-card">
                                <div class="position-relative border rounded border-warning overflow-hidden shadow-sm bg-white" style="height: 110px;">
                                    <img src="${e.target.result}" alt="Gallery Thumb" class="object-fit-cover w-100 h-100">
                                    <div class="position-absolute bottom-0 start-0 w-100 p-1 bg-warning text-navy text-truncate font-monospace small fw-bold" style="font-size: 0.65rem; text-align: center;">
                                        [NEW] ${(theFile.size / 1024).toFixed(0)} KB
                                    </div>
                                </div>
                            </div>
                        `;
                        previewGrid.append(html);
                    };
                })(file);
                
                reader.readAsDataURL(file);
            }
        });

        // Place Schema Generator
        $('#generate-dest-schema').on('click', function() {
            var name = $('#name').val() || 'Honolulu';
            var slug = $('#slug').val() || 'honolulu';
            var country = $('#country').val() || 'United States';
            var shortDesc = $('#short_desc').val() || 'Honolulu is a tourist hub.';
            var airport = $('#airport_code').val() || 'HNL';

            var schema = `<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TouristDestination",
  "name": "${name.replace(/"/g, '\\"')}",
  "description": "${shortDesc.replace(/"/g, '\\"')}",
  "url": "https://destinationfareways.com/destinations/${slug}",
  "containedInPlace": {
    "@type": "Place",
    "name": "${country.replace(/"/g, '\\"')}"
  },
  "identifier": "${airport}"
}
<\/script>`;

            $('#schema_markup').val(schema);
            validateSchema(schema);
        });

        // Run validation on load for existing schema
        validateSchema($('#schema_markup').val());

        // Simple Schema XML/JSON Validator
        $('#schema_markup').on('input', function() {
            validateSchema($(this).val());
        });

        function validateSchema(text) {
            var msgEl = $('#schema-validation-msg');
            if (!text || !text.trim()) {
                msgEl.addClass('d-none');
                return;
            }

            msgEl.removeClass('d-none');
            
            // Extract content between <script> tags if present
            var jsonText = text;
            var scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/i;
            var match = scriptRegex.exec(text);
            if (match && match[1]) {
                jsonText = match[1];
            }

            try {
                JSON.parse(jsonText.trim());
                msgEl.removeClass('text-danger').addClass('text-success').html('<i class="fa-solid fa-circle-check me-1"></i> JSON Schema is valid and clean.');
            } catch (e) {
                msgEl.removeClass('text-success').addClass('text-danger').html('<i class="fa-solid fa-triangle-exclamation me-1"></i> JSON Syntax Error: ' + e.message);
            }
        }
    });
</script>
@endsection
