@extends('layouts.admin')

@section('title', 'Edit Page')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}" class="text-decoration-none text-muted">Pages</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Edit Page</h2>
    <p class="text-muted mb-0">Modify page content, update metadata tags, coordinate Latent Semantic Index keywords, and tune custom LD+JSON scripts.</p>
</div>

<form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="pageTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab"><i class="fa-solid fa-file-lines me-2"></i>Page Content</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab"><i class="fa-solid fa-magnifying-glass me-2"></i>SEO & Socials</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="schema-tab" data-bs-toggle="pill" data-bs-target="#schema" type="button" role="tab"><i class="fa-solid fa-code me-2"></i>Structured Schemas</button>
                    </li>
                </ul>

                <div class="tab-content" id="pageTabContent">
                    
                    <!-- Tab 1: Page Content -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">Page Title</label>
                                    <input type="text" class="form-control px-3" id="title" name="title" value="{{ old('title', $page->title) }}" placeholder="e.g. Cheap Flights to London" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="slug" class="form-label fw-bold">URL Path Slug</label>
                                    <input type="text" class="form-control px-3 font-monospace" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="e.g. cheap-flights-london" style="font-size: 0.85rem;" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="subtitle" class="form-label fw-bold">Hero Subheading</label>
                                    <input type="text" class="form-control px-3" id="subtitle" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}" placeholder="e.g. Compare airline rates, discover booking patterns, and fly cheaper to London.">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="content" class="form-label fw-bold">Primary Page Content Body</label>
                                    <textarea class="form-control tinymce-editor" id="content" name="content" rows="12">{{ old('content', $page->content) }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_content" class="form-label fw-bold">Secondary SEO Text Content Block (Footer / Bottom Area)</label>
                                    <p class="text-muted small mb-2"><i class="fa-solid fa-circle-info me-1"></i>A dedicated HTML content block specifically for dense bottom page keywords, flight terms, and disclaimers.</p>
                                    <textarea class="form-control tinymce-editor" id="seo_content" name="seo_content" rows="6">{{ old('seo_content', $page->seo_content) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab 2: SEO & Socials -->
                    <div class="tab-pane fade" id="seo" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_title" class="form-label fw-bold">Meta Title Tag</label>
                                    <input type="text" class="form-control px-3 seo-input" id="seo_title" name="seo_title" value="{{ old('seo_title', $page->seo_title) }}" placeholder="Optimize for search engine titles..." data-char-counter="seo-title-count" data-max="60">
                                    <div class="text-end small mt-1"><span id="seo-title-count">{{ strlen($page->seo_title ?? '') }}</span> / 60 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_description" class="form-label fw-bold">Meta Description Tag</label>
                                    <textarea class="form-control px-3 seo-input" id="seo_description" name="seo_description" rows="3" placeholder="Write an engaging meta descriptor snippet..." data-char-counter="seo-desc-count" data-max="160">{{ old('seo_description', $page->seo_description) }}</textarea>
                                    <div class="text-end small mt-1"><span id="seo-desc-count">{{ strlen($page->seo_description ?? '') }}</span> / 160 characters</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="focus_keyword" class="form-label fw-bold">Focus SEO Keyword</label>
                                    <input type="text" class="form-control px-3" id="focus_keyword" name="focus_keyword" value="{{ old('focus_keyword', $page->focus_keyword) }}" placeholder="e.g. cheap flights to london">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label fw-bold">Other Latent Keywords</label>
                                    <input type="text" class="form-control px-3" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $page->seo_keywords) }}" placeholder="e.g. fly to london, flight deal lhr, london airfare">
                                </div>
                            </div>

                            <!-- Live Google snippet preview -->
                            <div class="col-12 my-3">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Google SEO Snippet Live Simulator</h6>
                                <div class="p-3 border rounded shadow-sm bg-white" style="font-family: Arial, sans-serif;">
                                    <div class="text-muted" style="font-size: 12px; margin-bottom: 2px;">
                                        https://destinationfareways.com/<span id="prev-seo-slug" class="font-monospace">{{ $page->slug ?? 'cheap-flights-london' }}</span>
                                    </div>
                                    <h5 id="prev-seo-title" class="text-primary mb-1 fw-normal" style="font-size: 19px; line-height: 1.3; cursor: pointer;">{{ $page->seo_title ?? ($page->title ?? 'Cheap Flights to London') }}</h5>
                                    <p id="prev-seo-desc" class="text-dark small mb-0" style="font-size: 13px; line-height: 1.4; color: #4d5156 !important;">
                                        {{ $page->seo_description ?? 'Enter description above to simulate this exact search snippet card rendering in live Google search index pages.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Facebook OG Metadata -->
                            <div class="col-12 border-top pt-3 mt-4">
                                <h6 class="fw-bold text-navy mb-3"><i class="fa-brands fa-facebook text-royal me-2"></i>Facebook OpenGraph Settings</h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="og_title" class="form-label fw-semibold">OG Title</label>
                                            <input type="text" class="form-control px-3" id="og_title" name="og_title" value="{{ old('og_title', $page->og_title) }}" placeholder="Defaults to Meta Title if blank">
                                        </div>
                                        <div class="mb-3">
                                            <label for="og_description" class="form-label fw-semibold">OG Description</label>
                                            <textarea class="form-control px-3" id="og_description" name="og_description" rows="3" placeholder="Defaults to Meta Description if blank">{{ old('og_description', $page->og_description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="og_image" class="form-label fw-semibold">OG Share Image (1200x630px)</label>
                                        <input type="file" class="form-control image-preview-trigger" id="og_image" name="og_image" data-preview-id="og-preview-box">
                                        <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 110px;">
                                            @if($page->og_image)
                                                <img src="{{ asset('storage/' . $page->og_image) }}" id="og-preview-box" alt="OG Preview" class="img-fluid h-100">
                                                <span class="text-muted small py-3 d-none" id="og-preview-placeholder">No image selected</span>
                                            @else
                                                <img src="" id="og-preview-box" alt="OG Preview" class="img-fluid h-100 d-none">
                                                <span class="text-muted small py-3" id="og-preview-placeholder">No image selected</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Twitter Card Metadata -->
                            <div class="col-12 border-top pt-3 mt-4">
                                <h6 class="fw-bold text-navy mb-3"><i class="fa-brands fa-twitter text-info me-2"></i>Twitter Cards Settings</h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="twitter_title" class="form-label fw-semibold">Twitter Title</label>
                                            <input type="text" class="form-control px-3" id="twitter_title" name="twitter_title" value="{{ old('twitter_title', $page->twitter_title) }}" placeholder="Defaults to Meta Title if blank">
                                        </div>
                                        <div class="mb-3">
                                            <label for="twitter_description" class="form-label fw-semibold">Twitter Description</label>
                                            <textarea class="form-control px-3" id="twitter_description" name="twitter_description" rows="3" placeholder="Defaults to Meta Description if blank">{{ old('twitter_description', $page->twitter_description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="twitter_image" class="form-label fw-semibold">Twitter Summary Image (1200x600px)</label>
                                        <input type="file" class="form-control image-preview-trigger" id="twitter_image" name="twitter_image" data-preview-id="twitter-preview-box">
                                        <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 110px;">
                                            @if($page->twitter_image)
                                                <img src="{{ asset('storage/' . $page->twitter_image) }}" id="twitter-preview-box" alt="Twitter Preview" class="img-fluid h-100">
                                                <span class="text-muted small py-3 d-none" id="twitter-preview-placeholder">No image selected</span>
                                            @else
                                                <img src="" id="twitter-preview-box" alt="Twitter Preview" class="img-fluid h-100 d-none">
                                                <span class="text-muted small py-3" id="twitter-preview-placeholder">No image selected</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Structured Schemas -->
                    <div class="tab-pane fade" id="schema" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="schema_markup" class="form-label fw-bold mb-0">Custom JSON-LD Schema Block</label>
                                        <button type="button" id="generate-generic-schema" class="btn btn-sm btn-outline-primary px-3 rounded-pill" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-magic me-1"></i>Generate WebPage Schema
                                        </button>
                                    </div>
                                    <textarea class="form-control px-3 font-monospace text-navy schema-validate-trigger" id="schema_markup" name="schema_markup" rows="6" placeholder='<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  ...
}
</script>' style="font-size: 0.8rem; background-color: #f8fafc;" data-error-id="schema-err-msg">{{ old('schema_markup', $page->schema_markup) }}</textarea>
                                    <div id="schema-err-msg" class="small mt-2 d-none"></div>
                                </div>

                                <div class="mb-4 border-top pt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="faq_schema" class="form-label fw-bold mb-0">FAQ Schema Block</label>
                                        <button type="button" id="generate-faq-schema" class="btn btn-sm btn-outline-primary px-3 rounded-pill" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-magic me-1"></i>Generate Default FAQ Schema
                                        </button>
                                    </div>
                                    <textarea class="form-control px-3 font-monospace text-navy schema-validate-trigger" id="faq_schema" name="faq_schema" rows="6" placeholder='<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  ...
}
</script>' style="font-size: 0.8rem; background-color: #f8fafc;" data-error-id="faq-err-msg">{{ old('faq_schema', $page->faq_schema) }}</textarea>
                                    <div id="faq-err-msg" class="small mt-2 d-none"></div>
                                </div>

                                <div class="mb-3 border-top pt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="breadcrumb_schema" class="form-label fw-bold mb-0">Breadcrumb Schema Block</label>
                                        <button type="button" id="generate-bread-schema" class="btn btn-sm btn-outline-primary px-3 rounded-pill" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-magic me-1"></i>Generate Breadcrumb Schema
                                        </button>
                                    </div>
                                    <textarea class="form-control px-3 font-monospace text-navy schema-validate-trigger" id="breadcrumb_schema" name="breadcrumb_schema" rows="6" placeholder='<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  ...
}
</script>' style="font-size: 0.8rem; background-color: #f8fafc;" data-error-id="bread-err-msg">{{ old('breadcrumb_schema', $page->breadcrumb_schema) }}</textarea>
                                    <div id="bread-err-msg" class="small mt-2 d-none"></div>
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
                        <option value="active" {{ $page->status === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                        <option value="inactive" {{ $page->status === 'inactive' ? 'selected' : '' }}>Inactive / Draft</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="show_breadcrumb" class="form-label fw-bold">Display Breadcrumb Bar?</label>
                    <select class="form-select px-3" id="show_breadcrumb" name="show_breadcrumb" required>
                        <option value="1" {{ $page->show_breadcrumb ? 'selected' : '' }}>Show Breadcrumbs on Page</option>
                        <option value="0" {{ !$page->show_breadcrumb ? 'selected' : '' }}>Hide Breadcrumbs on Page</option>
                    </select>
                </div>
            </div>

            <!-- Upload Assets -->
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-regular fa-image text-warning me-2"></i>Header Banner
                </h5>

                <div class="mb-3">
                    <label for="banner_image" class="form-label fw-bold">Hero Banner (1920x600px)</label>
                    <input type="file" class="form-control image-preview-trigger" id="banner_image" name="banner_image" data-preview-id="banner-preview-box">
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                        @if($page->banner_image)
                            <img src="{{ asset('storage/' . $page->banner_image) }}" id="banner-preview-box" alt="Banner Preview" class="img-fluid h-100">
                            <span class="text-muted small py-4 d-none" id="banner-preview-placeholder">No image selected</span>
                        @else
                            <img src="" id="banner-preview-box" alt="Banner Preview" class="img-fluid h-100 d-none">
                            <span class="text-muted small py-4" id="banner-preview-placeholder">No image selected</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-3 mt-4 text-end">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary px-4 rounded-pill me-2">Cancel</a>
        <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto Slug Generation
        $('#title').on('input', function() {
            if ($('#slug').val() === '') {
                var slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
                $('#slug').val(slug);
                $('#prev-seo-slug').text(slug ? slug : 'cheap-flights-london');
            }
            if ($('#seo_title').val() === '') {
                $('#prev-seo-title').text($(this).val());
            }
        });

        $('#slug').on('input', function() {
            var val = $(this).val();
            $('#prev-seo-slug').text(val ? val : 'cheap-flights-london');
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
                $('#' + previewId).text(val ? val : ($('#title').val() ? $('#title').val() : 'Cheap Flights to London | Airfare Deals'));
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
            }
        });

        // Schema validation listeners
        $('.schema-validate-trigger').on('input', function() {
            var errorId = $(this).data('error-id');
            validateSchema($(this).val(), errorId);
        });

        // Run validation on page load
        $('.schema-validate-trigger').each(function() {
            var errorId = $(this).data('error-id');
            validateSchema($(this).val(), errorId);
        });

        function validateSchema(text, errorId) {
            var msgEl = $('#' + errorId);
            if (!text || !text.trim()) {
                msgEl.addClass('d-none');
                return;
            }

            msgEl.removeClass('d-none');
            
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

        // Schema generation hooks
        $('#generate-generic-schema').on('click', function() {
            var title = $('#title').val() || 'Cheap Flights to London';
            var slug = $('#slug').val() || 'cheap-flights-london';
            var desc = $('#seo_description').val() || 'Find the cheapest flights to London on Destination Fareways.';
            
            var schema = `<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "${title.replace(/"/g, '\\"')}",
  "description": "${desc.replace(/"/g, '\\"')}",
  "url": "https://destinationfareways.com/${slug}"
}
<\/script>`;
            $('#schema_markup').val(schema);
            validateSchema(schema, 'schema-err-msg');
        });

        $('#generate-faq-schema').on('click', function() {
            var schema = `<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "How do I find the cheapest flight deals?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Booking mid-week and planning 4-6 weeks in advance typically yields the most competitive airline tariffs."
    }
  }]
}
<\/script>`;
            $('#faq_schema').val(schema);
            validateSchema(schema, 'faq-err-msg');
        });

        $('#generate-bread-schema').on('click', function() {
            var title = $('#title').val() || 'Cheap Flights to London';
            var slug = $('#slug').val() || 'cheap-flights-london';

            var schema = `<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Home",
    "item": "https://destinationfareways.com"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "${title.replace(/"/g, '\\"')}",
    "item": "https://destinationfareways.com/${slug}"
  }]
}
<\/script>`;
            $('#breadcrumb_schema').val(schema);
            validateSchema(schema, 'bread-err-msg');
        });
    });
</script>
@endsection
