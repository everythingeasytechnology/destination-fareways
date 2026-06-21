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

    @if($errors->any())
    <div class="alert alert-danger mb-4 border-0 shadow-sm" id="form-errors" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="fa-solid fa-circle-xmark fs-5 me-2"></i>
            <strong>Fix these errors before publishing:</strong>
        </div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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

    <input type="hidden" id="draft_id" name="draft_id" value="{{ old('draft_id') }}">

    <div class="border-top pt-3 mt-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div id="autosave-status" class="text-muted small d-flex align-items-center gap-2" style="min-width:180px;">
            <span id="autosave-dot" class="d-inline-block rounded-circle" style="width:8px;height:8px;background:#ccc;"></span>
            <span id="autosave-msg">Not saved yet</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
            <button type="submit" id="publish-btn" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Publish Offer</button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function () {

    /* ── Constants ─────────────────────────────────────── */
    const LS_KEY   = 'offer_create_draft';
    const SAVE_URL = '{{ route('admin.offers.autosave') }}';
    const CSRF     = '{{ csrf_token() }}';

    /* Text fields we persist (excludes file inputs) */
    const TEXT_FIELDS = [
        'title','slug','subtitle','from_city','to_city','airline',
        'original_price','offer_price','discount_label','promo_code',
        'valid_from','valid_until','short_desc','description',
        'status','is_featured','sort_order',
        'seo_title','seo_description','seo_keywords'
    ];

    /* ── Status indicator helpers ───────────────────────── */
    function setStatus(msg, color) {
        $('#autosave-msg').text(msg);
        $('#autosave-dot').css('background', color);
    }

    /* Flag set the moment form submit starts – blocks all further saves */
    var formSubmitting = false;

    /* ── localStorage helpers ───────────────────────────── */
    function saveLocal() {
        if (formSubmitting) return; // never re-save after form started submitting
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
                if (f === 'description') return; // TinyMCE – done after init
                var el = document.getElementById(f);
                if (!el || !d[f]) return;
                el.value = d[f];
                // re-trigger flatpickr if it's a date field
                if ($(el).hasClass('flatpickr-date') && el._flatpickr) {
                    el._flatpickr.setDate(d[f]);
                }
            });
            if (d['_draft_id']) {
                $('#draft_id').val(d['_draft_id']);
            }
            // Restore TinyMCE after it finishes initialising
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
            if ($('#title').val().trim() !== '') ajaxSave();
        }, 3000);
    }

    $('form').on('input change', 'input, textarea, select', function () {
        scheduleAutosave();
    });

    // Hook TinyMCE changes
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

    /* ── Form submit: lock saves, abort autosave, sync TinyMCE ─────────── */
    $('form').first().on('submit', function () {
        formSubmitting = true;          // must be FIRST – blocks saveLocal/ajaxSave
        clearTimeout(debounceTimer);
        if (activeXhr) { activeXhr.abort(); activeXhr = null; }
        clearLocal();                   // wipe draft from localStorage
        if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
            tinymce.get('description').save(); // sync editor → textarea for POST
        }
        // don't preventDefault → form POSTs naturally
    });

    /* ── Slug generation & SEO previews ─────────────────── */
    $('#title').on('input', function () {
        var slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        $('#slug').val(slug);
        $('#prev-seo-slug').text(slug || 'flight-deal-london');
        if ($('#seo_title').val() === '') $('#prev-seo-title').text($(this).val());
    });

    $('#slug').on('input', function () {
        $('#prev-seo-slug').text($(this).val() || 'flight-deal-london');
    });

    $('.seo-input').on('input', function () {
        var val    = $(this).val();
        var cid    = $(this).data('char-counter');
        var maxLen = $(this).data('max');
        var prevId = cid.includes('title') ? 'prev-seo-title' : 'prev-seo-desc';
        $('#' + cid).text(val.length)
            .toggleClass('text-danger', val.length > maxLen)
            .toggleClass('text-success', val.length <= maxLen);
        if (cid.includes('title')) {
            $('#' + prevId).text(val || ($('#title').val() || 'Flight Deal to London | Cheap Flights'));
        } else {
            $('#' + prevId).text(val || 'Enter description above to simulate this exact search snippet.');
        }
    });

    /* ── Image compress → WebP then preview ─────────────── */

    var IMG_CONFIG = {
        'image'        : { w: 800,  h: 600,  q: 0.82 },
        'banner_image' : { w: 1920, h: 600,  q: 0.80 },
        'og_image'     : { w: 1200, h: 630,  q: 0.82 },
    };

    /* Detect WebP encode support once at page load */
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

    /* Render preview image + info badge below the upload box */
    function renderPreview(previewId, src, infoHtml) {
        var ph    = previewId.replace('-box', '-placeholder');
        var infoId = previewId + '-info';
        $('#' + previewId).attr('src', src).removeClass('d-none');
        $('#' + ph).addClass('d-none').text('No image selected');
        if (!$('#' + infoId).length) {
            $('#' + previewId).closest('.mt-2').after('<div id="' + infoId + '" class="small mt-1 ps-1"></div>');
        }
        $('#' + infoId).html(infoHtml);
    }

    /* Core conversion: draws image on canvas → toBlob(webp) → callback(blob | null) */
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
                /* Verify browser actually produced a WebP blob */
                if (blob && blob.type === 'image/webp' && blob.size > 0) {
                    cb(blob);
                } else {
                    cb(null); /* browser produced PNG or failed */
                }
            }, 'image/webp', cfg.q);
        };
        img.onerror = function () { URL.revokeObjectURL(objUrl); cb(null); };
        img.src = objUrl;
    }

    /* Try to inject a File into the native file input via DataTransfer */
    function replaceInputFile(input, file) {
        try {
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            /* Verify the replacement actually worked */
            return input.files[0] && input.files[0].type === 'image/webp';
        } catch (e) { return false; }
    }

    $('.image-preview-trigger').on('change', function () {
        var input     = this;
        var file      = input.files[0];
        var previewId = $(this).data('preview-id');
        var ph        = previewId.replace('-box', '-placeholder');
        var cfg       = IMG_CONFIG[$(this).attr('id')] || { w: 1200, h: 900, q: 0.82 };

        /* Reset state */
        $('#' + previewId).addClass('d-none').attr('src', '');
        $('#' + previewId + '-info').remove();
        $('#' + ph).text('No image selected').removeClass('d-none');

        if (!file) return;

        /* Client-side size guard: reject over 10 MB */
        if (file.size > 10 * 1024 * 1024) {
            alert('File is too large (max 10 MB). Please choose a smaller image.');
            input.value = '';
            return;
        }

        /* GIF: skip conversion, just show preview */
        if (file.type === 'image/gif' || !CAN_WEBP) {
            var gifReader = new FileReader();
            gifReader.onload = function (e) {
                var label = !CAN_WEBP
                    ? '<span class="text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>WebP not supported in this browser — original kept (' + fmtBytes(file.size) + ')</span>'
                    : '<span class="text-muted">GIF – kept as original (' + fmtBytes(file.size) + ')</span>';
                renderPreview(previewId, e.target.result, label);
            };
            gifReader.readAsDataURL(file);
            return;
        }

        /* Show spinner while converting */
        $('#' + ph).html('<span class="spinner-border spinner-border-sm text-primary me-1"></span><span class="text-muted">Converting to WebP…</span>');

        toWebPBlob(file, cfg, function (blob) {
            if (!blob) {
                /* Conversion failed – show original */
                var fr = new FileReader();
                fr.onload = function (e) {
                    renderPreview(previewId, e.target.result,
                        '<span class="text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>WebP conversion failed – original kept (' + fmtBytes(file.size) + ')</span>');
                };
                fr.readAsDataURL(file);
                return;
            }

            /* Build WebP File object */
            var webpFile = new File(
                [blob],
                file.name.replace(/\.[^.]+$/, '') + '.webp',
                { type: 'image/webp', lastModified: Date.now() }
            );

            /* Replace the input's file with the WebP version */
            var replaced = replaceInputFile(input, webpFile);

            /* Build preview URL directly from the blob (no extra FileReader needed) */
            var previewUrl = URL.createObjectURL(blob);

            var saved = Math.max(0, file.size - blob.size);
            var pct   = Math.round(saved / file.size * 100);

            var badge = replaced
                ? '<span class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>WebP · ' + fmtBytes(blob.size)
                    + (pct > 0 ? ' <span class="badge bg-success ms-1">−' + pct + '%</span>' : '') + '</span>'
                : '<span class="text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>Converted but input replace unsupported – original will upload (' + fmtBytes(file.size) + ')</span>';

            renderPreview(previewId, previewUrl, badge);
        });
    });

    /* ── On page load: restore draft ─────────────────────── */
    restoreLocal();
});
</script>
@endsection
