@extends('layouts.admin')

@section('title', 'Global Settings')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Settings</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Global Site Settings</h2>
    <p class="text-muted mb-0">Customize site branding assets, contact information, social links, tracking codes, and operational status.</p>
</div>

<div class="card premium-card border-0 shadow-sm p-4">
    <!-- Tab Navigations -->
    <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="settingsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active px-4 py-2" id="info-tab" data-bs-toggle="pill" data-bs-target="#info" type="button" role="tab"><i class="fa-solid fa-circle-info me-2"></i>Site Info & Branding</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-2" id="socials-tab" data-bs-toggle="pill" data-bs-target="#socials" type="button" role="tab"><i class="fa-solid fa-share-nodes me-2"></i>Social Profiles</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-2" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics" type="button" role="tab"><i class="fa-solid fa-chart-line me-2"></i>Analytics & Code</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-2" id="maintenance-tab" data-bs-toggle="pill" data-bs-target="#maintenance" type="button" role="tab"><i class="fa-solid fa-screwdriver-wrench me-2"></i>Maintenance & Copyright</button>
        </li>
    </ul>

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="tab-content" id="settingsTabContent">
            
            <!-- Tab 1: Site Info & Branding -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="site_name" class="form-label fw-bold">Site Brand Name</label>
                            <input type="text" class="form-control px-3" id="site_name" name="site_name" value="{{ old('site_name', $setting->site_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tagline" class="form-label fw-bold">Site Tagline</label>
                            <input type="text" class="form-control px-3" id="tagline" name="tagline" value="{{ old('tagline', $setting->tagline) }}">
                        </div>
                        <div class="mb-3">
                            <label for="primary_email" class="form-label fw-bold">Primary Email</label>
                            <input type="email" class="form-control px-3" id="primary_email" name="primary_email" value="{{ old('primary_email', $setting->primary_email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="secondary_email" class="form-label fw-bold">Secondary Email</label>
                            <input type="email" class="form-control px-3" id="secondary_email" name="secondary_email" value="{{ old('secondary_email', $setting->secondary_email) }}">
                        </div>
                        <div class="mb-3">
                            <label for="primary_phone" class="form-label fw-bold">Primary Phone</label>
                            <input type="text" class="form-control px-3" id="primary_phone" name="primary_phone" value="{{ old('primary_phone', $setting->primary_phone) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="secondary_phone" class="form-label fw-bold">Secondary Phone</label>
                            <input type="text" class="form-control px-3" id="secondary_phone" name="secondary_phone" value="{{ old('secondary_phone', $setting->secondary_phone) }}">
                        </div>
                        <div class="mb-3">
                            <label for="whatsapp" class="form-label fw-bold">WhatsApp Direct Link Number</label>
                            <input type="text" class="form-control px-3" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}">
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 border-start ps-md-4">
                        <div class="mb-3">
                            <label for="address" class="form-label fw-bold">Street Address</label>
                            <input type="text" class="form-control px-3" id="address" name="address" value="{{ old('address', $setting->address) }}" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="city" class="form-label fw-bold">City</label>
                                <input type="text" class="form-control px-3" id="city" name="city" value="{{ old('city', $setting->city) }}" required>
                            </div>
                            <div class="col-6">
                                <label for="state" class="form-label fw-bold">State</label>
                                <input type="text" class="form-control px-3" id="state" name="state" value="{{ old('state', $setting->state) }}" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label for="country" class="form-label fw-bold">Country</label>
                                <input type="text" class="form-control px-3" id="country" name="country" value="{{ old('country', $setting->country) }}" required>
                            </div>
                            <div class="col-6">
                                <label for="zip" class="form-label fw-bold">Zip Code</label>
                                <input type="text" class="form-control px-3" id="zip" name="zip" value="{{ old('zip', $setting->zip) }}" required>
                            </div>
                        </div>

                        <!-- Logo Selection and preview -->
                        <div class="mb-3">
                            <label for="logo" class="form-label fw-bold">Branding Logo (max 2MB)</label>
                            <input type="file" class="form-control image-preview-trigger" id="logo" name="logo" data-preview-id="logo-preview-box">
                            <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 90px;">
                                <img src="{{ $setting->logo ? asset('storage/'.$setting->logo) : '' }}" id="logo-preview-box" alt="Logo Preview" class="img-fluid h-100 {{ $setting->logo ? '' : 'd-none' }}">
                                <span class="text-muted small py-3 {{ $setting->logo ? 'd-none' : '' }}" id="logo-preview-placeholder">No image selected</span>
                            </div>
                        </div>

                        <!-- Favicon selection and preview -->
                        <div class="mb-3">
                            <label for="favicon" class="form-label fw-bold">Branding Favicon (max 2MB)</label>
                            <input type="file" class="form-control image-preview-trigger" id="favicon" name="favicon" data-preview-id="favicon-preview-box">
                            <div class="mt-2 p-2 border rounded text-center bg-light d-flex align-items-center justify-content-center" style="height: 60px;">
                                <img src="{{ $setting->favicon ? asset('storage/'.$setting->favicon) : '' }}" id="favicon-preview-box" alt="Favicon Preview" class="img-fluid h-100 {{ $setting->favicon ? '' : 'd-none' }}">
                                <span class="text-muted small py-2 {{ $setting->favicon ? 'd-none' : '' }}" id="favicon-preview-placeholder">No image selected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Social Profiles -->
            <div class="tab-pane fade" id="socials" role="tabpanel">
                <div class="row g-3" style="max-width: 600px;">
                    <div class="mb-3">
                        <label for="social_facebook" class="form-label fw-bold"><i class="fa-brands fa-square-facebook text-primary me-2 fs-5"></i>Facebook URL</label>
                        <input type="url" class="form-control px-3" id="social_facebook" name="social_facebook" value="{{ old('social_facebook', $setting->social_facebook) }}" placeholder="https://facebook.com/brandname">
                    </div>
                    <div class="mb-3">
                        <label for="social_twitter" class="form-label fw-bold"><i class="fa-brands fa-square-twitter text-info me-2 fs-5"></i>Twitter URL</label>
                        <input type="url" class="form-control px-3" id="social_twitter" name="social_twitter" value="{{ old('social_twitter', $setting->social_twitter) }}" placeholder="https://twitter.com/brandname">
                    </div>
                    <div class="mb-3">
                        <label for="social_instagram" class="form-label fw-bold"><i class="fa-brands fa-instagram text-danger me-2 fs-5"></i>Instagram URL</label>
                        <input type="url" class="form-control px-3" id="social_instagram" name="social_instagram" value="{{ old('social_instagram', $setting->social_instagram) }}" placeholder="https://instagram.com/brandname">
                    </div>
                    <div class="mb-3">
                        <label for="social_linkedin" class="form-label fw-bold"><i class="fa-brands fa-linkedin text-primary me-2 fs-5"></i>LinkedIn Company URL</label>
                        <input type="url" class="form-control px-3" id="social_linkedin" name="social_linkedin" value="{{ old('social_linkedin', $setting->social_linkedin) }}" placeholder="https://linkedin.com/company/brandname">
                    </div>
                    <div class="mb-3">
                        <label for="social_youtube" class="form-label fw-bold"><i class="fa-brands fa-youtube text-danger me-2 fs-5"></i>YouTube Channel URL</label>
                        <input type="url" class="form-control px-3" id="social_youtube" name="social_youtube" value="{{ old('social_youtube', $setting->social_youtube) }}" placeholder="https://youtube.com/c/brandname">
                    </div>
                </div>
            </div>

            <!-- Tab 3: Analytics & Code -->
            <div class="tab-pane fade" id="analytics" role="tabpanel">
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="analytics_google_id" class="form-label fw-bold"><i class="fa-solid fa-chart-line text-success me-2"></i>Google Analytics Tag ID</label>
                            <input type="text" class="form-control px-3" id="analytics_google_id" name="analytics_google_id" value="{{ old('analytics_google_id', $setting->analytics_google_id) }}" placeholder="G-XXXXXXXXXX">
                        </div>
                        <div class="mb-3">
                            <label for="analytics_facebook_pixel" class="form-label fw-bold"><i class="fa-solid fa-code text-primary me-2"></i>Facebook Pixel ID</label>
                            <input type="text" class="form-control px-3" id="analytics_facebook_pixel" name="analytics_facebook_pixel" value="{{ old('analytics_facebook_pixel', $setting->analytics_facebook_pixel) }}" placeholder="1234567890">
                        </div>
                        <div class="mb-3">
                            <label for="analytics_custom_code" class="form-label fw-bold">Custom Javascript Analytics Block</label>
                            <textarea class="form-control font-monospace" id="analytics_custom_code" name="analytics_custom_code" rows="4" style="font-size: 0.85rem;" placeholder="<script>... analytics tracking scripts ...</script>">{{ old('analytics_custom_code', $setting->analytics_custom_code) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 border-start ps-md-4">
                        <div class="mb-3">
                            <label for="header_scripts" class="form-label fw-bold">Additional Header Scripts (inside &lt;head&gt;)</label>
                            <textarea class="form-control font-monospace" id="header_scripts" name="header_scripts" rows="4" style="font-size: 0.85rem;" placeholder="<!-- Header script attachments -->">{{ old('header_scripts', $setting->header_scripts) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="footer_scripts" class="form-label fw-bold">Additional Footer Scripts (before &lt;/body&gt;)</label>
                            <textarea class="form-control font-monospace" id="footer_scripts" name="footer_scripts" rows="4" style="font-size: 0.85rem;" placeholder="<!-- Footer script attachments -->">{{ old('footer_scripts', $setting->footer_scripts) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Maintenance & Copyright -->
            <div class="tab-pane fade" id="maintenance" role="tabpanel">
                <div class="row g-3" style="max-width: 600px;">
                    <!-- Maintenance Mode Selector Switch (toggle switch not checkbox) -->
                    <div class="mb-4 p-3 rounded border bg-light d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1 text-navy fw-bold"><i class="fa-solid fa-circle-exclamation text-danger me-2"></i>Active Maintenance Mode</h6>
                            <span class="text-muted small">Restrict live site access and display a maintenance warning template to consumers.</span>
                        </div>
                        <div class="form-check form-switch fs-5">
                            <input type="hidden" name="maintenance_mode" value="inactive">
                            <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance_mode_switch" value="active" {{ old('maintenance_mode', $setting->maintenance_mode) === 'active' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="copyright" class="form-label fw-bold">Footer Copyright Text</label>
                        <input type="text" class="form-control px-3" id="copyright" name="copyright" value="{{ old('copyright', $setting->copyright) }}">
                    </div>
                </div>
            </div>

        </div>

        <div class="border-top pt-3 mt-4 text-end">
            <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Save Settings</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Image preview triggers
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
