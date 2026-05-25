@extends('layouts.admin')

@section('title', 'Call & Floating Buttons Configuration')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Call Settings</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Call & WhatsApp Buttons Configuration</h2>
    <p class="text-muted mb-0">Configure your call-to-actions, phone prompts, and floatable customer service widgets. Changes render in real-time below.</p>
</div>

<div class="row g-4">
    <!-- Left Column: Form Settings -->
    <div class="col-12 col-xl-7">
        <div class="card premium-card border-0 shadow-sm p-4 h-100">
            <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                <i class="fa-solid fa-sliders text-warning me-2"></i>Configure Buttons
            </h5>

            <form action="{{ route('admin.call-settings.update') }}" method="POST">
                @csrf
                
                <div class="row g-3">
                    <!-- Numbers -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Primary Phone Line</label>
                            <input type="text" class="form-control px-3 live-preview-trigger" id="phone" name="phone" value="{{ old('phone', $callSetting->phone) }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="whatsapp" class="form-label fw-bold">WhatsApp Hotline Number</label>
                            <input type="text" class="form-control px-3 live-preview-trigger" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $callSetting->whatsapp) }}">
                        </div>
                    </div>

                    <!-- Label & Colors -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="button_text" class="form-label fw-bold">Floating Button Label</label>
                            <input type="text" class="form-control px-3 live-preview-trigger" id="button_text" name="button_text" value="{{ old('button_text', $callSetting->button_text) }}" required>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="mb-3">
                            <label for="button_color" class="form-label fw-bold">Button Color</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color border-end-0 px-2" id="button_color" name="button_color" value="{{ old('button_color', $callSetting->button_color) }}" required style="width: 50px;">
                                <input type="text" class="form-control font-monospace ps-2 live-preview-trigger" id="button_color_hex" value="{{ old('button_color', $callSetting->button_color) }}" style="font-size: 0.9rem;">
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="mb-3">
                            <label for="text_color" class="form-label fw-bold">Text Color</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color border-end-0 px-2" id="text_color" name="text_color" value="{{ old('text_color', $callSetting->text_color) }}" required style="width: 50px;">
                                <input type="text" class="form-control font-monospace ps-2 live-preview-trigger" id="text_color_hex" value="{{ old('text_color', $callSetting->text_color) }}" style="font-size: 0.9rem;">
                            </div>
                        </div>
                    </div>

                    <!-- Floating Position & Toggle -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="floating_position" class="form-label fw-bold">Floating Widget Position</label>
                            <select class="form-select px-3 live-preview-trigger" id="floating_position" name="floating_position" required>
                                <option value="bottom-right" {{ old('floating_position', $callSetting->floating_position) === 'bottom-right' ? 'selected' : '' }}>Bottom Right</option>
                                <option value="bottom-left" {{ old('floating_position', $callSetting->floating_position) === 'bottom-left' ? 'selected' : '' }}>Bottom Left</option>
                                <option value="top-right" {{ old('floating_position', $callSetting->floating_position) === 'top-right' ? 'selected' : '' }}>Top Right</option>
                                <option value="top-left" {{ old('floating_position', $callSetting->floating_position) === 'top-left' ? 'selected' : '' }}>Top Left</option>
                            </select>
                        </div>
                    </div>

                    <!-- Header/Footer CTA Banner Settings -->
                    <div class="col-12">
                        <h6 class="fw-bold text-navy mt-3 border-bottom pb-1">Header/Sidebar Promo CTA Block</h6>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="cta_text" class="form-label fw-bold">CTA Primary Headline Text</label>
                            <input type="text" class="form-control px-3 live-preview-trigger" id="cta_text" name="cta_text" value="{{ old('cta_text', $callSetting->cta_text) }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="cta_phone" class="form-label fw-bold">CTA Hotline Number</label>
                            <input type="text" class="form-control px-3 live-preview-trigger" id="cta_phone" name="cta_phone" value="{{ old('cta_phone', $callSetting->cta_phone) }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="cta_subtext" class="form-label fw-bold">CTA Discount Subtext</label>
                            <input type="text" class="form-control px-3 live-preview-trigger" id="cta_subtext" name="cta_subtext" value="{{ old('cta_subtext', $callSetting->cta_subtext) }}">
                        </div>
                    </div>

                    <!-- Display Switches -->
                    <div class="col-12">
                        <h6 class="fw-bold text-navy mt-3 border-bottom pb-1">Display & Visibility Controls</h6>
                    </div>

                    <div class="col-12 g-3">
                        <div class="row row-cols-1 row-cols-md-2 g-2">
                            <!-- Toggle Widget Status -->
                            <div class="col">
                                <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-light">
                                    <span class="small fw-bold text-navy">Global Status (Active/Inactive)</span>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="status" value="0">
                                        <input class="form-check-input live-preview-trigger" type="checkbox" name="status" id="status" value="1" {{ old('status', $callSetting->status) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <!-- Toggle WhatsApp button -->
                            <div class="col">
                                <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-light">
                                    <span class="small fw-bold text-navy">Enable WhatsApp Floating Line</span>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="toggle_whatsapp" value="0">
                                        <input class="form-check-input live-preview-trigger" type="checkbox" name="toggle_whatsapp" id="toggle_whatsapp" value="1" {{ old('toggle_whatsapp', $callSetting->toggle_whatsapp) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <!-- Toggle Floating Mobile -->
                            <div class="col">
                                <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-light">
                                    <span class="small fw-bold text-navy">Enable Mobile Floating Button</span>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="toggle_mobile_floating" value="0">
                                        <input class="form-check-input live-preview-trigger" type="checkbox" name="toggle_mobile_floating" id="toggle_mobile_floating" value="1" {{ old('toggle_mobile_floating', $callSetting->toggle_mobile_floating) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <!-- Toggle Desktop Widget -->
                            <div class="col">
                                <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-light">
                                    <span class="small fw-bold text-navy">Enable Desktop Floating Button</span>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="toggle_desktop" value="0">
                                        <input class="form-check-input live-preview-trigger" type="checkbox" name="toggle_desktop" id="toggle_desktop" value="1" {{ old('toggle_desktop', $callSetting->toggle_desktop) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <!-- Toggle Header -->
                            <div class="col">
                                <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-light">
                                    <span class="small fw-bold text-navy">Display Call Hotline in Header</span>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="toggle_header" value="0">
                                        <input class="form-check-input live-preview-trigger" type="checkbox" name="toggle_header" id="toggle_header" value="1" {{ old('toggle_header', $callSetting->toggle_header) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <!-- Toggle Footer -->
                            <div class="col">
                                <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-light">
                                    <span class="small fw-bold text-navy">Display Call Hotline in Footer</span>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="toggle_footer" value="0">
                                        <input class="form-check-input live-preview-trigger" type="checkbox" name="toggle_footer" id="toggle_footer" value="1" {{ old('toggle_footer', $callSetting->toggle_footer) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-3 mt-4 text-end">
                    <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Save Call Settings</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Interactive Live Preview Block -->
    <div class="col-12 col-xl-5">
        <div class="card premium-card border-0 shadow-sm p-4 d-flex flex-column h-100 bg-navy text-white overflow-hidden position-relative" style="min-height: 450px;">
            <h5 class="fw-bold text-warning mb-4 border-bottom border-secondary pb-2 z-3">
                <i class="fa-regular fa-eye me-2"></i>Live CSS Widget Preview
            </h5>
            
            <p class="text-light small mb-3 z-3">This panel simulates how the premium floating widget renders on mobile/desktop. Interact and customize colors to see changes instantly.</p>

            <!-- Mock Header Call Link -->
            <div id="preview-header-block" class="p-2 border border-secondary border-dashed rounded text-center mb-3 z-3 bg-opacity-10 bg-white">
                <span class="small text-muted d-block font-monospace mb-1">MOCK HEADER OUTLET</span>
                <a href="#" class="text-white text-decoration-none fw-bold small"><i class="fa-solid fa-phone text-warning me-1"></i> <span id="prev-header-phone">+1 (800) 555-0199</span></a>
            </div>

            <!-- Mock Promo CTA Block -->
            <div id="preview-cta-block" class="card bg-dark border border-secondary p-3 mb-4 z-3 text-start">
                <span class="small text-warning d-block font-monospace mb-1">HOTLINE PROMO BLOCK</span>
                <h6 class="fw-bold mb-1" id="prev-cta-title">Speak with a Travel Expert</h6>
                <div class="fs-4 text-warning fw-bold mb-1" id="prev-cta-phone">+1 (800) 555-0199</div>
                <p class="small text-light mb-0" id="prev-cta-subtext" style="font-size: 0.8rem;">Get exclusive phone-only discounts up to 30% off!</p>
            </div>

            <div class="flex-grow-1 z-3"></div>

            <!-- Floating Preview Button Simulation Area -->
            <div id="floating-simulator-container" class="position-relative border border-secondary border-dashed rounded w-100 p-3 text-center bg-black bg-opacity-20" style="height: 120px;">
                <span class="position-absolute top-0 start-0 m-2 badge bg-secondary font-monospace small">WIDGET PLACEMENT (MOCK SCREEN)</span>
                
                <!-- Floating Call Button Mock -->
                <div id="mock-floating-widget" class="position-absolute p-2 rounded-pill shadow-lg d-flex align-items-center gap-2 cursor-pointer transition-all" style="cursor: pointer;">
                    <div id="mock-phone-badge" class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: rgba(255,255,255,0.25);">
                        <i class="fa-solid fa-phone animate-shake fs-6"></i>
                    </div>
                    <span class="fw-bold px-1" id="prev-widget-text" style="font-size: 0.85rem; letter-spacing: 0.5px;">Call Now</span>
                </div>

                <!-- Floating WhatsApp Mock -->
                <div id="mock-whatsapp-widget" class="position-absolute p-2 rounded-circle shadow-lg bg-success text-white d-flex align-items-center justify-content-center cursor-pointer" style="width: 44px; height: 44px; transition: all 0.3s; cursor: pointer;">
                    <i class="fa-brands fa-whatsapp fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
    }
    .animate-shake {
        animation: shake 2.5s infinite;
    }
    @keyframes shake {
        0%, 100% { transform: rotate(0deg); }
        10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
        20%, 40%, 60%, 80% { transform: rotate(10deg); }
    }
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Sync color pickers with hex inputs
        $('#button_color').on('input', function() {
            $('#button_color_hex').val($(this).val().toUpperCase());
            updateLivePreview();
        });
        $('#button_color_hex').on('input', function() {
            var val = $(this).val();
            if (val.match(/^#[0-9A-F]{6}$/i)) {
                $('#button_color').val(val);
                updateLivePreview();
            }
        });

        $('#text_color').on('input', function() {
            $('#text_color_hex').val($(this).val().toUpperCase());
            updateLivePreview();
        });
        $('#text_color_hex').on('input', function() {
            var val = $(this).val();
            if (val.match(/^#[0-9A-F]{6}$/i)) {
                $('#text_color').val(val);
                updateLivePreview();
            }
        });

        // Trigger updates on standard inputs
        $('.live-preview-trigger').on('input change', function() {
            updateLivePreview();
        });

        function updateLivePreview() {
            // Numbers & Text
            var phone = $('#phone').val();
            var btnText = $('#button_text').val();
            var btnColor = $('#button_color').val();
            var txtColor = $('#text_color').val();
            var position = $('#floating_position').val();
            
            var ctaText = $('#cta_text').val();
            var ctaPhone = $('#cta_phone').val();
            var ctaSubtext = $('#cta_subtext').val();

            // Status toggles
            var statusActive = $('#status').is(':checked');
            var isWhatsappEnabled = $('#toggle_whatsapp').is(':checked');
            var isHeaderEnabled = $('#toggle_header').is(':checked');
            
            // Header display
            $('#prev-header-phone').text(phone);
            if (isHeaderEnabled) {
                $('#preview-header-block').slideDown(250);
            } else {
                $('#preview-header-block').slideUp(250);
            }

            // CTA Block display
            $('#prev-cta-title').text(ctaText);
            $('#prev-cta-phone').text(ctaPhone);
            $('#prev-cta-subtext').text(ctaSubtext);
            if (ctaText || ctaPhone) {
                $('#preview-cta-block').slideDown(250);
            } else {
                $('#preview-cta-block').slideUp(250);
            }

            // Widget setup
            if (statusActive) {
                $('#floating-simulator-container').fadeTo(200, 1.0);
            } else {
                $('#floating-simulator-container').fadeTo(200, 0.4);
            }

            // Apply widget colors
            $('#mock-floating-widget').css({
                'background-color': btnColor,
                'color': txtColor
            });
            $('#prev-widget-text').text(btnText);

            // Apply positions within mock screen
            var styleObj = { top: 'auto', bottom: 'auto', left: 'auto', right: 'auto' };
            var waStyleObj = { top: 'auto', bottom: 'auto', left: 'auto', right: 'auto' };

            if (position.includes('bottom')) {
                styleObj.bottom = '15px';
                waStyleObj.bottom = '15px';
            } else {
                styleObj.top = '15px';
                waStyleObj.top = '15px';
            }

            if (position.includes('right')) {
                styleObj.right = '15px';
                waStyleObj.right = '70px'; // Shift WhatsApp to the left of the phone button
            } else {
                styleObj.left = '15px';
                waStyleObj.left = '145px'; // Shift WhatsApp to the right of the phone button
            }

            $('#mock-floating-widget').css(styleObj);
            
            if (isWhatsappEnabled) {
                $('#mock-whatsapp-widget').show().css(waStyleObj);
            } else {
                $('#mock-whatsapp-widget').hide();
            }
        }

        // Initialize preview on load
        updateLivePreview();
    });
</script>
@endsection
