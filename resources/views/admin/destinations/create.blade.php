@extends('layouts.admin')

@section('title', 'Add New Destination')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.destinations.index') }}" class="text-decoration-none text-muted">Destinations</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Add New Destination</h2>
    <p class="text-muted mb-0">Define travel destinations, establish entry-level price indicators, compile beautiful image galleries, and optimize for regional travel queries.</p>
</div>

@if ($errors->any())
<div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4" role="alert">
    <div class="d-flex align-items-start gap-3">
        <i class="fa-solid fa-circle-xmark fs-5 mt-1 text-danger"></i>
        <div>
            <strong class="d-block mb-2">Fix these errors before saving:</strong>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        
    </div>
</div>
@endif

<form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="draft_id" id="draft_id" value="{{ old('draft_id') }}">

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">

                <!-- Tabs Navigation -->
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="destinationTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                            <i class="fa-solid fa-earth-americas me-2"></i>Location Details
                            @if ($errors->hasAny(['name','slug','country','state','airport_code','short_desc','description','starting_price','best_time_to_visit','climate']))
                                <span class="badge bg-danger ms-1" style="font-size:0.6rem;">!</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="gallery-tab" data-bs-toggle="pill" data-bs-target="#gallery" type="button" role="tab">
                            <i class="fa-regular fa-images me-2"></i>Image Gallery
                            @if ($errors->has('gallery.*'))
                                <span class="badge bg-danger ms-1" style="font-size:0.6rem;">!</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>SEO & Socials
                            @if ($errors->hasAny(['seo_title','seo_description','seo_keywords','og_image','schema_markup']))
                                <span class="badge bg-danger ms-1" style="font-size:0.6rem;">!</span>
                            @endif
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="destinationTabContent">

                    <!-- Tab 1: Location Details -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">Destination / City Name</label>
                                    <input type="text" class="form-control px-3 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Honolulu" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label fw-bold">Auto-Generated URL Slug</label>
                                    <input type="text" class="form-control px-3 font-monospace @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. honolulu" style="font-size: 0.85rem;">
                                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="country" class="form-label fw-bold">Country</label>
                                    <input type="text" class="form-control px-3 @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" placeholder="e.g. United States" required>
                                    @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label fw-bold">State / Province (optional)</label>
                                    <input type="text" class="form-control px-3 @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" placeholder="e.g. Hawaii">
                                    @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="airport_code" class="form-label fw-bold">Primary Airport Code</label>
                                    <input type="text" class="form-control px-3 font-monospace @error('airport_code') is-invalid @enderror" id="airport_code" name="airport_code" value="{{ old('airport_code') }}" placeholder="e.g. HNL" style="text-transform: uppercase;">
                                    @error('airport_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="starting_price" class="form-label fw-bold">Starting Price Indicator ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control px-3 @error('starting_price') is-invalid @enderror" id="starting_price" name="starting_price" value="{{ old('starting_price') }}" placeholder="299.00">
                                        @error('starting_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="best_time_to_visit" class="form-label fw-bold">Best Time to Visit</label>
                                    <input type="text" class="form-control px-3 @error('best_time_to_visit') is-invalid @enderror" id="best_time_to_visit" name="best_time_to_visit" value="{{ old('best_time_to_visit') }}" placeholder="e.g. April to October">
                                    @error('best_time_to_visit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="climate" class="form-label fw-bold">Climate Type</label>
                                    <input type="text" class="form-control px-3 @error('climate') is-invalid @enderror" id="climate" name="climate" value="{{ old('climate') }}" placeholder="e.g. Tropical / Marine">
                                    @error('climate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="short_desc" class="form-label fw-bold">Short Introduction Description</label>
                                    <textarea class="form-control px-3 @error('short_desc') is-invalid @enderror" id="short_desc" name="short_desc" rows="3" placeholder="Write a brief summary to show on grid tiles/lists (max 500 chars)...">{{ old('short_desc') }}</textarea>
                                    @error('short_desc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Detailed Destination Information Travel Guide</label>
                                    <textarea class="form-control tinymce-editor @error('description') is-invalid @enderror" id="description" name="description" rows="12">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Image Gallery -->
                    <div class="tab-pane fade" id="gallery" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="gallery-input" class="form-label fw-bold"><i class="fa-regular fa-folder-open text-warning me-2"></i>Select Multiple Gallery Images</label>
                                    <p class="text-muted small mb-3">Upload multiple photos to display in the destination details photo carousel. Each image is automatically converted to WebP for optimal performance.</p>
                                    <input type="file" class="form-control @error('gallery.*') is-invalid @enderror" id="gallery-input" name="gallery[]" multiple accept="image/*">
                                    @error('gallery.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12 my-3">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Selected Gallery Previews</h6>
                                <div id="gallery-preview-grid" class="row g-2">
                                    <div class="col-12 text-center py-4 border rounded bg-light" id="gallery-placeholder">
                                        <i class="fa-regular fa-images fs-1 text-muted d-block mb-2"></i>
                                        <span class="text-muted small">No gallery images chosen yet. Select files above to pre-visualize.</span>
                                    </div>
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
                                    <input type="text" class="form-control px-3 seo-input @error('seo_title') is-invalid @enderror" id="seo_title" name="seo_title" value="{{ old('seo_title') }}" placeholder="Optimize for search engine titles..." data-char-counter="seo-title-count" data-max="60">
                                    @error('seo_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="text-end small mt-1"><span id="seo-title-count">0</span> / 60 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_description" class="form-label fw-bold">Meta Description Tag</label>
                                    <textarea class="form-control px-3 seo-input @error('seo_description') is-invalid @enderror" id="seo_description" name="seo_description" rows="3" placeholder="Write an engaging meta descriptor snippet..." data-char-counter="seo-desc-count" data-max="160">{{ old('seo_description') }}</textarea>
                                    @error('seo_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="text-end small mt-1"><span id="seo-desc-count">0</span> / 160 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label fw-bold">Focus SEO Keywords</label>
                                    <input type="text" class="form-control px-3 @error('seo_keywords') is-invalid @enderror" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords') }}" placeholder="e.g. flights to honolulu, hawaii vacation, honolulu travel guide">
                                    @error('seo_keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Live Google snippet preview -->
                            <div class="col-12 my-4">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Google SEO Snippet Live Simulator</h6>
                                <div class="p-3 border rounded shadow-sm bg-white" style="font-family: Arial, sans-serif;">
                                    <div class="text-muted" style="font-size: 12px; margin-bottom: 2px;">
                                        https://destinationfareways.com/destinations/<span id="prev-seo-slug" class="font-monospace">honolulu</span>
                                    </div>
                                    <h5 id="prev-seo-title" class="text-primary mb-1 fw-normal" style="font-size: 19px; line-height: 1.3; cursor: pointer;">Honolulu | Cheap Flights</h5>
                                    <p id="prev-seo-desc" class="text-dark small mb-0" style="font-size: 13px; line-height: 1.4; color: #4d5156 !important;">
                                        Enter description above to simulate this exact search snippet card rendering in live Google search index pages.
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
                                <textarea class="form-control px-3 font-monospace text-navy @error('schema_markup') is-invalid @enderror" id="schema_markup" name="schema_markup" rows="8" placeholder='<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TouristDestination",
  ...
}
</script>' style="font-size: 0.8rem; background-color: #f8fafc;">{{ old('schema_markup') }}</textarea>
                                @error('schema_markup') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div id="schema-validation-msg" class="small mt-2 d-none"></div>
                            </div>

                            <!-- Social OG image upload -->
                            <div class="col-12 mt-3">
                                <div class="mb-3">
                                    <label for="og_image" class="form-label fw-bold">OG Share Card Image (1200x630px recommended)</label>
                                    <input type="file" class="form-control image-preview-trigger @error('og_image') is-invalid @enderror" id="og_image" name="og_image" data-preview-id="og-preview-box">
                                    @error('og_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
            <div class="card premium-card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-circle-check text-warning me-2"></i>Publish Settings
                </h5>

                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Display Status</label>
                    <select class="form-select px-3 @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Visible)</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="is_domestic" class="form-label fw-bold">Flight Class Category</label>
                    <select class="form-select px-3 @error('is_domestic') is-invalid @enderror" id="is_domestic" name="is_domestic" required>
                        <option value="0" {{ old('is_domestic') == '0' ? 'selected' : '' }}>✈️ International Destination</option>
                        <option value="1" {{ old('is_domestic') == '1' ? 'selected' : '' }}>🏡 Domestic Destination</option>
                    </select>
                    @error('is_domestic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="is_featured" class="form-label fw-bold">Featured Status</label>
                    <select class="form-select px-3 @error('is_featured') is-invalid @enderror" id="is_featured" name="is_featured" required>
                        <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>Standard Placement</option>
                        <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>🌟 Promoted / Featured Spotlight</option>
                    </select>
                    @error('is_featured') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="is_popular" class="form-label fw-bold">Mark as Highly Popular?</label>
                    <select class="form-select px-3 @error('is_popular') is-invalid @enderror" id="is_popular" name="is_popular" required>
                        <option value="0" {{ old('is_popular') == '0' ? 'selected' : '' }}>Standard Demand</option>
                        <option value="1" {{ old('is_popular') == '1' ? 'selected' : '' }}>🔥 Trending / High Booking Volume</option>
                    </select>
                    @error('is_popular') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label fw-bold">Sort Priority Order</label>
                    <input type="number" class="form-control px-3 @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required>
                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-regular fa-image text-warning me-2"></i>Cover & Banners
                </h5>

                <div class="mb-4">
                    <label for="featured_image" class="form-label fw-bold">Featured Card Image (800x600px)</label>
                    <input type="file" class="form-control image-preview-trigger @error('featured_image') is-invalid @enderror" id="featured_image" name="featured_image" data-preview-id="cover-preview-box">
                    @error('featured_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                        <img src="" id="cover-preview-box" alt="Cover Preview" class="img-fluid h-100 d-none">
                        <span class="text-muted small py-4" id="cover-preview-placeholder">No image selected</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="banner_image" class="form-label fw-bold">Main Banner Image (1920x600px)</label>
                    <input type="file" class="form-control image-preview-trigger @error('banner_image') is-invalid @enderror" id="banner_image" name="banner_image" data-preview-id="banner-preview-box">
                    @error('banner_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 100px;">
                        <img src="" id="banner-preview-box" alt="Banner Preview" class="img-fluid h-100 d-none">
                        <span class="text-muted small py-3" id="banner-preview-placeholder">No image selected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-3 mt-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div id="autosave-status" class="text-muted small d-flex align-items-center gap-2" style="min-width: 180px;">
            <span id="autosave-dot" class="d-inline-block rounded-circle" style="width:8px;height:8px;background:#ccc;"></span>
            <span id="autosave-msg">Not saved yet</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.destinations.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
            <button type="submit" id="publish-btn" class="btn btn-action px-5">
                <i class="fa-solid fa-floppy-disk me-2"></i>Save Destination
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function () {

    /* ── Constants ─────────────────────────────────────── */
    const LS_KEY   = 'destination_create_draft';
    const SAVE_URL = '{{ route('admin.destinations.autosave') }}';
    const CSRF     = '{{ csrf_token() }}';

    const TEXT_FIELDS = [
        'name', 'slug', 'country', 'state', 'airport_code',
        'short_desc', 'description', 'starting_price', 'best_time_to_visit', 'climate',
        'status', 'is_domestic', 'is_featured', 'is_popular', 'sort_order',
        'seo_title', 'seo_description', 'seo_keywords', 'schema_markup'
    ];

    /* ── Status indicator ───────────────────────────────── */
    function setStatus(msg, color) {
        $('#autosave-msg').text(msg);
        $('#autosave-dot').css('background', color);
    }

    var formSubmitting = false;

    /* ── Switch to tab with errors on page load ─────────── */
    @if ($errors->any())
        @if ($errors->hasAny(['seo_title','seo_description','seo_keywords','og_image','schema_markup']))
            $('#seo-tab').tab('show');
        @elseif ($errors->has('gallery.*'))
            $('#gallery-tab').tab('show');
        @else
            $('#general-tab').tab('show');
        @endif
    @endif

    /* ── localStorage helpers ───────────────────────────── */
    function saveLocal() {
        if (formSubmitting) return;
        var d = {};
        TEXT_FIELDS.forEach(function (f) {
            var el = document.getElementById(f);
            if (!el) return;
            d[f] = el.value;
        });
        if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
            d['description'] = tinymce.get('description').getContent();
        }
        var did = $('#draft_id').val();
        if (did) d['_draft_id'] = did;
        localStorage.setItem(LS_KEY, JSON.stringify(d));
    }

    function restoreLocal() {
        var raw = localStorage.getItem(LS_KEY);
        if (!raw) return;
        try {
            var d = JSON.parse(raw);
            TEXT_FIELDS.forEach(function (f) {
                if (f === 'description') return;
                var el = document.getElementById(f);
                if (!el || d[f] === undefined) return;
                el.value = d[f];
            });
            if (d['_draft_id']) $('#draft_id').val(d['_draft_id']);

            var attempts = 0;
            var ti = setInterval(function () {
                if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
                    if (d['description']) tinymce.get('description').setContent(d['description']);
                    clearInterval(ti);
                }
                if (++attempts > 40) clearInterval(ti);
            }, 250);

            setStatus('Draft restored', '#f0a500');
        } catch (e) {}
    }

    function clearLocal() {
        localStorage.removeItem(LS_KEY);
    }

    /* ── AJAX save to DB ─────────────────────────────────── */
    var ajaxSaving = false;
    var activeXhr  = null;

    function ajaxSave() {
        if (ajaxSaving || formSubmitting) return;
        ajaxSaving = true;
        setStatus('Saving…', '#6c757d');

        if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
            tinymce.get('description').save();
        }

        var payload = { _token: CSRF };
        TEXT_FIELDS.forEach(function (f) {
            var el = document.getElementById(f);
            if (el) payload[f] = el.value;
        });

        var did = $('#draft_id').val();
        if (did) payload['draft_id'] = did;

        activeXhr = $.post(SAVE_URL, payload)
            .done(function (res) {
                if (res.success) {
                    $('#draft_id').val(res.id);
                    saveLocal();
                    setStatus('Draft saved ✓', '#198754');
                } else {
                    setStatus(res.message || 'Not saved yet', '#f0a500');
                }
            })
            .fail(function (xhr) {
                if (xhr.statusText !== 'abort') {
                    setStatus('Auto-save failed', '#dc3545');
                }
            })
            .always(function () {
                ajaxSaving = false;
                activeXhr  = null;
            });
    }

    /* ── Debounced auto-save on any field change ─────────── */
    var debounceTimer;
    function scheduleAutosave() {
        saveLocal();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            if ($('#name').val().trim() !== '') ajaxSave();
        }, 3000);
    }

    $('form').on('input change', 'input:not([type=file]), textarea, select', function () {
        scheduleAutosave();
    });

    var tinyHooked = false;
    var hookTimer = setInterval(function () {
        if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
            if (!tinyHooked) {
                tinymce.get('description').on('input keyup change', function () {
                    scheduleAutosave();
                });
                tinyHooked = true;
            }
            clearInterval(hookTimer);
        }
    }, 500);

    /* ── Form submit: lock saves, wipe draft, sync TinyMCE ── */
    $('form').first().on('submit', function () {
        formSubmitting = true;
        clearTimeout(debounceTimer);
        if (activeXhr) { activeXhr.abort(); activeXhr = null; }
        clearLocal();
        if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
            tinymce.get('description').save();
        }
    });

    /* ── Slug generation ─────────────────────────────────── */
    $('#name').on('input', function () {
        var slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        $('#slug').val(slug);
        $('#prev-seo-slug').text(slug || 'honolulu');
        if ($('#seo_title').val() === '') $('#prev-seo-title').text($(this).val());
    });

    $('#slug').on('input', function () {
        $('#prev-seo-slug').text($(this).val() || 'honolulu');
    });

    /* ── SEO character counters & live preview ───────────── */
    $('.seo-input').on('input', function () {
        var val    = $(this).val();
        var cid    = $(this).data('char-counter');
        var maxLen = $(this).data('max');
        var prevId = cid.includes('title') ? 'prev-seo-title' : 'prev-seo-desc';
        $('#' + cid).text(val.length)
            .toggleClass('text-danger', val.length > maxLen)
            .toggleClass('text-success', val.length <= maxLen);
        if (cid.includes('title')) {
            $('#' + prevId).text(val || ($('#name').val() || 'Honolulu | Cheap Flights'));
        } else {
            $('#' + prevId).text(val || 'Enter description above to simulate this exact search snippet card rendering in live Google search index pages.');
        }
    });

    /* ── WebP conversion helpers ─────────────────────────── */
    var IMG_CONFIG = {
        'featured_image' : { w: 800,  h: 600,  q: 0.82 },
        'banner_image'   : { w: 1920, h: 600,  q: 0.80 },
        'og_image'       : { w: 1200, h: 630,  q: 0.82 },
    };
    var GALLERY_CFG = { w: 1200, h: 900, q: 0.82 };

    var CAN_WEBP = (function () {
        try {
            var c = document.createElement('canvas');
            c.width = c.height = 1;
            return c.toDataURL('image/webp').indexOf('data:image/webp') === 0;
        } catch (e) { return false; }
    })();

    function fmtBytes(b) {
        return b < 1048576 ? (b / 1024).toFixed(0) + ' KB' : (b / 1048576).toFixed(2) + ' MB';
    }

    function toWebPBlob(file, cfg, cb) {
        var objUrl = URL.createObjectURL(file);
        var img    = new Image();
        img.onload = function () {
            URL.revokeObjectURL(objUrl);
            var w = img.naturalWidth, h = img.naturalHeight;
            if (w > cfg.w) { h = Math.round(h * cfg.w / w); w = cfg.w; }
            if (h > cfg.h) { w = Math.round(w * cfg.h / h); h = cfg.h; }
            var canvas = document.createElement('canvas');
            canvas.width = w; canvas.height = h;
            canvas.getContext('2d').drawImage(img, 0, 0, w, h);
            canvas.toBlob(function (blob) {
                cb((blob && blob.type === 'image/webp' && blob.size > 0) ? blob : null);
            }, 'image/webp', cfg.q);
        };
        img.onerror = function () { URL.revokeObjectURL(objUrl); cb(null); };
        img.src = objUrl;
    }

    function replaceInputFile(input, file) {
        try {
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            return input.files[0] && input.files[0].type === 'image/webp';
        } catch (e) { return false; }
    }

    function renderPreview(previewId, src, infoHtml) {
        var ph     = previewId.replace('-box', '-placeholder');
        var infoId = previewId + '-info';
        $('#' + previewId).attr('src', src).removeClass('d-none');
        $('#' + ph).addClass('d-none').text('No image selected');
        if (!$('#' + infoId).length) {
            $('#' + previewId).closest('.mt-2').after('<div id="' + infoId + '" class="small mt-1 ps-1"></div>');
        }
        $('#' + infoId).html(infoHtml);
    }

    /* ── Single image upload: compress → WebP → preview ──── */
    $('.image-preview-trigger').on('change', function () {
        var input     = this;
        var file      = input.files[0];
        var previewId = $(this).data('preview-id');
        var ph        = previewId.replace('-box', '-placeholder');
        var cfg       = IMG_CONFIG[$(this).attr('id')] || { w: 1200, h: 900, q: 0.82 };

        $('#' + previewId).addClass('d-none').attr('src', '');
        $('#' + previewId + '-info').remove();
        $('#' + ph).text('No image selected').removeClass('d-none');

        if (!file) return;

        if (file.size > 10 * 1024 * 1024) {
            alert('File is too large (max 10 MB). Please choose a smaller image.');
            input.value = '';
            return;
        }

        if (file.type === 'image/gif' || !CAN_WEBP) {
            var gifReader = new FileReader();
            gifReader.onload = function (e) {
                var label = !CAN_WEBP
                    ? '<span class="text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>WebP not supported in this browser – original kept (' + fmtBytes(file.size) + ')</span>'
                    : '<span class="text-muted">GIF – kept as original (' + fmtBytes(file.size) + ')</span>';
                renderPreview(previewId, e.target.result, label);
            };
            gifReader.readAsDataURL(file);
            return;
        }

        $('#' + ph).html('<span class="spinner-border spinner-border-sm text-primary me-1"></span><span class="text-muted">Converting to WebP…</span>');

        toWebPBlob(file, cfg, function (blob) {
            if (!blob) {
                var fr = new FileReader();
                fr.onload = function (e) {
                    renderPreview(previewId, e.target.result,
                        '<span class="text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>Conversion failed – original kept (' + fmtBytes(file.size) + ')</span>');
                };
                fr.readAsDataURL(file);
                return;
            }
            var webpFile   = new File([blob], file.name.replace(/\.[^.]+$/, '') + '.webp', { type: 'image/webp', lastModified: Date.now() });
            var replaced   = replaceInputFile(input, webpFile);
            var previewUrl = URL.createObjectURL(blob);
            var pct        = Math.round(Math.max(0, file.size - blob.size) / file.size * 100);
            var badge = replaced
                ? '<span class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>WebP · ' + fmtBytes(blob.size) + (pct > 0 ? ' <span class="badge bg-success ms-1">−' + pct + '%</span>' : '') + '</span>'
                : '<span class="text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>Converted but replace unsupported – original will upload (' + fmtBytes(file.size) + ')</span>';
            renderPreview(previewId, previewUrl, badge);
        });
    });

    /* ── Gallery multi-upload: convert each to WebP + preview grid ── */
    var galleryDataTransfer = new DataTransfer();

    $('#gallery-input').on('change', function () {
        var files       = Array.from(this.files);
        var previewGrid = $('#gallery-preview-grid');

        previewGrid.empty();

        if (files.length === 0) {
            previewGrid.append(`<div class="col-12 text-center py-4 border rounded bg-light" id="gallery-placeholder">
                <i class="fa-regular fa-images fs-1 text-muted d-block mb-2"></i>
                <span class="text-muted small">No gallery images chosen yet. Select files above to pre-visualize.</span>
            </div>`);
            return;
        }

        if (!CAN_WEBP) {
            // Fallback: show raw previews without conversion
            files.forEach(function (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    previewGrid.append(`<div class="col-6 col-sm-4 col-md-3">
                        <div class="position-relative border rounded overflow-hidden shadow-sm bg-white" style="height:100px;">
                            <img src="${e.target.result}" class="object-fit-cover w-100 h-100" alt="">
                            <div class="position-absolute bottom-0 start-0 w-100 p-1 bg-dark bg-opacity-70 text-white text-truncate font-monospace" style="font-size:0.65rem;">${fmtBytes(file.size)}</div>
                        </div></div>`);
                };
                reader.readAsDataURL(file);
            });
            return;
        }

        // Show spinner placeholder
        previewGrid.append('<div class="col-12 text-center py-3 text-muted small" id="gallery-converting"><span class="spinner-border spinner-border-sm me-2"></span>Converting images to WebP…</div>');

        galleryDataTransfer = new DataTransfer();
        var input = this;
        var done  = 0;

        files.forEach(function (file, idx) {
            toWebPBlob(file, GALLERY_CFG, function (blob) {
                done++;

                if (blob) {
                    var webpFile = new File([blob], file.name.replace(/\.[^.]+$/, '') + '.webp', { type: 'image/webp', lastModified: Date.now() });
                    galleryDataTransfer.items.add(webpFile);
                    var previewUrl = URL.createObjectURL(blob);
                    var pct        = Math.round(Math.max(0, file.size - blob.size) / file.size * 100);
                    previewGrid.append(`<div class="col-6 col-sm-4 col-md-3">
                        <div class="position-relative border rounded overflow-hidden shadow-sm bg-white" style="height:100px;">
                            <img src="${previewUrl}" class="object-fit-cover w-100 h-100" alt="">
                            <div class="position-absolute bottom-0 start-0 w-100 p-1 bg-dark bg-opacity-70 text-white text-truncate font-monospace" style="font-size:0.65rem;">
                                WebP · ${fmtBytes(blob.size)}${pct > 0 ? ' <span class=\'badge bg-success ms-1\'>−' + pct + '%</span>' : ''}
                            </div>
                        </div></div>`);
                } else {
                    // Conversion failed – keep original
                    galleryDataTransfer.items.add(file);
                    var fr = new FileReader();
                    fr.onload = function (e) {
                        previewGrid.append(`<div class="col-6 col-sm-4 col-md-3">
                            <div class="position-relative border rounded overflow-hidden shadow-sm bg-white" style="height:100px;">
                                <img src="${e.target.result}" class="object-fit-cover w-100 h-100" alt="">
                                <div class="position-absolute bottom-0 start-0 w-100 p-1 bg-warning bg-opacity-80 text-dark text-truncate font-monospace" style="font-size:0.65rem;">Original · ${fmtBytes(file.size)}</div>
                            </div></div>`);
                    };
                    fr.readAsDataURL(file);
                }

                if (done === files.length) {
                    $('#gallery-converting').remove();
                    // Replace file input files with converted WebP files
                    try { input.files = galleryDataTransfer.files; } catch (e) {}
                }
            });
        });
    });

    /* ── Schema generator ────────────────────────────────── */
    $('#generate-dest-schema').on('click', function () {
        var name      = $('#name').val()       || 'Honolulu';
        var slug      = $('#slug').val()       || 'honolulu';
        var country   = $('#country').val()    || 'United States';
        var shortDesc = $('#short_desc').val() || 'Honolulu is a tourist hub.';
        var airport   = $('#airport_code').val() || 'HNL';

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

    $('#schema_markup').on('input', function () { validateSchema($(this).val()); });

    function validateSchema(text) {
        var msgEl = $('#schema-validation-msg');
        if (!text.trim()) { msgEl.addClass('d-none'); return; }
        msgEl.removeClass('d-none');
        var jsonText = text;
        var match = /<script\b[^>]*>([\s\S]*?)<\/script>/i.exec(text);
        if (match && match[1]) jsonText = match[1];
        try {
            JSON.parse(jsonText.trim());
            msgEl.removeClass('text-danger').addClass('text-success').html('<i class="fa-solid fa-circle-check me-1"></i> JSON Schema is valid and clean.');
        } catch (e) {
            msgEl.removeClass('text-success').addClass('text-danger').html('<i class="fa-solid fa-triangle-exclamation me-1"></i> JSON Syntax Error: ' + e.message);
        }
    }

    /* ── Restore draft on page load ──────────────────────── */
    restoreLocal();
});
</script>
@endsection
