@extends('layouts.admin')

@section('title', 'Edit Flight Route')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<style>
    .select2-container--bootstrap-5 .select2-selection { min-height: 42px; border-color: #dee2e6; }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered { line-height: 40px; padding-left: 12px; font-family: monospace; }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder { line-height: 40px; }
    #ai-status-msg { display:none; }
</style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.flight-routes.index') }}" class="text-decoration-none text-muted">Flight Routes</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Edit Flight Route</h2>
    <p class="text-muted mb-0">{{ $flightRoute->origin_city }} → {{ $flightRoute->destination_city }}</p>
</div>

<form action="{{ route('admin.flight-routes.update', $flightRoute->id) }}" method="POST" enctype="multipart/form-data" id="route-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">

                <!-- Tabs -->
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="routeTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active px-4 py-2" data-bs-toggle="pill" data-bs-target="#general" type="button">
                            <i class="fa-solid fa-plane me-2"></i>Route Details
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-4 py-2" data-bs-toggle="pill" data-bs-target="#faq" type="button">
                            <i class="fa-solid fa-circle-question me-2"></i>FAQs
                            @if($flightRoute->faq_schema)
                                @php $faqCount = count(json_decode($flightRoute->faq_schema, true) ?? []); @endphp
                                @if($faqCount > 0)
                                    <span class="badge bg-gold text-navy ms-1" style="background-color:#F59E0B !important;font-size:0.65rem;">{{ $faqCount }}</span>
                                @endif
                            @endif
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-4 py-2" data-bs-toggle="pill" data-bs-target="#seo" type="button">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>SEO & Socials
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-4 py-2" data-bs-toggle="pill" data-bs-target="#schema" type="button">
                            <i class="fa-solid fa-code me-2"></i>Schema Markup
                        </button>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- ── Tab 1: Route Details ── --}}
                    <div class="tab-pane fade show active" id="general">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-bold">Route Title</label>
                                <input type="text" class="form-control px-3" id="title" name="title" value="{{ old('title', $flightRoute->title) }}" required>
                                <div class="mt-2">
                                    <button type="button" id="btn-generate-ai" class="btn btn-sm btn-warning fw-semibold px-4">
                                        <i class="fa-solid fa-wand-magic-sparkles me-2"></i>Generate AI Content
                                    </button>
                                    <span id="ai-status-msg" class="ms-3 text-muted small">
                                        <span class="spinner-border spinner-border-sm me-1"></span>Generating AI content...
                                    </span>
                                    <span id="ai-success-msg" class="ms-3 text-success small d-none">
                                        <i class="fa-solid fa-circle-check me-1"></i>AI content generated successfully.
                                    </span>
                                    <span id="ai-error-msg" class="ms-3 text-danger small d-none"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">URL Slug</label>
                                <input type="text" class="form-control px-3 font-monospace" id="slug" name="slug" value="{{ old('slug', $flightRoute->slug) }}" style="font-size:0.85rem;" required>
                            </div>

                            <div class="col-12 border rounded p-3 bg-light bg-opacity-50 my-2">
                                <h6 class="fw-bold text-navy mb-3"><i class="fa-solid fa-route me-2 text-warning"></i>Route Corridor</h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Origin Airport <span class="text-danger">*</span></label>
                                        <select id="origin_airport_select" class="form-select">
                                            <option value="">— Select Origin Airport —</option>
                                            @foreach($airports as $airport)
                                                <option value="{{ $airport->iata_code }}"
                                                    data-city="{{ $airport->city }}"
                                                    data-country="USA"
                                                    {{ old('origin_airport_code', $flightRoute->origin_airport_code) === $airport->iata_code ? 'selected' : '' }}>
                                                    {{ $airport->iata_code }} – {{ $airport->city }} – {{ $airport->airport_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Destination Airport <span class="text-danger">*</span></label>
                                        <select id="destination_airport_select" class="form-select">
                                            <option value="">— Select Destination Airport —</option>
                                            @foreach($airports as $airport)
                                                <option value="{{ $airport->iata_code }}"
                                                    data-city="{{ $airport->city }}"
                                                    data-country="USA"
                                                    {{ old('destination_airport_code', $flightRoute->destination_airport_code) === $airport->iata_code ? 'selected' : '' }}>
                                                    {{ $airport->iata_code }} – {{ $airport->city }} – {{ $airport->airport_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- Hidden fields submitted with form --}}
                                <input type="hidden" name="origin_city"              id="h_origin_city"    value="{{ old('origin_city', $flightRoute->origin_city) }}">
                                <input type="hidden" name="origin_airport_code"      id="h_origin_iata"    value="{{ old('origin_airport_code', $flightRoute->origin_airport_code) }}">
                                <input type="hidden" name="origin_country"           id="h_origin_country" value="{{ old('origin_country', $flightRoute->origin_country ?? 'USA') }}">
                                <input type="hidden" name="destination_city"         id="h_dest_city"      value="{{ old('destination_city', $flightRoute->destination_city) }}">
                                <input type="hidden" name="destination_airport_code" id="h_dest_iata"      value="{{ old('destination_airport_code', $flightRoute->destination_airport_code) }}">
                                <input type="hidden" name="destination_country"      id="h_dest_country"   value="{{ old('destination_country', $flightRoute->destination_country ?? 'USA') }}">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold">Starting Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control px-3" name="starting_price" value="{{ old('starting_price', $flightRoute->starting_price) }}" min="0" step="1">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold">Flight Duration</label>
                                <input type="text" class="form-control px-3" name="flight_duration" value="{{ old('flight_duration', $flightRoute->flight_duration) }}" placeholder="e.g. 5h 30m">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold">Frequency</label>
                                <input type="text" class="form-control px-3" name="frequency" value="{{ old('frequency', $flightRoute->frequency) }}" placeholder="e.g. Multiple daily flights">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Airlines</label>
                                <input type="text" class="form-control px-3" name="airlines" value="{{ old('airlines', $flightRoute->airlines) }}" placeholder="e.g. Delta, United, American Airlines">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Short Description</label>
                                <textarea class="form-control px-3" name="short_desc" rows="3">{{ old('short_desc', $flightRoute->short_desc) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Full Route Description</label>
                                <textarea class="form-control tinymce-editor" name="description" rows="10">{{ old('description', $flightRoute->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- ── Tab 2: FAQs ── --}}
                    <div class="tab-pane fade" id="faq">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold text-navy mb-0">Frequently Asked Questions</h6>
                                <p class="text-muted small mb-0">FAQs appear on the route page and generate FAQ schema automatically.</p>
                            </div>
                            <button type="button" id="add-faq-row" class="btn btn-sm btn-action rounded-pill px-3">
                                <i class="fa-solid fa-plus me-1"></i>Add Question
                            </button>
                        </div>

                        <input type="hidden" name="faq_schema" id="faq-json-input">

                        <div id="faq-rows" class="d-flex flex-column gap-3"></div>

                        <div id="faq-empty-msg" class="text-center py-5 text-muted border rounded-3 bg-light" style="display:none !important;">
                            <i class="fa-solid fa-circle-question fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">No FAQs yet. Click "Add Question" to start.</p>
                        </div>
                    </div>

                    {{-- ── Tab 3: SEO ── --}}
                    <div class="tab-pane fade" id="seo">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Meta Title</label>
                                <input type="text" class="form-control px-3 seo-input" id="seo_title" name="seo_title"
                                       value="{{ old('seo_title', $flightRoute->seo_title) }}"
                                       data-char-counter="seo-title-count" data-max="60">
                                <div class="text-end small mt-1"><span id="seo-title-count">0</span> / 60 characters</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Meta Description</label>
                                <textarea class="form-control px-3 seo-input" id="seo_desc" name="seo_description"
                                          rows="3" data-char-counter="seo-desc-count" data-max="160">{{ old('seo_description', $flightRoute->seo_description) }}</textarea>
                                <div class="text-end small mt-1"><span id="seo-desc-count">0</span> / 160 characters</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Focus Keywords</label>
                                <input type="text" class="form-control px-3" name="seo_keywords" value="{{ old('seo_keywords', $flightRoute->seo_keywords) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Canonical URL (optional)</label>
                                <input type="url" class="form-control px-3" name="canonical_url" value="{{ old('canonical_url', $flightRoute->canonical_url) }}">
                            </div>

                            <!-- Google Snippet Simulator — pre-populated on page load -->
                            <div class="col-12 my-3">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Google SEO Snippet Simulator</h6>
                                <div class="p-3 border rounded shadow-sm bg-white" style="font-family: Arial, sans-serif;">
                                    <div class="text-muted" style="font-size: 12px; margin-bottom: 2px;">
                                        https://destinationfareways.com/flight-routes/<span id="prev-slug" class="font-monospace">{{ $flightRoute->slug }}</span>
                                    </div>
                                    <h5 id="prev-title" class="text-primary mb-1 fw-normal" style="font-size: 19px; line-height: 1.3;">
                                        {{ $flightRoute->seo_title ?: $flightRoute->title }}
                                    </h5>
                                    <p id="prev-desc" class="text-dark small mb-0" style="font-size: 13px; line-height: 1.4; color: #4d5156 !important;">
                                        {{ $flightRoute->seo_description ?: 'Enter a meta description above to preview how this route will appear in Google search results.' }}
                                    </p>
                                </div>
                            </div>

                            @if($flightRoute->og_image)
                            <div class="col-12">
                                <label class="form-label fw-bold">Current OG Image</label>
                                <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height:120px;">
                                    <img src="{{ asset('storage/' . $flightRoute->og_image) }}" alt="OG" class="img-fluid h-100 object-fit-cover rounded">
                                </div>
                                <p class="small text-muted mt-1">Upload a new image below to replace.</p>
                            </div>
                            @endif
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ $flightRoute->og_image ? 'Replace OG Image' : 'OG Share Image (1200×630px)' }}</label>
                                <input type="file" class="form-control image-preview-trigger" name="og_image" data-preview-id="og-preview-box">
                                <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center d-none" style="height:120px;" id="og-preview-wrapper">
                                    <img src="" id="og-preview-box" alt="" class="img-fluid h-100">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Tab 4: Schema ── --}}
                    <div class="tab-pane fade" id="schema">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="alert alert-info border-0 py-2 px-3 small">
                                    <i class="fa-solid fa-circle-info me-1"></i>
                                    FAQ Schema is generated automatically from the FAQ tab. Use this field for additional custom JSON-LD only.
                                </div>
                                <label class="form-label fw-bold">Custom JSON-LD Schema</label>
                                <textarea class="form-control px-3 font-monospace text-navy" name="schema_markup" rows="12" style="font-size:0.8rem;background:#f8fafc;">{{ old('schema_markup', $flightRoute->schema_markup) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-12 col-xl-4">

            <div class="card premium-card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2"><i class="fa-solid fa-circle-check text-warning me-2"></i>Publish Settings</h5>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select class="form-select px-3" name="status" required>
                        <option value="active" @selected(old('status', $flightRoute->status) === 'active')>Active (Visible)</option>
                        <option value="inactive" @selected(old('status', $flightRoute->status) === 'inactive')>Inactive / Hidden</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Route Type</label>
                    <select class="form-select px-3" name="is_domestic" required>
                        <option value="1" @selected(old('is_domestic', $flightRoute->is_domestic) == '1')>Domestic (USA)</option>
                        <option value="0" @selected(old('is_domestic', $flightRoute->is_domestic) == '0')>International</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Featured?</label>
                    <select class="form-select px-3" name="is_featured" required>
                        <option value="0" @selected(!old('is_featured', $flightRoute->is_featured))>Standard</option>
                        <option value="1" @selected(old('is_featured', $flightRoute->is_featured))>⭐ Featured Route</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Popular?</label>
                    <select class="form-select px-3" name="is_popular" required>
                        <option value="0" @selected(!old('is_popular', $flightRoute->is_popular))>Normal</option>
                        <option value="1" @selected(old('is_popular', $flightRoute->is_popular))>🔥 Popular Route</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Sort Order</label>
                    <input type="number" class="form-control px-3" name="sort_order" value="{{ old('sort_order', $flightRoute->sort_order) }}" min="0">
                </div>
            </div>

            <!-- Images -->
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2"><i class="fa-regular fa-image text-warning me-2"></i>Route Images</h5>

                @if($flightRoute->featured_image)
                <div class="mb-3">
                    <p class="form-label fw-semibold mb-1">Current Cover</p>
                    <img src="{{ asset('storage/' . $flightRoute->featured_image) }}" alt="Cover" class="img-fluid rounded shadow-sm" style="max-height:120px;object-fit:cover;width:100%;">
                </div>
                @endif
                <div class="mb-4">
                    <label class="form-label fw-bold">{{ $flightRoute->featured_image ? 'Replace Cover' : 'Card Cover Image (800×600px)' }}</label>
                    <input type="file" class="form-control image-preview-trigger" name="featured_image" data-preview-id="cover-preview-box">
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center d-none" style="height:120px;" id="cover-preview-wrapper">
                        <img src="" id="cover-preview-box" alt="" class="img-fluid h-100">
                    </div>
                </div>

                @if($flightRoute->banner_image)
                <div class="mb-3">
                    <p class="form-label fw-semibold mb-1">Current Banner</p>
                    <img src="{{ asset('storage/' . $flightRoute->banner_image) }}" alt="Banner" class="img-fluid rounded shadow-sm" style="max-height:80px;object-fit:cover;width:100%;">
                </div>
                @endif
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ $flightRoute->banner_image ? 'Replace Banner' : 'Page Banner (1920×600px)' }}</label>
                    <input type="file" class="form-control image-preview-trigger" name="banner_image" data-preview-id="banner-preview-box">
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center d-none" style="height:100px;" id="banner-preview-wrapper">
                        <img src="" id="banner-preview-box" alt="" class="img-fluid h-100">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-3 mt-4 text-end">
        <a href="{{ route('admin.flight-routes.index') }}" class="btn btn-outline-secondary px-4 rounded-pill me-2">Cancel</a>
        <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Update Route</button>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {

    // ── Select2 Airport Dropdowns ──────────────────────────────────
    $('#origin_airport_select, #destination_airport_select').select2({
        theme: 'bootstrap-5',
        placeholder: '— Search airport by code or city —',
        allowClear: true,
        width: '100%',
    });

    function fillOrigin(iata, city, country) {
        $('#h_origin_iata').val(iata);
        $('#h_origin_city').val(city);
        $('#h_origin_country').val(country || 'USA');
    }

    function fillDestination(iata, city, country) {
        $('#h_dest_iata').val(iata);
        $('#h_dest_city').val(city);
        $('#h_dest_country').val(country || 'USA');
    }

    $('#origin_airport_select').on('change', function () {
        var opt = $(this).find(':selected');
        fillOrigin(opt.val(), opt.data('city'), opt.data('country'));
    });

    $('#destination_airport_select').on('change', function () {
        var opt = $(this).find(':selected');
        fillDestination(opt.val(), opt.data('city'), opt.data('country'));
    });

    // Pre-fill hidden fields from currently selected options (edit mode)
    (function () {
        var oOpt = $('#origin_airport_select').find(':selected');
        if (oOpt.val()) fillOrigin(oOpt.val(), oOpt.data('city'), oOpt.data('country'));
        var dOpt = $('#destination_airport_select').find(':selected');
        if (dOpt.val()) fillDestination(dOpt.val(), dOpt.data('city'), dOpt.data('country'));
    })();

    // ── SEO counters + live preview ────────────────────────────────
    function updateCounter(input) {
        var val = input.val();
        var counterId = input.data('char-counter');
        var maxLen    = input.data('max');
        $('#' + counterId).text(val.length)
            .toggleClass('text-danger', val.length > maxLen)
            .toggleClass('text-success', val.length <= maxLen && val.length > 0);
        if (counterId === 'seo-title-count') {
            $('#prev-title').text(val || $('#title').val() || '{{ addslashes($flightRoute->title) }}');
        } else {
            $('#prev-desc').text(val || 'Enter a meta description above to preview.');
        }
    }

    $('.seo-input').each(function () { updateCounter($(this)); });
    $('.seo-input').on('input', function () { updateCounter($(this)); });

    $('#slug').on('input', function () {
        $('#prev-slug').text($(this).val() || '{{ $flightRoute->slug }}');
    });
    $('#title').on('input', function () {
        if ($('#seo_title').val() === '') {
            $('#prev-title').text($(this).val() || '{{ addslashes($flightRoute->title) }}');
        }
    });

    // ── Image previews ─────────────────────────────────────────────
    $('.image-preview-trigger').on('change', function () {
        var previewId = $(this).data('preview-id');
        var wrapperId = previewId.replace('-box', '-wrapper');
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + previewId).attr('src', e.target.result);
                $('#' + wrapperId).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // ── FAQ Manager ────────────────────────────────────────────────
    var faqs = @json(json_decode($flightRoute->faq_schema) ?? []);
    if (!Array.isArray(faqs)) { faqs = []; }
    faqs = faqs.map(function (f) {
        return { question: f.question || '', answer: f.answer || '' };
    });

    function renderFaqs() {
        var $rows = $('#faq-rows');
        $rows.empty();
        if (faqs.length === 0) { $('#faq-empty-msg').show(); } else { $('#faq-empty-msg').hide(); }
        faqs.forEach(function (faq, index) {
            var row = `
            <div class="faq-row card border-0 shadow-sm p-3 rounded-3" data-index="${index}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-navy small"><i class="fa-solid fa-circle-question text-gold me-2"></i>Question #${index + 1}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0 remove-faq" data-index="${index}">
                        <i class="fa-solid fa-trash-can" style="font-size:0.75rem;"></i>
                    </button>
                </div>
                <div class="mb-2">
                    <input type="text" class="form-control px-3 faq-question" data-index="${index}"
                           value="${escHtml(faq.question)}" placeholder="e.g. What airlines fly this route?" style="font-size:0.88rem;">
                </div>
                <div>
                    <textarea class="form-control px-3 faq-answer" data-index="${index}" rows="3"
                              placeholder="Provide a clear, helpful answer..." style="font-size:0.88rem;">${escHtml(faq.answer)}</textarea>
                </div>
            </div>`;
            $rows.append(row);
        });
        syncFaqJson();
    }

    function escHtml(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function syncFaqJson() {
        $('#faq-json-input').val(faqs.length ? JSON.stringify(faqs) : '');
    }

    $('#add-faq-row').on('click', function () {
        faqs.push({ question: '', answer: '' });
        renderFaqs();
        $('#faq-rows .faq-row:last')[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    $(document).on('input', '.faq-question', function () {
        faqs[$(this).data('index')].question = $(this).val();
        syncFaqJson();
    });

    $(document).on('input', '.faq-answer', function () {
        faqs[$(this).data('index')].answer = $(this).val();
        syncFaqJson();
    });

    $(document).on('click', '.remove-faq', function () {
        faqs.splice($(this).data('index'), 1);
        renderFaqs();
    });

    $('#route-form').on('submit', function () { syncFaqJson(); });

    renderFaqs();

    // ── Generate AI Content ────────────────────────────────────────
    $('#btn-generate-ai').on('click', function () {
        var title    = $('#title').val().trim();
        var origIata = $('#h_origin_iata').val().trim();
        var origCity = $('#h_origin_city').val().trim();
        var destIata = $('#h_dest_iata').val().trim();
        var destCity = $('#h_dest_city').val().trim();

        if (!title) { alert('Please enter a Route Title first.'); $('#title').focus(); return; }
        if (!origIata || !destIata) { alert('Please select both Origin and Destination airports first.'); return; }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Generating...');
        $('#ai-status-msg').show();
        $('#ai-success-msg').addClass('d-none');
        $('#ai-error-msg').addClass('d-none').text('');

        $.ajax({
            url:     '{{ route("admin.flight-routes.generate-ai") }}',
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                title:            title,
                origin_iata:      origIata,
                origin_city:      origCity,
                destination_iata: destIata,
                destination_city: destCity,
                starting_price:   $('input[name="starting_price"]').val(),
                flight_duration:  $('input[name="flight_duration"]').val(),
                airlines:         $('input[name="airlines"]').val(),
                frequency:        $('input[name="frequency"]').val(),
            },
            success: function (res) {
                if (!res.success) { showAiError(res.error || 'Unknown error.'); return; }

                if (res.slug)            { $('#slug').val(res.slug); $('#prev-slug').text(res.slug); }
                if (res.seo_title)       { $('#seo_title').val(res.seo_title).trigger('input'); }
                if (res.seo_description) { $('#seo_desc').val(res.seo_description).trigger('input'); }
                if (res.seo_keywords)    { $('input[name="seo_keywords"]').val(res.seo_keywords); }
                if (res.short_desc)      { $('textarea[name="short_desc"]').val(res.short_desc); }

                if (res.description) {
                    if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
                        tinymce.get('description').setContent(res.description);
                    } else {
                        $('textarea[name="description"]').val(res.description);
                    }
                }

                if (res.faqs && res.faqs.length) {
                    faqs = res.faqs.map(function (f) {
                        return { question: f.question || '', answer: f.answer || '' };
                    });
                    renderFaqs();
                }

                if (res.schema_markup) { $('textarea[name="schema_markup"]').val(res.schema_markup); }

                $('#ai-status-msg').hide();
                $('#ai-success-msg').removeClass('d-none');
                $btn.prop('disabled', false).html('<i class="fa-solid fa-wand-magic-sparkles me-2"></i>Generate AI Content');
            },
            error: function (xhr) {
                var msg = 'Request failed.';
                try { msg = JSON.parse(xhr.responseText).error || msg; } catch (e) {}
                showAiError(msg);
            }
        });

        function showAiError(msg) {
            $('#ai-status-msg').hide();
            $('#ai-error-msg').removeClass('d-none').text('Error: ' + msg);
            $btn.prop('disabled', false).html('<i class="fa-solid fa-wand-magic-sparkles me-2"></i>Generate AI Content');
        }
    });
});
</script>
@endsection
