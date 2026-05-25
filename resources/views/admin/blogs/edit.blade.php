@extends('layouts.admin')

@section('title', 'Edit Travel Blog Post')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}" class="text-decoration-none text-muted">Blogs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Post</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Edit Travel Blog Post</h2>
    <p class="text-muted mb-0">Modify the article details, update cover assets, refine SEO tags, and tweak structured markup declarations.</p>
</div>

<form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">
                
                <!-- Tabs Navigation -->
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="blogTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab"><i class="fa-solid fa-newspaper me-2"></i>General Details</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab"><i class="fa-solid fa-magnifying-glass me-2"></i>SEO & Socials</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2" id="schema-tab" data-bs-toggle="pill" data-bs-target="#schema" type="button" role="tab"><i class="fa-solid fa-code me-2"></i>Schema Markup</button>
                    </li>
                </ul>

                <div class="tab-content" id="blogTabContent">
                    
                    <!-- Tab 1: General Details -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">Post Title</label>
                                    <input type="text" class="form-control px-3" id="title" name="title" value="{{ old('title', $blog->title) }}" placeholder="e.g. 10 Best Hidden Beaches in Mallorca" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="slug" class="form-label fw-bold">URL Slug</label>
                                    <input type="text" class="form-control px-3 font-monospace" id="slug" name="slug" value="{{ old('slug', $blog->slug) }}" placeholder="e.g. 10-best-hidden-beaches-mallorca" style="font-size: 0.85rem;" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="subtitle" class="form-label fw-bold">Sub-heading / Catchphrase</label>
                                    <input type="text" class="form-control px-3" id="subtitle" name="subtitle" value="{{ old('subtitle', $blog->subtitle) }}" placeholder="e.g. Discover pristine sands, turquoise waters, and escape the crowds">
                                </div>
                            </div>

                            <!-- Post metadata -->
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label fw-bold">Category</label>
                                    <input type="text" class="form-control px-3" id="category" name="category" value="{{ old('category', $blog->category) }}" placeholder="e.g. Travel Guides">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="read_time" class="form-label fw-bold">Read Duration</label>
                                    <input type="text" class="form-control px-3" id="read_time" name="read_time" value="{{ old('read_time', $blog->read_time) }}" placeholder="e.g. 6 min read">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <label for="tags" class="form-label fw-bold">Comma-Separated Tags</label>
                                    <input type="text" class="form-control px-3" id="tags" name="tags" value="{{ old('tags', $blog->tags) }}" placeholder="e.g. spain, beach, mallorca, summer">
                                </div>
                            </div>

                            <!-- Author Card -->
                            <div class="col-12 border rounded p-3 bg-light bg-opacity-50 my-3">
                                <h6 class="fw-bold text-navy mb-3"><i class="fa-regular fa-user me-2 text-warning"></i>Author Profile Infobox</h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="author_name" class="form-label fw-semibold">Author Name</label>
                                        <input type="text" class="form-control px-3 bg-white" id="author_name" name="author_name" value="{{ old('author_name', $blog->author_name) }}" placeholder="e.g. Clara Oswald">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="author_image" class="form-label fw-semibold">Author Profile Photo</label>
                                        <input type="file" class="form-control bg-white image-preview-trigger" id="author_image" name="author_image" data-preview-id="author-preview-box">
                                        <div class="mt-2 d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px;">
                                                @if($blog->author_image)
                                                    <img src="{{ asset('storage/' . $blog->author_image) }}" id="author-preview-box" alt="Author Preview" class="object-fit-cover w-100 h-100">
                                                    <span class="text-muted small d-none" id="author-preview-placeholder"><i class="fa-regular fa-image"></i></span>
                                                @else
                                                    <img src="" id="author-preview-box" alt="Author Preview" class="object-fit-cover w-100 h-100 d-none">
                                                    <span class="text-muted small" id="author-preview-placeholder"><i class="fa-regular fa-image"></i></span>
                                                @endif
                                            </div>
                                            <span class="text-muted small" style="font-size: 0.75rem;">Avatar Preview (square recommended)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="excerpt" class="form-label fw-bold">Post Excerpt / Brief Summary</label>
                                    <textarea class="form-control px-3" id="excerpt" name="excerpt" rows="3" placeholder="Write a short summary (150-200 characters) to show on article listings...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="content" class="form-label fw-bold">Full Post Article Content</label>
                                    <textarea class="form-control tinymce-editor" id="content" name="content" rows="12">{{ old('content', $blog->content) }}</textarea>
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
                                    <input type="text" class="form-control px-3 seo-input" id="seo_title" name="seo_title" value="{{ old('seo_title', $blog->seo_title) }}" placeholder="Optimize for search engine titles..." data-char-counter="seo-title-count" data-max="60">
                                    <div class="text-end small mt-1"><span id="seo-title-count">{{ strlen($blog->seo_title ?? '') }}</span> / 60 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_description" class="form-label fw-bold">Meta Description Tag</label>
                                    <textarea class="form-control px-3 seo-input" id="seo_description" name="seo_description" rows="3" placeholder="Write an engaging meta descriptor snippet..." data-char-counter="seo-desc-count" data-max="160">{{ old('seo_description', $blog->seo_description) }}</textarea>
                                    <div class="text-end small mt-1"><span id="seo-desc-count">{{ strlen($blog->seo_description ?? '') }}</span> / 160 characters</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label fw-bold">Focus SEO Keywords</label>
                                    <input type="text" class="form-control px-3" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $blog->seo_keywords) }}" placeholder="e.g. mallorca beach guide, best mallorca beaches, spain travel">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="canonical_url" class="form-label fw-bold">Canonical URL override (optional)</label>
                                    <input type="url" class="form-control px-3" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $blog->canonical_url) }}" placeholder="e.g. https://destinationfareways.com/blogs/original-post-slug">
                                </div>
                            </div>

                            <!-- Live Google snippet preview -->
                            <div class="col-12 my-4">
                                <h6 class="fw-bold text-navy border-bottom pb-2">Google SEO Snippet Live Simulator</h6>
                                <div class="p-3 border rounded shadow-sm bg-white" style="font-family: Arial, sans-serif;">
                                    <div class="text-muted" style="font-size: 12px; margin-bottom: 2px;">
                                        https://destinationfareways.com/blogs/<span id="prev-seo-slug" class="font-monospace">{{ $blog->slug ?? '10-best-hidden-beaches-mallorca' }}</span>
                                    </div>
                                    <h5 id="prev-seo-title" class="text-primary mb-1 fw-normal" style="font-size: 19px; line-height: 1.3; cursor: pointer;">{{ $blog->seo_title ?? ($blog->title ?? '10 Best Hidden Beaches in Mallorca') }}</h5>
                                    <p id="prev-seo-desc" class="text-dark small mb-0" style="font-size: 13px; line-height: 1.4; color: #4d5156 !important;">
                                        {{ $blog->seo_description ?? 'Enter description above to simulate this exact search snippet card rendering in live Google search index pages.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Social OG image upload -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="og_image" class="form-label fw-bold">OG Share Card Image (1200x630px recommended)</label>
                                    <input type="file" class="form-control image-preview-trigger" id="og_image" name="og_image" data-preview-id="og-preview-box">
                                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                        @if($blog->og_image)
                                            <img src="{{ asset('storage/' . $blog->og_image) }}" id="og-preview-box" alt="OG Preview" class="img-fluid h-100">
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
                                    <textarea class="form-control px-3 font-monospace text-navy" id="schema_markup" name="schema_markup" rows="12" placeholder='<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  ...
}
</script>' style="font-size: 0.8rem; background-color: #f8fafc;">{{ old('schema_markup', $blog->schema_markup) }}</textarea>
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
            <!-- Publishing attributes -->
            <div class="card premium-card border-0 shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-circle-check text-warning me-2"></i>Publish Settings
                </h5>

                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Display Status</label>
                    <select class="form-select px-3" id="status" name="status" required>
                        <option value="active" {{ $blog->status === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                        <option value="inactive" {{ $blog->status === 'inactive' ? 'selected' : '' }}>Inactive / Draft</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="is_featured" class="form-label fw-bold">Mark as Featured Post?</label>
                    <select class="form-select px-3" id="is_featured" name="is_featured" required>
                        <option value="0" {{ !$blog->is_featured ? 'selected' : '' }}>Standard Post</option>
                        <option value="1" {{ $blog->is_featured ? 'selected' : '' }}>🌟 Promoted Featured Post</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="published_at" class="form-label fw-bold">Scheduled / Publish Date</label>
                    <input type="text" class="form-control flatpickr-date px-3" id="published_at" name="published_at" value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d') : '') }}" placeholder="Defaults to current date">
                </div>
            </div>

            <!-- Upload Assets -->
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-regular fa-image text-warning me-2"></i>Post Assets
                </h5>

                <div class="mb-4">
                    <label for="featured_image" class="form-label fw-bold">List Cover Image (800x600px)</label>
                    <input type="file" class="form-control image-preview-trigger" id="featured_image" name="featured_image" data-preview-id="cover-preview-box">
                    <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                        @if($blog->featured_image)
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" id="cover-preview-box" alt="Cover Preview" class="img-fluid h-100">
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
                        @if($blog->banner_image)
                            <img src="{{ asset('storage/' . $blog->banner_image) }}" id="banner-preview-box" alt="Banner Preview" class="img-fluid h-100">
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
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary px-4 rounded-pill me-2">Cancel</a>
        <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle custom slug modification
        $('#title').on('input', function() {
            // Only update slug if it was empty originally
            if ($('#slug').val() === '') {
                var slug = $(this).val().toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
                $('#slug').val(slug);
                $('#prev-seo-slug').text(slug ? slug : '10-best-hidden-beaches-mallorca');
            }
            if ($('#seo_title').val() === '') {
                $('#prev-seo-title').text($(this).val());
            }
        });

        $('#slug').on('input', function() {
            var val = $(this).val();
            $('#prev-seo-slug').text(val ? val : '10-best-hidden-beaches-mallorca');
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
                $('#' + previewId).text(val ? val : ($('#title').val() ? $('#title').val() : '10 Best Hidden Beaches in Mallorca'));
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

        // Generate default JSON-LD Blog schema
        $('#generate-blog-schema').on('click', function() {
            var title = $('#title').val() || 'Mallorca Hidden Beaches';
            var slug = $('#slug').val() || 'mallorca-hidden-beaches';
            var excerpt = $('#excerpt').val() || 'Mallorca has some of the best hidden beaches in Europe.';
            var author = $('#author_name').val() || 'Admin';
            var date = $('#published_at').val() || new Date().toISOString().split('T')[0];

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

        // Run validation on page load for existing schemas
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
