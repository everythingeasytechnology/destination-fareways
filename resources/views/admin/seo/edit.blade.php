@extends('layouts.admin')

@section('title', 'Configure SEO - ' . $seo->page_name)

@section('styles')
<style>
    /* Google Snippet Simulator */
    .google-preview {
        font-family: arial, sans-serif;
        padding: 18px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .google-url-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #202124;
        margin-bottom: 4px;
    }
    .google-favicon-placeholder {
        width: 16px;
        height: 16px;
        background-color: #f1f3f4;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #5f6368;
    }
    .google-url {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .google-title {
        font-size: 20px;
        color: #1a0dab;
        line-height: 1.3;
        margin-bottom: 4px;
        display: block;
        text-decoration: none;
        word-wrap: break-word;
        font-weight: normal;
    }
    .google-title:hover {
        text-decoration: underline;
    }
    .google-desc {
        font-size: 14px;
        color: #4d5156;
        line-height: 1.58;
        word-wrap: break-word;
    }

    /* Facebook Open Graph Card Preview */
    .og-preview-card {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #f8fafc;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .og-preview-img-container {
        position: relative;
        width: 100%;
        height: 240px;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .og-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .og-preview-img-fallback {
        color: #94a3b8;
        font-size: 2.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .og-preview-img-fallback span {
        font-size: 0.85rem;
        font-weight: 500;
    }
    .og-preview-content {
        padding: 12px 16px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
    }
    .og-preview-domain {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 3px;
        letter-spacing: 0.5px;
    }
    .og-preview-title {
        font-size: 15px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .og-preview-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.4;
        height: 36px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* Twitter Card Preview */
    .twitter-preview-card {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .twitter-preview-img-container {
        position: relative;
        width: 100%;
        height: 220px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .twitter-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .twitter-preview-img-fallback {
        color: #94a3b8;
        font-size: 2.2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .twitter-preview-img-fallback span {
        font-size: 0.8rem;
        font-weight: 500;
    }
    .twitter-preview-content {
        padding: 12px 14px;
        border-top: 1px solid #f1f5f9;
    }
    .twitter-preview-domain {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 2px;
    }
    .twitter-preview-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
        line-height: 1.3;
    }
    .twitter-preview-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.35;
        height: 34px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* Counter Badges */
    .counter-badge {
        font-size: 0.72rem;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: bold;
    }
    .counter-ok {
        background-color: #d1fae5;
        color: #065f46;
    }
    .counter-warning {
        background-color: #ffedd5;
        color: #9a3412;
    }
    .counter-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }
</style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.seo.index') }}" class="text-decoration-none text-muted">SEO Settings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Configure</li>
@endsection

@section('content')
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('admin.seo.index') }}" class="btn btn-sm btn-light border"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <span class="badge bg-royal text-white font-monospace">PAGE IDENTIFIER: {{ $seo->page_identifier }}</span>
    </div>
    <h2 class="display-font mb-1 text-navy">Configure SEO & Metadata: <span class="text-royal">{{ $seo->page_name }}</span></h2>
    <p class="text-muted mb-0">Fine-tune target key phrases, metadata description limits, and custom graphics mappings to capture search indexing click-throughs.</p>
</div>

<form action="{{ route('admin.seo.update', $seo->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Form Fields Left Column -->
        <div class="col-lg-7 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4 mb-4">
                <ul class="nav nav-pills premium-pills mb-4" id="seoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general-pane" type="button" role="tab" aria-controls="general-pane" aria-selected="true">
                            <i class="fa-solid fa-circle-info me-2"></i>General Meta Tags
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="social-tab" data-bs-toggle="pill" data-bs-target="#social-pane" type="button" role="tab" aria-controls="social-pane" aria-selected="false">
                            <i class="fa-solid fa-share-nodes me-2"></i>Social Graph Tags
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="advanced-tab" data-bs-toggle="pill" data-bs-target="#advanced-pane" type="button" role="tab" aria-controls="advanced-pane" aria-selected="false">
                            <i class="fa-solid fa-code me-2"></i>Advanced SEO Block
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="seoTabsContent">
                    <!-- General SEO Details Tab -->
                    <div class="tab-pane fade show active" id="general-pane" role="tabpanel" aria-labelledby="general-tab" tabindex="0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="focus_keyword" class="form-label fw-bold text-navy small">Focus Target Keyword</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-key"></i></span>
                                    <input type="text" class="form-control" id="focus_keyword" name="focus_keyword" value="{{ old('focus_keyword', $seo->focus_keyword) }}" placeholder="e.g. cheap flights to europe">
                                </div>
                                <div class="form-text text-muted small" style="font-size:0.75rem;">Primary search phrase you wish to rank for on this page.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="robots_tag" class="form-label fw-bold text-navy small">Robots Meta Directives</label>
                                <select class="form-select" id="robots_tag" name="robots_tag" required>
                                    <option value="index, follow" {{ old('robots_tag', $seo->robots_tag) == 'index, follow' ? 'selected' : '' }}>INDEX, FOLLOW (Standard)</option>
                                    <option value="noindex, follow" {{ old('robots_tag', $seo->robots_tag) == 'noindex, follow' ? 'selected' : '' }}>NOINDEX, FOLLOW (Exclude listing)</option>
                                    <option value="index, nofollow" {{ old('robots_tag', $seo->robots_tag) == 'index, nofollow' ? 'selected' : '' }}>INDEX, NOFOLLOW (Do not pass link weight)</option>
                                    <option value="noindex, nofollow" {{ old('robots_tag', $seo->robots_tag) == 'noindex, nofollow' ? 'selected' : '' }}>NOINDEX, NOFOLLOW (Completely hidden)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="meta_title" class="form-label fw-bold text-navy small mb-0">Search Engine Title <span class="text-danger">*</span></label>
                                    <span id="title-counter" class="counter-badge counter-ok">0 / 60</span>
                                </div>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $seo->meta_title) }}" required>
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted small" style="font-size:0.75rem;">Keep it around 50-60 characters for complete display in Google results.</div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="meta_description" class="form-label fw-bold text-navy small mb-0">Search Engine Meta Description <span class="text-danger">*</span></label>
                                    <span id="desc-counter" class="counter-badge counter-ok">0 / 160</span>
                                </div>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="4" required>{{ old('meta_description', $seo->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted small" style="font-size:0.75rem;">Aim for 120-160 characters. Provide an attractive summary of page content.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="canonical_url" class="form-label fw-bold text-navy small">Canonical URL Override</label>
                                <input type="url" class="form-control @error('canonical_url') is-invalid @enderror" id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $seo->canonical_url) }}" placeholder="https://destinationfareways.com/page-slug">
                                @error('canonical_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted small" style="font-size:0.75rem;">Leave empty to default to page source path. Prevents duplicate content indexation issues.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="meta_keywords" class="form-label fw-bold text-navy small">Meta Keywords (Legacy)</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $seo->meta_keywords) }}" placeholder="e.g. airfares, discount tickets, reservations">
                            </div>
                        </div>
                    </div>

                    <!-- Social / Open Graph Media Settings Tab -->
                    <div class="tab-pane fade" id="social-pane" role="tabpanel" aria-labelledby="social-tab" tabindex="0">
                        <h5 class="fw-bold text-navy mb-3 pb-2 border-bottom"><i class="fa-brands fa-facebook text-primary me-2"></i>Facebook Open Graph</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="og_title" class="form-label fw-bold text-navy small">Open Graph Title</label>
                                <input type="text" class="form-control" id="og_title" name="og_title" value="{{ old('og_title', $seo->og_title) }}" placeholder="Leave blank to reuse standard Search Title">
                            </div>
                            <div class="col-12">
                                <label for="og_description" class="form-label fw-bold text-navy small">Open Graph Description</label>
                                <textarea class="form-control" id="og_description" name="og_description" rows="3" placeholder="Leave blank to reuse standard Meta Description">{{ old('og_description', $seo->og_description) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-navy small">Open Graph Graphic Banner (Recommended: 1200x630px)</label>
                                <div class="input-group">
                                    <input type="file" class="form-control @error('og_image') is-invalid @enderror" id="og_image" name="og_image" accept="image/*">
                                    @error('og_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if($seo->og_image)
                                    <div class="mt-2 small text-muted">
                                        <i class="fa-regular fa-image me-1"></i> Current Banner: <a href="{{ asset('storage/' . $seo->og_image) }}" target="_blank" class="text-royal font-monospace">{{ basename($seo->og_image) }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <h5 class="fw-bold text-navy mb-3 pb-2 border-bottom"><i class="fa-brands fa-twitter text-info me-2"></i>Twitter Cards Metadata</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="twitter_title" class="form-label fw-bold text-navy small">Twitter Display Title</label>
                                <input type="text" class="form-control" id="twitter_title" name="twitter_title" value="{{ old('twitter_title', $seo->twitter_title) }}" placeholder="Leave blank to reuse standard Search Title">
                            </div>
                            <div class="col-12">
                                <label for="twitter_description" class="form-label fw-bold text-navy small">Twitter Body Summary</label>
                                <textarea class="form-control" id="twitter_description" name="twitter_description" rows="3" placeholder="Leave blank to reuse standard Meta Description">{{ old('twitter_description', $seo->twitter_description) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-navy small">Twitter Card Graphic Banner (Recommended: 1024x512px)</label>
                                <div class="input-group">
                                    <input type="file" class="form-control @error('twitter_image') is-invalid @enderror" id="twitter_image" name="twitter_image" accept="image/*">
                                    @error('twitter_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if($seo->twitter_image)
                                    <div class="mt-2 small text-muted">
                                        <i class="fa-regular fa-image me-1"></i> Current Banner: <a href="{{ asset('storage/' . $seo->twitter_image) }}" target="_blank" class="text-royal font-monospace">{{ basename($seo->twitter_image) }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Text / Content Block Tab -->
                    <div class="tab-pane fade" id="advanced-pane" role="tabpanel" aria-labelledby="advanced-tab" tabindex="0">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="seo_content_block" class="form-label fw-bold text-navy small">SEO Footer Rich Content Block (Blade / Custom Layout Block)</label>
                                <textarea class="form-control font-monospace" id="seo_content_block" name="seo_content_block" rows="12" placeholder="Configure any custom HTML, footer content block or internal link arrays here..." style="font-size:0.85rem;">{{ old('seo_content_block', $seo->seo_content_block) }}</textarea>
                                <div class="form-text text-muted small" style="font-size:0.75rem;">Can include structured paragraphs, custom link anchors, or page-specific headings for optimized keyword density on page footers.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <button type="submit" class="btn btn-action rounded-pill px-5 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Save SEO Configuration
                </button>
                <a href="{{ route('admin.seo.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">Cancel</a>
            </div>
        </div>

        <!-- Sticky Preview Panel Right Column -->
        <div class="col-lg-5 col-xl-4">
            <div class="sticky-lg-top" style="top: 90px; z-index: 10;">
                <div class="d-flex flex-column gap-4">
                    <!-- Google Search Preview Widget -->
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold text-navy small mb-0"><i class="fa-brands fa-google me-1 text-primary"></i>Google Search Engine Preview</h6>
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.65rem;">Live Feed</span>
                        </div>
                        <div class="google-preview">
                            <div class="google-url-row">
                                <div class="google-favicon-placeholder">
                                    <i class="fa-solid fa-earth-americas" style="font-size: 9px;"></i>
                                </div>
                                <div class="google-url" id="google-preview-url">
                                    https://destinationfareways.com/{{ $seo->page_identifier }}
                                </div>
                                <div class="text-muted" style="font-size: 8px;"><i class="fa-solid fa-ellipsis-vertical"></i></div>
                            </div>
                            <a href="javascript:void(0)" class="google-title" id="google-preview-title">
                                {{ $seo->meta_title ?: 'Please enter a meta title...' }}
                            </a>
                            <div class="google-desc" id="google-preview-desc">
                                {{ $seo->meta_description ?: 'Please enter an engaging meta description here to see how searchers will perceive your website page in local engines.' }}
                            </div>
                        </div>
                    </div>

                    <!-- Facebook Open Graph Preview -->
                    <div>
                        <h6 class="fw-bold text-navy small mb-2"><i class="fa-brands fa-facebook me-1 text-primary"></i>Facebook Card Share Preview</h6>
                        <div class="og-preview-card">
                            <div class="og-preview-img-container">
                                @if($seo->og_image)
                                    <img src="{{ asset('storage/' . $seo->og_image) }}" class="og-preview-img" id="og-preview-image-el" alt="OG Preview">
                                @else
                                    <img src="" class="og-preview-img d-none" id="og-preview-image-el" alt="OG Preview">
                                    <div class="og-preview-img-fallback" id="og-preview-image-fallback">
                                        <i class="fa-solid fa-image"></i>
                                        <span>No Banner Selected</span>
                                    </div>
                                @endif
                            </div>
                            <div class="og-preview-content">
                                <div class="og-preview-domain">destinationfareways.com</div>
                                <div class="og-preview-title" id="og-preview-title-el">
                                    {{ $seo->og_title ?: ($seo->meta_title ?: 'Standard Title Placeholder') }}
                                </div>
                                <div class="og-preview-desc" id="og-preview-desc-el">
                                    {{ $seo->og_description ?: ($seo->meta_description ?: 'Standard Meta Description text will act as the fallback for Open Graph feeds.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Twitter Card Preview -->
                    <div>
                        <h6 class="fw-bold text-navy small mb-2"><i class="fa-brands fa-twitter me-1 text-info"></i>Twitter Card Stream Preview</h6>
                        <div class="twitter-preview-card">
                            <div class="twitter-preview-img-container">
                                @if($seo->twitter_image)
                                    <img src="{{ asset('storage/' . $seo->twitter_image) }}" class="twitter-preview-img" id="twitter-preview-image-el" alt="Twitter Preview">
                                @else
                                    <img src="" class="twitter-preview-img d-none" id="twitter-preview-image-el" alt="Twitter Preview">
                                    <div class="twitter-preview-img-fallback" id="twitter-preview-image-fallback">
                                        <i class="fa-solid fa-image"></i>
                                        <span>No Banner Selected</span>
                                    </div>
                                @endif
                            </div>
                            <div class="twitter-preview-content">
                                <div class="twitter-preview-domain">destinationfareways.com</div>
                                <div class="twitter-preview-title" id="twitter-preview-title-el">
                                    {{ $seo->twitter_title ?: ($seo->meta_title ?: 'Standard Title Placeholder') }}
                                </div>
                                <div class="twitter-preview-desc" id="twitter-preview-desc-el">
                                    {{ $seo->twitter_description ?: ($seo->meta_description ?: 'Standard Meta Description text will act as the fallback for Twitter Card feeds.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var baseSlug = "{{ $seo->page_identifier }}";

        // Character limit warnings and counters
        function updateCounters() {
            var titleVal = $('#meta_title').val();
            var descVal = $('#meta_description').val();
            
            var titleLen = titleVal.length;
            var descLen = descVal.length;

            // Update title badge
            $('#title-counter').text(titleLen + ' / 60');
            if (titleLen === 0) {
                $('#title-counter').removeClass().addClass('counter-badge counter-warning');
            } else if (titleLen <= 60) {
                $('#title-counter').removeClass().addClass('counter-badge counter-ok');
            } else {
                $('#title-counter').removeClass().addClass('counter-badge counter-danger');
            }

            // Update description badge
            $('#desc-counter').text(descLen + ' / 160');
            if (descLen === 0) {
                $('#desc-counter').removeClass().addClass('counter-badge counter-warning');
            } else if (descLen >= 120 && descLen <= 160) {
                $('#desc-counter').removeClass().addClass('counter-badge counter-ok');
            } else if (descLen < 120) {
                $('#desc-counter').removeClass().addClass('counter-badge counter-warning');
            } else {
                $('#desc-counter').removeClass().addClass('counter-badge counter-danger');
            }

            // Live updates for Google Snippet
            $('#google-preview-title').text(titleVal ? titleVal : 'Please enter a meta title...');
            $('#google-preview-desc').text(descVal ? descVal : 'Please enter an engaging meta description here to see how searchers will perceive your website page in local engines.');

            // Sync social graph titles
            var ogTitle = $('#og_title').val();
            var twitterTitle = $('#twitter_title').val();
            
            $('#og-preview-title-el').text(ogTitle ? ogTitle : (titleVal ? titleVal : 'Standard Title Placeholder'));
            $('#twitter-preview-title-el').text(twitterTitle ? twitterTitle : (titleVal ? titleVal : 'Standard Title Placeholder'));

            // Sync social graph descriptions
            var ogDesc = $('#og_description').val();
            var twitterDesc = $('#twitter_description').val();
            
            $('#og-preview-desc-el').text(ogDesc ? ogDesc : (descVal ? descVal : 'Standard Meta Description text will act as the fallback for Open Graph feeds.'));
            $('#twitter-preview-desc-el').text(twitterDesc ? twitterDesc : (descVal ? descVal : 'Standard Meta Description text will act as the fallback for Twitter Card feeds.'));
        }

        // Canonical URL update live
        function updateCanonical() {
            var canonVal = $('#canonical_url').val();
            if (canonVal) {
                $('#google-preview-url').text(canonVal);
            } else {
                $('#google-preview-url').text('https://destinationfareways.com/' + baseSlug);
            }
        }

        // Image upload previews
        function setupImagePreview(inputId, imgElId, fallbackId) {
            $('#' + inputId).on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#' + imgElId).attr('src', e.target.result).removeClass('d-none');
                        $('#' + fallbackId).addClass('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Initialize events
        $('#meta_title, #meta_description, #og_title, #og_description, #twitter_title, #twitter_description').on('input keyup change', function() {
            updateCounters();
        });

        $('#canonical_url').on('input keyup change', function() {
            updateCanonical();
        });

        setupImagePreview('og_image', 'og-preview-image-el', 'og-preview-image-fallback');
        setupImagePreview('twitter_image', 'twitter-preview-image-el', 'twitter-preview-image-fallback');

        // Initial trigger
        updateCounters();
        updateCanonical();
    });
</script>
@endsection
