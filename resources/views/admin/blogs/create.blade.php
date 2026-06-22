@extends('layouts.admin')

@section('title', 'Create Travel Blog Post')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}" class="text-decoration-none text-muted">Blogs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Create Travel Blog Post</h2>
    <p class="text-muted mb-0">Publish beautiful stories and guides. Keep them engaging, SEO optimized, and structured with structured schema data.</p>
</div>

@if ($errors->any())
<div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4" role="alert">
    <div class="d-flex align-items-start gap-3">
        <i class="fa-solid fa-circle-xmark fs-5 mt-1 text-danger"></i>
        <div>
            <strong class="d-block mb-2">Fix these errors before publishing:</strong>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

<form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="draft_id" id="draft_id" value="{{ old('draft_id') }}">

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">

                <!-- Tabs Navigation -->
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="blogTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                            <i class="fa-solid fa-newspaper me-2"></i>General Details
                            @if ($errors->hasAny(['title','slug','subtitle','category','read_time','tags','author_name','author_image','excerpt','content']))
                                <span class="badge bg-danger ms-1" style="font-size:0.6rem;">!</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>SEO & Socials
                            @if ($errors->hasAny(['seo_title','seo_description','seo_keywords','canonical_url','og_image']))
                                <span class="badge bg-danger ms-1" style="font-size:0.6rem;">!</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="schema-tab" data-bs-toggle="pill" data-bs-target="#schema" type="button" role="tab">
                            <i class="fa-solid fa-code me-2"></i>Schema Markup
                            @if ($errors->has('schema_markup'))
                                <span class="badge bg-danger ms-1" style="font-size:0.6rem;">!</span>
                            @endif
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="blogTabContent">

                    <!-- Tab 1: General Details -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">Post Title</label>
                                    <input type="text" class="form-control px-3 @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. 10 Best Hidden Beaches in Mallorca" required>
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="slug" class="form-label fw-bold">Auto-Generated URL Slug</label>
                                    <input type="text" class="form-control px-3 font-monospace @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. 10-best-hidden-beaches-mallorca" style="font-size: 0.85rem;">
                                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="subtitle" class="form-label fw-bold">Sub-heading / Catchphrase</label>
                                    <input type="text" class="form-control px-3 @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Discover pristine sands, turquoise waters, and escape the crowds">
                                    @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label fw-bold">Category</label>
                                    <input type="text" class="form-control px-3 @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" placeholder="e.g. Travel Guides">
                                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="read_time" class="form-label fw-bold">Read Duration</label>
                                    <input type="text" class="form-control px-3 @error('read_time') is-invalid @enderror" id="read_time" name="read_time" value="{{ old('read_time') }}" placeholder="e.g. 6 min read">
                                    @error('read_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="tags" class="form-label fw-bold">Comma-Separated Tags</label>
                                    <input type="text" class="form-control px-3 @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags') }}" placeholder="e.g. spain, beach, mallorca, summer">
                                    @error('tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Author Card -->
                            <div class="col-12 border rounded p-3 bg-light bg-opacity-50 my-3">
                                <h6 class="fw-bold text-navy mb-3"><i class="fa-regular fa-user me-2 text-warning"></i>Author Profile Infobox</h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="author_name" class="form-label fw-semibold">Author Name</label>
                                        <input type="text" class="form-control px-3 bg-white @error('author_name') is-invalid @enderror" id="author_name" name="author_name" value="{{ old('author_name', Auth::user()->name ?? '') }}" placeholder="e.g. Clara Oswald">
                                        @error('author_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="author_image" class="form-label fw-semibold">Author Profile Photo</label>
                                        <input type="file" class="form-control bg-white image-preview-trigger @error('author_image') is-invalid @enderror" id="author_image" name="author_image" data-preview-id="author-preview-box">
                                        @error('author_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <div class="mt-2 d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px;">
                                                <img src="" id="author-preview-box" alt="Author Preview" class="object-fit-cover w-100 h-100 d-none">
                                                <span class="text-muted small" id="author-preview-placeholder"><i class="fa-regular fa-image"></i></span>
                                            </div>
                                            <span class="text-muted small" style="font-size: 0.75rem;">Avatar Preview (square recommended)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="excerpt" class="form-label fw-bold">Post Excerpt / Brief Summary</label>
                                    <textarea class="form-control px-3 @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3" placeholder="Write a short summary (150-200 characters) to show on article listings...">{{ old('excerpt') }}</textarea>
                                    @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="content" class="form-label fw-bold">Full Post Article Content</label>
                                    <textarea class="form-control tinymce-editor @error('content') is-invalid @enderror" id="content" name="content" rows="12">{{ old('content') }}</textarea>
                                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                    <input type="text" class="form-control px-3 @error('seo_keywords') is-invalid @enderror" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords') }}" placeholder="e.g. mallorca beach guide, best mallorca beaches, spain travel">
                                    @error('seo_keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="canonical_url" class="form-label fw-bold">Canonical URL override (optional)</label>
                                    <input type="url" class="form-control px-3 @error('canonical_url') is-invalid @enderror" id="canonical_url" name="canonical_url" value="{{ old('canonical_url') }}" placeholder="e.g. https://destinationfareways.com/blogs/original-post-slug">
                                    @error('canonical_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Live Google snippet preview -->
                            <div class="col-12 my-4">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Google SEO Snippet Live Simulator</h6>
                                <div class="p-3 border rounded shadow-sm bg-white" style="font-family: Arial, sans-serif;">
                                    <div class="text-muted" style="font-size: 12px; margin-bottom: 2px;">
                                        https://destinationfareways.com/blogs/<span id="prev-seo-slug" class="font-monospace">10-best-hidden-beaches-mallorca</span>
                                    </div>
                                    <h5 id="prev-seo-title" class="text-primary mb-1 fw-normal" style="font-size: 19px; line-height: 1.3; cursor: pointer;">10 Best Hidden Beaches in Mallorca</h5>
                                    <p id="prev-seo-desc" class="text-dark small mb-0" style="font-size: 13px; line-height: 1.4; color: #4d5156 !important;">
                                        Enter description above to simulate this exact search snippet card rendering in live Google search index pages.
                                    </p>
                                </div>
                            </div>

                            <div class="col-12">
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

                    <!-- Tab 3: Schema Markup -->
                    <div class="tab-pane fade" id="schema" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="schema_markup" class="form-label fw-bold mb-0">Custom JSON-LD Schema Script Block</label>
                                        <button type="button" id="generate-blog-schema" class="btn btn-sm btn-outline-primary px-3 rounded-pill" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-magic me-1"></i>Generate Default Article Schema
                                        </button>
                                    </div>
                                    <p class="text-muted small mb-2"><i class="fa-solid fa-circle-info me-1"></i>Paste your custom JSON-LD schema block below (including the <code>&lt;script type="application/ld+json"&gt;</code> tags).</p>
                                    <textarea class="form-control px-3 font-monospace text-navy @error('schema_markup') is-invalid @enderror" id="schema_markup" name="schema_markup" rows="12" placeholder='<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  ...
}
</script>' style="font-size: 0.8rem; background-color: #f8fafc;">{{ old('schema_markup') }}</textarea>
                                    @error('schema_markup') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div id="schema-validation-msg" class="small mt-2 d-none"></div>
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
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive / Draft</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="is_featured" class="form-label fw-bold">Mark as Featured Post?</label>
                    <select class="form-select px-3 @error('is_featured') is-invalid @enderror" id="is_featured" name="is_featured" required>
                        <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>Standard Post</option>
                        <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>🌟 Promoted Featured Post</option>
                    </select>
                    @error('is_featured') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="published_at" class="form-label fw-bold">Scheduled / Publish Date</label>
                    <input type="text" class="form-control flatpickr-date px-3 @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at') }}" placeholder="Defaults to current date">
                    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-regular fa-image text-warning me-2"></i>Post Assets
                </h5>

                <div class="mb-4">
                    <label for="featured_image" class="form-label fw-bold">List Cover Image (800x600px)</label>
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
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">Cancel</a>
            <button type="submit" id="publish-btn" class="btn btn-action px-5">
                <i class="fa-solid fa-floppy-disk me-2"></i>Publish Blog Post
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function () {

    /* ── Constants ─────────────────────────────────────── */
    const LS_KEY   = 'blog_create_draft';
    const SAVE_URL = '{{ route('admin.blogs.autosave') }}';
    const CSRF     = '{{ csrf_token() }}';

    const TEXT_FIELDS = [
        'title', 'slug', 'subtitle', 'category', 'read_time', 'tags',
        'author_name', 'excerpt', 'content',
        'status', 'is_featured', 'published_at',
        'seo_title', 'seo_description', 'seo_keywords', 'canonical_url', 'schema_markup'
    ];

    /* ── Status indicator ───────────────────────────────── */
    function setStatus(msg, color) {
        $('#autosave-msg').text(msg);
        $('#autosave-dot').css('background', color);
    }

    var formSubmitting = false;

    /* ── Switch to tab with errors on page load ─────────── */
    @if ($errors->any())
        @if ($errors->hasAny(['seo_title','seo_description','seo_keywords','canonical_url','og_image']))
            $('#seo-tab').tab('show');
        @elseif ($errors->has('schema_markup'))
            $('#schema-tab').tab('show');
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
        if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
            d['content'] = tinymce.get('content').getContent();
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
                if (f === 'content') return;
                var el = document.getElementById(f);
                if (!el || !d[f]) return;
                el.value = d[f];
                if ($(el).hasClass('flatpickr-date') && el._flatpickr) {
                    el._flatpickr.setDate(d[f]);
                }
            });
            if (d['_draft_id']) $('#draft_id').val(d['_draft_id']);

            var attempts = 0;
            var ti = setInterval(function () {
                if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                    if (d['content']) tinymce.get('content').setContent(d['content']);
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

        if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
            tinymce.get('content').save();
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
        if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
            if (!tinyHooked) {
                tinymce.get('content').on('input keyup change', function () {
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
        if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
            tinymce.get('content').save();
        }
    });

    /* ── Slug generation ─────────────────────────────────── */
    $('#title').on('input', function () {
        var slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        $('#slug').val(slug);
        $('#prev-seo-slug').text(slug || '10-best-hidden-beaches-mallorca');
        if ($('#seo_title').val() === '') $('#prev-seo-title').text($(this).val());
    });

    $('#slug').on('input', function () {
        $('#prev-seo-slug').text($(this).val() || '10-best-hidden-beaches-mallorca');
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
            $('#' + prevId).text(val || ($('#title').val() || '10 Best Hidden Beaches in Mallorca'));
        } else {
            $('#' + prevId).text(val || 'Enter description above to simulate this exact search snippet card rendering in live Google search index pages.');
        }
    });

    /* ── Image file preview ──────────────────────────────── */
    $('.image-preview-trigger').on('change', function () {
        var file      = this.files[0];
        var previewId = $(this).data('preview-id');
        var ph        = previewId.replace('-box', '-placeholder');

        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + previewId).attr('src', e.target.result).removeClass('d-none');
                $('#' + ph).addClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            $('#' + previewId).addClass('d-none').attr('src', '');
            $('#' + ph).removeClass('d-none');
        }
    });

    /* ── Generate default JSON-LD schema ────────────────── */
    $('#generate-blog-schema').on('click', function () {
        var title   = $('#title').val() || 'Mallorca Hidden Beaches';
        var slug    = $('#slug').val()  || 'mallorca-hidden-beaches';
        var excerpt = $('#excerpt').val() || 'Mallorca has some of the best hidden beaches in Europe.';
        var author  = $('#author_name').val() || 'Admin';
        var date    = $('#published_at').val() || new Date().toISOString().split('T')[0];

        var schema = `<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://destinationfareways.com/blogs/${slug}"
  },
  "headline": "${title.replace(/"/g, '\\"')}",
  "description": "${excerpt.replace(/"/g, '\\"')}",
  "image": "https://destinationfareways.com/storage/uploads/blogs/default-blog.jpg",
  "author": {
    "@type": "Person",
    "name": "${author.replace(/"/g, '\\"')}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Destination Fareways",
    "logo": {
      "@type": "ImageObject",
      "url": "https://destinationfareways.com/assets/images/logo.png"
    }
  },
  "datePublished": "${date}"
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
