@extends('layouts.admin')

@section('title', 'Edit Structured Schema')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.schema.index') }}" class="text-decoration-none text-muted">Schema Settings</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('admin.schema.index') }}" class="btn btn-sm btn-light border"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    <h2 class="display-font mb-1 text-navy">Edit Structured Schema Markup</h2>
    <p class="text-muted mb-0">Modify, re-evaluate, and re-validate JSON-LD structured snippets assigned to your application landing pages.</p>
</div>

<form action="{{ route('admin.schema.update', $schema->id) }}" method="POST" id="schema-form">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Form inputs Left Column -->
        <div class="col-lg-7">
            <div class="card premium-card border-0 shadow-sm p-4">
                <div class="row g-3">
                    <!-- Page Identifier -->
                    <div class="col-md-6">
                        <label for="page_identifier_select" class="form-label fw-bold text-navy small">Target Page Scope <span class="text-danger">*</span></label>
                        <select class="form-select @error('page_identifier') is-invalid @enderror" id="page_identifier_select" required>
                            <option value="">-- Select Target Page --</option>
                            @foreach($pages as $key => $name)
                                <option value="{{ $key }}" {{ old('page_identifier', $schema->page_identifier) == $key ? 'selected' : '' }}>{{ $name }} ({{ $key }})</option>
                            @endforeach
                            <option value="custom" {{ old('page_identifier', $schema->page_identifier) && !array_key_exists(old('page_identifier', $schema->page_identifier), $pages) ? 'selected' : '' }}>[Other... Create Custom Identifier]</option>
                        </select>
                        <input type="hidden" name="page_identifier" id="page_identifier_hidden" value="{{ old('page_identifier', $schema->page_identifier) }}">
                        
                        <div class="mt-2 d-none" id="custom_page_identifier_container">
                            <label for="custom_page_identifier" class="form-label fw-semibold text-muted small">Type Custom Identifier (slug-friendly)</label>
                            <input type="text" class="form-control" id="custom_page_identifier" placeholder="e.g. europe-tours-landing" value="{{ old('page_identifier', $schema->page_identifier) && !array_key_exists(old('page_identifier', $schema->page_identifier), $pages) ? old('page_identifier', $schema->page_identifier) : '' }}">
                        </div>
                        @error('page_identifier')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Schema Type -->
                    <div class="col-md-6">
                        <label for="schema_type_select" class="form-label fw-bold text-navy small">Schema Structure Type <span class="text-danger">*</span></label>
                        @php
                            $standardTypes = ['Organization','WebSite','LocalBusiness','TouristDestination','FAQPage','BreadcrumbList','FlightReservation'];
                            $isCustomType = !in_array(old('schema_type', $schema->schema_type), $standardTypes);
                        @endphp
                        <select class="form-select @error('schema_type') is-invalid @enderror" id="schema_type_select" required>
                            <option value="">-- Select Structure --</option>
                            <option value="Organization" {{ old('schema_type', $schema->schema_type) == 'Organization' ? 'selected' : '' }}>Organization Graph (Corporate Identity)</option>
                            <option value="WebSite" {{ old('schema_type', $schema->schema_type) == 'WebSite' ? 'selected' : '' }}>WebSite Scope (Internal Search Bar)</option>
                            <option value="LocalBusiness" {{ old('schema_type', $schema->schema_type) == 'LocalBusiness' ? 'selected' : '' }}>LocalBusiness (Contact Coordinates)</option>
                            <option value="TouristDestination" {{ old('schema_type', $schema->schema_type) == 'TouristDestination' ? 'selected' : '' }}>TouristDestination (Travel Guides)</option>
                            <option value="FAQPage" {{ old('schema_type', $schema->schema_type) == 'FAQPage' ? 'selected' : '' }}>FAQPage (Question Drops)</option>
                            <option value="BreadcrumbList" {{ old('schema_type', $schema->schema_type) == 'BreadcrumbList' ? 'selected' : '' }}>BreadcrumbList (URL Pathing)</option>
                            <option value="FlightReservation" {{ old('schema_type', $schema->schema_type) == 'FlightReservation' ? 'selected' : '' }}>FlightReservation (Booking Markups)</option>
                            <option value="custom" {{ $isCustomType ? 'selected' : '' }}>[Other... Type Custom Type]</option>
                        </select>
                        <input type="hidden" name="schema_type" id="schema_type_hidden" value="{{ old('schema_type', $schema->schema_type) }}">

                        <div class="mt-2 d-none" id="custom_schema_type_container">
                            <label for="custom_schema_type" class="form-label fw-semibold text-muted small">Type Custom Schema Type Name</label>
                            <input type="text" class="form-control" id="custom_schema_type" placeholder="e.g. TravelAgency" value="{{ $isCustomType ? old('schema_type', $schema->schema_type) : '' }}">
                        </div>
                        @error('schema_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-12">
                        <label for="status" class="form-label fw-bold text-navy small">Publication Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" {{ old('status', $schema->status) == 'active' ? 'selected' : '' }}>Active (Inject on destination page header)</option>
                            <option value="inactive" {{ old('status', $schema->status) == 'inactive' ? 'selected' : '' }}>Inactive (Draft / Hidden)</option>
                        </select>
                    </div>

                    <!-- Schema JSON payload -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="schema_json" class="form-label fw-bold text-navy small mb-0">JSON-LD Schema Payload <span class="text-danger">*</span></label>
                            <span class="badge bg-warning text-navy font-monospace d-none" id="validation-info-badge">
                                <i class="fa-solid fa-code me-1"></i> Script Tags Detected (Will auto-strip)
                            </span>
                        </div>
                        <textarea class="form-control font-monospace @error('schema_json') is-invalid @enderror" id="schema_json" name="schema_json" rows="12" required placeholder='{&#10;  "@context": "https://schema.org",&#10;  "@type": "Organization",&#10;  "name": "Destination Fareways"&#10;}' style="font-size: 0.85rem; line-height: 1.45;">{{ old('schema_json', $schema->schema_json) }}</textarea>
                        @error('schema_json')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted small" style="font-size: 0.75rem;">Enter the raw JSON-LD structure or paste the complete Google Search Console script tags. Only validated structured payloads are saved.</div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex align-items-center gap-2">
                <button type="submit" class="btn btn-action rounded-pill px-5 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Validate & Update Schema
                </button>
                <a href="{{ route('admin.schema.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">Cancel</a>
            </div>
        </div>

        <!-- Validation Results Right Column -->
        <div class="col-lg-5">
            <div class="sticky-lg-top" style="top: 90px; z-index: 10;">
                <div class="d-flex flex-column gap-3">
                    <h6 class="fw-bold text-navy small mb-0"><i class="fa-solid fa-circle-nodes me-1 text-royal"></i>JSON-LD Validation Engine</h6>
                    
                    <div id="validation-alert" class="alert alert-info border-0 shadow-sm p-3 mb-0" role="alert">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa-solid fa-circle-info fs-5 mt-0.5"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Awaiting Code Input...</h6>
                                <div class="small">Provide a valid JSON payload in the text editor to execute real-time structural analysis.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card premium-card border-0 shadow-sm p-4 mt-2">
                        <h6 class="fw-bold text-navy small mb-3 border-bottom pb-2">JSON-LD Cheat Sheet</h6>
                        <ul class="list-unstyled small mb-0 d-flex flex-column gap-2.5">
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-circle-dot text-gold mt-1" style="font-size: 0.55rem;"></i>
                                <div><strong>LocalBusiness</strong>: Boost local search results maps visibility. Include coordinates, phone, and hours.</div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-circle-dot text-gold mt-1" style="font-size: 0.55rem;"></i>
                                <div><strong>FAQPage</strong>: Populates search engine drops in search pages. Must contain exact Question and Answer matches.</div>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-circle-dot text-gold mt-1" style="font-size: 0.55rem;"></i>
                                <div><strong>BreadcrumbList</strong>: Enables custom search page URL nesting instead of simple direct URL text lines.</div>
                            </li>
                        </ul>
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
        
        // Target Page Scope hidden synchronizer
        function syncPageIdentifier() {
            var selected = $('#page_identifier_select').val();
            if (selected === 'custom') {
                $('#custom_page_identifier_container').removeClass('d-none');
                var customVal = $('#custom_page_identifier').val().trim().toLowerCase().replace(/[^a-z0-9-_]/g, '-');
                $('#page_identifier_hidden').val(customVal);
            } else {
                $('#custom_page_identifier_container').addClass('d-none');
                $('#page_identifier_hidden').val(selected);
            }
        }

        // Schema Type hidden synchronizer
        function syncSchemaType() {
            var selected = $('#schema_type_select').val();
            if (selected === 'custom') {
                $('#custom_schema_type_container').removeClass('d-none');
                var customVal = $('#custom_schema_type').val().trim();
                $('#schema_type_hidden').val(customVal);
            } else {
                $('#custom_schema_type_container').addClass('d-none');
                $('#schema_type_hidden').val(selected);
            }
        }

        // JSON-LD Validator
        function validateJSONLD() {
            var rawText = $('#schema_json').val().trim();
            if (!rawText) {
                $('#validation-alert').removeClass().addClass('alert alert-info border-0 shadow-sm').html(
                    '<div class="d-flex align-items-start gap-2">' +
                        '<i class="fa-solid fa-circle-info fs-5 mt-0.5"></i>' +
                        '<div>' +
                            '<h6 class="fw-bold mb-1">Awaiting Code Input...</h6>' +
                            '<div class="small">Provide a valid JSON payload in the text editor to execute real-time structural analysis.</div>' +
                        '</div>' +
                    '</div>'
                );
                $('#validation-info-badge').addClass('d-none');
                return;
            }
            
            // Check if script tags are included, and strip them
            var extractedText = rawText;
            var matches = rawText.match(/<script\b[^>]*>([\s\S]*?)<\/script>/i);
            if (matches && matches[1]) {
                extractedText = matches[1].trim();
                $('#validation-info-badge').removeClass('d-none');
            } else {
                $('#validation-info-badge').addClass('d-none');
            }

            try {
                var parsed = JSON.parse(extractedText);
                // Valid JSON!
                var type = parsed['@type'] || 'Not specified';
                var context = parsed['@context'] || 'Not specified';
                
                var successHtml = '<div class="d-flex align-items-start gap-2">' +
                    '<i class="fa-solid fa-circle-check fs-5 text-success mt-0.5"></i>' +
                    '<div>' +
                        '<h6 class="fw-bold text-success mb-1">JSON Syntax Valid!</h6>' +
                        '<div class="small text-muted mb-2">Successfully parsed by the compiler. Ready for injection.</div>' +
                        '<div class="font-monospace small bg-white p-2.5 rounded border text-navy" style="font-size:0.75rem;">' +
                            '<strong>@context:</strong> ' + context + '<br>' +
                            '<strong>@type:</strong> ' + type +
                        '</div>' +
                    '</div>' +
                '</div>';
                
                $('#validation-alert').removeClass().addClass('alert alert-success border-success bg-success bg-opacity-10 shadow-sm').html(successHtml);
            } catch (e) {
                // Invalid JSON!
                var errorHtml = '<div class="d-flex align-items-start gap-2">' +
                    '<i class="fa-solid fa-triangle-exclamation fs-5 text-danger mt-0.5"></i>' +
                    '<div>' +
                        '<h6 class="fw-bold text-danger mb-1">JSON Syntax Error</h6>' +
                        '<div class="small text-danger fw-semibold mb-1" style="font-size: 0.8rem;">' + e.message + '</div>' +
                        '<div class="small text-muted" style="font-size: 0.75rem;">Please review braces matches, missing commas, double quote markers, or trailing commas.</div>' +
                    '</div>' +
                '</div>';
                
                $('#validation-alert').removeClass().addClass('alert alert-danger border-danger bg-danger bg-opacity-10 shadow-sm').html(errorHtml);
            }
        }

        // Initialize selectors
        $('#page_identifier_select').on('change', function() {
            syncPageIdentifier();
        });
        $('#custom_page_identifier').on('input keyup change', function() {
            syncPageIdentifier();
        });

        $('#schema_type_select').on('change', function() {
            syncSchemaType();
        });
        $('#custom_schema_type').on('input keyup change', function() {
            syncSchemaType();
        });

        $('#schema_json').on('input keyup change', function() {
            validateJSONLD();
        });

        // Trigger on load
        syncPageIdentifier();
        syncSchemaType();
        validateJSONLD();

        // On submit, block invalid JSON
        $('#schema-form').on('submit', function(e) {
            var rawText = $('#schema_json').val().trim();
            var extractedText = rawText;
            var matches = rawText.match(/<script\b[^>]*>([\s\S]*?)<\/script>/i);
            if (matches && matches[1]) {
                extractedText = matches[1].trim();
            }
            try {
                JSON.parse(extractedText);
            } catch (err) {
                e.preventDefault();
                alert('Please correct syntax errors in the JSON-LD payload before submitting.');
            }
        });
    });
</script>
@endsection
