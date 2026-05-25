@extends('layouts.admin')

@section('title', 'GDS / Flight API Settings')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">API Configuration</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">GDS & Flight API Configuration</h2>
    <p class="text-muted mb-0">Manage global flight inventory providers, API credentials, endpoints, fare markups, and synchronize booking databases.</p>
</div>

<div class="row g-4">
    <!-- Left Column: API Settings Form -->
    <div class="col-12 col-xl-8">
        <div class="card premium-card border-0 shadow-sm p-4">
            <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                <i class="fa-solid fa-network-wired text-warning me-2"></i>Provider Configuration
            </h5>

            <form action="{{ route('admin.api-settings.update') }}" method="POST">
                @csrf
                
                <div class="row g-3">
                    <!-- General Settings -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="provider" class="form-label fw-bold">API Provider Name</label>
                            <input type="text" class="form-control px-3" id="provider" name="provider" value="{{ old('provider', $apiSetting->provider) }}" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="base_url" class="form-label fw-bold">API Base Endpoint URL</label>
                            <input type="url" class="form-control px-3" id="base_url" name="base_url" value="{{ old('base_url', $apiSetting->base_url) }}" required>
                        </div>
                    </div>

                    <!-- Credentials with Masking -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="api_key" class="form-label fw-bold">API Key (Encrypted)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-navy"><i class="fa-solid fa-key"></i></span>
                                <input type="password" class="form-control px-3" id="api_key" name="api_key" value="{{ old('api_key', $apiSetting->api_key ? '••••••••••••••••' : '') }}" placeholder="Enter GDS Client ID / API Key">
                                <button class="btn btn-outline-secondary toggle-password" type="button"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="api_secret" class="form-label fw-bold">API Secret (Encrypted)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-navy"><i class="fa-solid fa-shield-halved"></i></span>
                                <input type="password" class="form-control px-3" id="api_secret" name="api_secret" value="{{ old('api_secret', $apiSetting->api_secret ? '••••••••••••••••' : '') }}" placeholder="Enter GDS API Secret">
                                <button class="btn btn-outline-secondary toggle-password" type="button"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Controls -->
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="currency" class="form-label fw-bold">Default Currency Code</label>
                            <input type="text" class="form-control px-3" id="currency" name="currency" value="{{ old('currency', $apiSetting->currency) }}" placeholder="e.g. USD" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="markup_percent" class="form-label fw-bold">Global Fare Markup (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control px-3" id="markup_percent" name="markup_percent" value="{{ old('markup_percent', $apiSetting->markup_percent) }}" required>
                                <span class="input-group-text bg-light text-navy">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="commission_percent" class="form-label fw-bold">Agent Commission (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control px-3" id="commission_percent" name="commission_percent" value="{{ old('commission_percent', $apiSetting->commission_percent) }}" required>
                                <span class="input-group-text bg-light text-navy">%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sandbox Mode and API status -->
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="mode" class="form-label fw-bold">Operational Mode</label>
                            <select class="form-select px-3" id="mode" name="mode" required>
                                <option value="sandbox" {{ old('mode', $apiSetting->mode) === 'sandbox' ? 'selected' : '' }}>Sandbox / Test Environment</option>
                                <option value="live" {{ old('mode', $apiSetting->mode) === 'live' ? 'selected' : '' }}>Live / Production Environment</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="api_status" class="form-label fw-bold">System Status</label>
                            <select class="form-select px-3" id="api_status" name="api_status" required>
                                <option value="active" {{ old('api_status', $apiSetting->api_status) === 'active' ? 'selected' : '' }}>Active (Online)</option>
                                <option value="inactive" {{ old('api_status', $apiSetting->api_status) === 'inactive' ? 'selected' : '' }}>Inactive (Offline)</option>
                                <option value="error" {{ old('api_status', $apiSetting->api_status) === 'error' ? 'selected' : '' }}>Error (Degraded)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Endpoints Segment -->
                    <div class="col-12">
                        <h6 class="fw-bold text-navy mt-4 border-bottom pb-1"><i class="fa-solid fa-code-fork me-2"></i>API Route Endpoints</h6>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="endpoint_search" class="form-label fw-bold">Flight Search Route</label>
                            <input type="text" class="form-control px-3 font-monospace" id="endpoint_search" name="endpoint_search" value="{{ old('endpoint_search', $apiSetting->endpoint_search) }}" required style="font-size:0.85rem;">
                        </div>
                        <div class="mb-3">
                            <label for="endpoint_booking" class="form-label fw-bold">Seat Booking Route</label>
                            <input type="text" class="form-control px-3 font-monospace" id="endpoint_booking" name="endpoint_booking" value="{{ old('endpoint_booking', $apiSetting->endpoint_booking) }}" required style="font-size:0.85rem;">
                        </div>
                        <div class="mb-3">
                            <label for="endpoint_fare_rules" class="form-label fw-bold">Fare Pricing & Rules Route</label>
                            <input type="text" class="form-control px-3 font-monospace" id="endpoint_fare_rules" name="endpoint_fare_rules" value="{{ old('endpoint_fare_rules', $apiSetting->endpoint_fare_rules) }}" required style="font-size:0.85rem;">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="endpoint_cancellation" class="form-label fw-bold">Booking Cancellation Route</label>
                            <input type="text" class="form-control px-3 font-monospace" id="endpoint_cancellation" name="endpoint_cancellation" value="{{ old('endpoint_cancellation', $apiSetting->endpoint_cancellation) }}" required style="font-size:0.85rem;">
                        </div>
                        <div class="mb-3">
                            <label for="endpoint_refund" class="form-label fw-bold">Ticket Refund Route</label>
                            <input type="text" class="form-control px-3 font-monospace" id="endpoint_refund" name="endpoint_refund" value="{{ old('endpoint_refund', $apiSetting->endpoint_refund) }}" required style="font-size:0.85rem;">
                        </div>
                        <div class="mb-3">
                            <label for="endpoint_webhook" class="form-label fw-bold">Push Notifications Webhook Route</label>
                            <input type="text" class="form-control px-3 font-monospace" id="endpoint_webhook" name="endpoint_webhook" value="{{ old('endpoint_webhook', $apiSetting->endpoint_webhook) }}" required style="font-size:0.85rem;">
                        </div>
                    </div>
                </div>

                <div class="border-top pt-3 mt-4 text-end">
                    <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Save Configuration</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Interactive Diagnostic Terminal -->
    <div class="col-12 col-xl-4">
        <div class="card premium-card border-0 shadow-sm p-4 h-100 d-flex flex-column bg-navy text-white overflow-hidden">
            <h5 class="fw-bold text-warning mb-3 border-bottom border-secondary pb-2">
                <i class="fa-solid fa-terminal me-2"></i>Diagnostic Terminal
            </h5>
            
            <p class="text-light small mb-3">Execute a live ping checklist on your primary API provider to verify credentials, DNS, and latency status.</p>

            <button type="button" class="btn btn-warning w-100 fw-bold mb-4 py-2" id="test-connection-btn">
                <i class="fa-solid fa-bolt me-2"></i>Test GDS API Connection
            </button>

            <div class="flex-grow-1 d-flex flex-column">
                <span class="small text-muted font-monospace d-block mb-1">TERMINAL LOG OUTPUT</span>
                <div class="card bg-black border-secondary p-3 font-monospace flex-grow-1 text-success d-flex flex-column" style="font-size: 0.8rem; min-height: 250px; border-radius: 8px;">
                    <div id="terminal-content" class="flex-grow-1 overflow-y-auto mb-2" style="white-space: pre-wrap; height: 100%;">{{ $apiSetting->last_error_log ?? "Terminal initialized.\nReady to run connectivity check..." }}</div>
                    
                    <div id="terminal-spinner" class="d-none text-center my-auto">
                        <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                        <span class="ms-2 text-warning">Pinging target endpoint...</span>
                    </div>
                </div>
            </div>

            <!-- Last Sync information badge -->
            <div class="mt-3 p-2 bg-dark rounded border border-secondary d-flex align-items-center justify-content-between">
                <span class="small text-light">Last Successful Sync</span>
                <span class="badge bg-warning text-navy font-monospace small" id="last-sync-badge">
                    {{ $apiSetting->last_sync_at ? $apiSetting->last_sync_at->diffForHumans() : 'Never' }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Toggle password mask
        $('.toggle-password').on('click', function() {
            var input = $(this).closest('.input-group').find('input');
            var icon = $(this).find('i');
            
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-regular fa-eye').addClass('fa-solid fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-solid fa-eye-slash').addClass('fa-regular fa-eye');
            }
        });

        // Test API connection AJAX trigger
        $('#test-connection-btn').on('click', function() {
            var btn = $(this);
            var termContent = $('#terminal-content');
            var termSpinner = $('#terminal-spinner');

            // Set loading state
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Checking connection...');
            termContent.addClass('d-none');
            termSpinner.removeClass('d-none');

            $.ajax({
                url: "{{ route('admin.api-settings.test') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    termContent.text(data.log).removeClass('d-none');
                    termSpinner.addClass('d-none');
                    btn.prop('disabled', false).html('<i class="fa-solid fa-bolt me-2"></i>Test GDS API Connection');
                    
                    // Show success toast dynamically if it exists
                    var toastHtml = '<div class="toast show align-items-center text-white bg-success border-0 shadow-lg" role="alert"><div class="d-flex"><div class="toast-body d-flex align-items-center"><i class="fa-solid fa-circle-check fs-5 me-2"></i>' + data.message + '</div></div></div>';
                    $('.toast-container').append(toastHtml);
                    setTimeout(function() { $('.toast').last().fadeOut(500, function() { $(this).remove(); }); }, 4000);

                    // Update last sync badge
                    $('#last-sync-badge').text('Just now');
                },
                error: function(xhr) {
                    var errorMsg = "API Call Failed!\n";
                    if (xhr.responseJSON && xhr.responseJSON.log) {
                        errorMsg += xhr.responseJSON.log;
                    } else {
                        errorMsg += "HTTP Error: " + xhr.status + " - " + xhr.statusText;
                    }
                    termContent.text(errorMsg).removeClass('d-none');
                    termSpinner.addClass('d-none');
                    btn.prop('disabled', false).html('<i class="fa-solid fa-bolt me-2"></i>Test GDS API Connection');

                    var toastHtml = '<div class="toast show align-items-center text-white bg-danger border-0 shadow-lg" role="alert"><div class="d-flex"><div class="toast-body d-flex align-items-center"><i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>API connection failed!</div></div></div>';
                    $('.toast-container').append(toastHtml);
                    setTimeout(function() { $('.toast').last().fadeOut(500, function() { $(this).remove(); }); }, 4000);
                }
            });
        });
    });
</script>
@endsection
