@extends('layouts.frontend')

@section('content')

{{-- ─────────────────────────────────────────────
     HERO  (offers-hero-section style)
──────────────────────────────────────────────── --}}
<section class="offers-hero-section bg-navy text-white mt-5 position-relative overflow-hidden">
    <div class="offers-hero-pattern"></div>
    <div class="offers-hero-orb orb-1"></div>
    <div class="offers-hero-orb orb-2"></div>

    <div class="container py-5 text-center position-relative" style="z-index:2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <span class="live-dot"></span>
            <span>Secure Booking — 2-Hour Response</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
            Booking Enquiry Wizard
        </h1>
        <p class="lead mb-4 text-muted-white" data-aos="fade-up" data-aos-delay="120">
            Submit your travel details in under 2 minutes. Our agents will lock your fare within 2 hours.
        </p>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="160">
            @include('partials.frontend.breadcrumb')
        </div>
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="220">
            <div class="offers-stat-pill"><i class="fa-solid fa-shield-halved text-gold me-2"></i>Secure &amp; Encrypted</div>
            <div class="offers-stat-pill"><i class="fa-solid fa-headset text-gold me-2"></i>24/7 Expert Support</div>
            <div class="offers-stat-pill"><i class="fa-solid fa-tags text-gold me-2"></i>Best Price Guarantee</div>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────────
     MAIN CONTENT
──────────────────────────────────────────────── --}}
<section class="py-5 bg-softgray">
    <div class="container py-2">

        @if(session('success') && session('ref_number'))
        {{-- ── SUCCESS STATE ── --}}
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="bq-success-card text-center" data-aos="fade-up">
                    <div class="bq-success-icon mx-auto mb-4">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h2 class="display-font text-navy mb-3">Enquiry Submitted!</h2>
                    <p class="text-muted mb-4" style="font-size:1.05rem;">
                        Thank you for choosing Destination Fareways. Your reservation has been locked into our queue.
                    </p>
                    <div class="bq-ref-box mx-auto mb-4">
                        <span class="d-block text-muted small text-uppercase fw-bold mb-1" style="letter-spacing:.8px;">Your Booking Reference</span>
                        <span class="display-font text-gold" style="font-size:2rem;letter-spacing:2px;">{{ session('ref_number') }}</span>
                    </div>
                    <p class="text-muted mb-5">
                        <i class="fa-solid fa-clock text-gold me-1"></i>
                        A dedicated travel concierge will call or email you within <strong>2 hours</strong> to finalise your promotional fare.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('home') }}" class="btn btn-navy px-4 py-3">
                            <i class="fa-solid fa-house me-2"></i>Return Home
                        </a>
                        @if(!empty($callSettings) && $callSettings->status)
                            <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold px-4 py-3 fw-bold font-monospace d-flex align-items-center gap-2">
                                <i class="fa-solid fa-phone"></i> {{ $callSettings->phone }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @else
        {{-- ── WIZARD + SIDEBAR ── --}}
        <div class="row g-5 align-items-start">

            {{-- ── LEFT: Multi-step wizard ── --}}
            <div class="col-lg-7" data-aos="fade-up">
                <div class="bq-wizard-card">

                    {{-- Step Progress Bar --}}
                    <div class="bq-steps">
                        <div class="bq-step active" id="step-ind-1">
                            <div class="bq-step-circle">1</div>
                            <span class="bq-step-label">Trip Details</span>
                        </div>
                        <div class="bq-step-line"></div>
                        <div class="bq-step" id="step-ind-2">
                            <div class="bq-step-circle">2</div>
                            <span class="bq-step-label">Passenger Info</span>
                        </div>
                        <div class="bq-step-line"></div>
                        <div class="bq-step" id="step-ind-3">
                            <div class="bq-step-circle">3</div>
                            <span class="bq-step-label">Review &amp; Confirm</span>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('booking.submit') }}" method="POST" id="enquiryWizardForm" class="bq-form-body">
                        @csrf

                        {{-- ── STEP 1 ── --}}
                        <div class="wizard-step-block" id="wizard-step-1">
                            <div class="bq-step-heading">
                                <div class="bq-step-heading-icon"><i class="fa-solid fa-plane"></i></div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-navy">Step 1: Trip &amp; Flight Preferences</h5>
                                    <p class="mb-0 text-muted small">Where and when are you flying?</p>
                                </div>
                            </div>

                            {{-- Trip Type Pills --}}
                            <div class="mb-4">
                                <label class="bq-label mb-2">Trip Type <span class="text-danger">*</span></label>
                                <div class="bq-trip-pills">
                                    <input class="bq-trip-input" type="radio" name="trip_type" id="trip-rt" value="round_trip" checked>
                                    <label class="bq-trip-pill" for="trip-rt"><i class="fa-solid fa-rotate me-1"></i>Round Trip</label>

                                    <input class="bq-trip-input" type="radio" name="trip_type" id="trip-ow" value="one_way">
                                    <label class="bq-trip-pill" for="trip-ow"><i class="fa-solid fa-arrow-right me-1"></i>One Way</label>

                                    <input class="bq-trip-input" type="radio" name="trip_type" id="trip-mc" value="multi_city">
                                    <label class="bq-trip-pill" for="trip-mc"><i class="fa-solid fa-city me-1"></i>Multi-City</label>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="wizard-from" class="bq-label">Origin Airport or City <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-plane-departure"></i></span>
                                        <input type="text" name="from_airport" class="bq-input" id="wizard-from" placeholder="e.g. New York (JFK)" required value="{{ old('from_airport') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="wizard-to" class="bq-label">Destination Airport or City <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-plane-arrival"></i></span>
                                        <input type="text" name="to_airport" class="bq-input" id="wizard-to" placeholder="e.g. Los Angeles (LAX)" required value="{{ old('to_airport') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="wizard-depart" class="bq-label">Departure Date <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-calendar-days"></i></span>
                                        <input type="text" name="departure_date" class="bq-input flatpickr-date bg-white" id="wizard-depart" placeholder="Select departure date" required value="{{ old('departure_date', date('Y-m-d', strtotime('+7 days'))) }}">
                                    </div>
                                </div>
                                <div class="col-md-6" id="wizard-return-wrapper">
                                    <label for="wizard-return" class="bq-label">Return Date</label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-calendar-check"></i></span>
                                        <input type="text" name="return_date" class="bq-input flatpickr-date bg-white" id="wizard-return" placeholder="Select return date" value="{{ old('return_date', date('Y-m-d', strtotime('+14 days'))) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="wizard-cabin" class="bq-label">Cabin Class <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-couch"></i></span>
                                        <select name="cabin_class" id="wizard-cabin" class="bq-input" required>
                                            <option value="economy" {{ old('cabin_class') == 'economy' ? 'selected' : '' }}>Economy</option>
                                            <option value="premium_economy" {{ old('cabin_class') == 'premium_economy' ? 'selected' : '' }}>Premium Economy</option>
                                            <option value="business" {{ old('cabin_class') == 'business' ? 'selected' : '' }}>Business</option>
                                            <option value="first" {{ old('cabin_class') == 'first' ? 'selected' : '' }}>First Class</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="wizard-airline" class="bq-label">Preferred Airline <span class="text-muted fw-normal">(Optional)</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-jet-fighter-up"></i></span>
                                        <input type="text" name="preferred_airline" class="bq-input" id="wizard-airline" placeholder="e.g. Delta, Emirates" value="{{ old('preferred_airline') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="wizard-budget" class="bq-label">Estimated Budget <span class="text-muted fw-normal">(Optional)</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-dollar-sign"></i></span>
                                        <input type="text" name="budget" class="bq-input" id="wizard-budget" placeholder="e.g. Under $1000" value="{{ old('budget') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="bq-step-footer justify-content-end">
                                <button type="button" class="btn btn-navy px-4 py-2 d-flex align-items-center gap-2 next-step-btn" data-next="2">
                                    Next: Passengers <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ── STEP 2 ── --}}
                        <div class="wizard-step-block" id="wizard-step-2" style="display:none;">
                            <div class="bq-step-heading">
                                <div class="bq-step-heading-icon"><i class="fa-solid fa-users"></i></div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-navy">Step 2: Passenger Details &amp; Contact</h5>
                                    <p class="mb-0 text-muted small">Who is travelling and how can we reach you?</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-4">
                                    <label for="wizard-adults" class="bq-label">Adults (12+) <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-person"></i></span>
                                        <input type="number" name="adults" class="bq-input" id="wizard-adults" min="1" max="9" required value="{{ old('adults', 1) }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="wizard-children" class="bq-label">Children (2–11)</label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-child"></i></span>
                                        <input type="number" name="children" class="bq-input" id="wizard-children" min="0" max="9" value="{{ old('children', 0) }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="wizard-infants" class="bq-label">Infants (0–2)</label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-baby"></i></span>
                                        <input type="number" name="infants" class="bq-input" id="wizard-infants" min="0" max="9" value="{{ old('infants', 0) }}">
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="bq-section-divider">
                                        <span><i class="fa-solid fa-user me-2"></i>Primary Contact Person</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="wizard-name" class="bq-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" name="name" class="bq-input" id="wizard-name" placeholder="John Doe" required value="{{ old('name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="wizard-email" class="bq-label">Email Address <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-envelope"></i></span>
                                        <input type="email" name="email" class="bq-input" id="wizard-email" placeholder="john@example.com" required value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="wizard-phone" class="bq-label">Mobile Phone <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-phone"></i></span>
                                        <input type="tel" name="phone" class="bq-input" id="wizard-phone" placeholder="+1 (555) 000-0000" required value="{{ old('phone') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="bq-step-footer">
                                <button type="button" class="btn btn-outline-navy px-4 py-2 d-flex align-items-center gap-2 prev-step-btn" data-prev="1">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </button>
                                <button type="button" class="btn btn-navy px-4 py-2 d-flex align-items-center gap-2 next-step-btn" data-next="3">
                                    Next: Review <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ── STEP 3 ── --}}
                        <div class="wizard-step-block" id="wizard-step-3" style="display:none;">
                            <div class="bq-step-heading">
                                <div class="bq-step-heading-icon"><i class="fa-solid fa-circle-check"></i></div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-navy">Step 3: Review &amp; Submit</h5>
                                    <p class="mb-0 text-muted small">Confirm your details before we lock your fare.</p>
                                </div>
                            </div>

                            {{-- Review summary card --}}
                            <div class="bq-review-card mb-4">
                                <div class="bq-review-header">
                                    <i class="fa-solid fa-ticket text-gold me-2"></i>
                                    <span>Booking Summary</span>
                                </div>
                                <div class="bq-review-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <span class="bq-review-label">Route</span>
                                            <span class="bq-review-value" id="rev-route">— to —</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="bq-review-label">Dates</span>
                                            <span class="bq-review-value" id="rev-dates">—</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="bq-review-label">Cabin Class</span>
                                            <span class="bq-review-value text-capitalize" id="rev-class">Economy</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="bq-review-label">Passengers</span>
                                            <span class="bq-review-value" id="rev-pass">1 Adult</span>
                                        </div>
                                        <div class="col-12 border-top pt-3 mt-1">
                                            <span class="bq-review-label">Primary Contact</span>
                                            <span class="bq-review-value" id="rev-contact">—</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="wizard-requests" class="bq-label">Special Requests <span class="text-muted fw-normal">(Meal preferences, seat choices, assistance, etc.)</span></label>
                                <textarea name="special_requests" class="bq-input bq-textarea" id="wizard-requests" rows="4" placeholder="Type any optional notes here..."></textarea>
                            </div>

                            <div class="bq-step-footer">
                                <button type="button" class="btn btn-outline-navy px-4 py-2 d-flex align-items-center gap-2 prev-step-btn" data-prev="2">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </button>
                                <button type="submit" class="btn btn-gold px-5 py-3 fw-bold d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-lock"></i> Lock Seat Deal
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ── RIGHT: Trust Sidebar ── --}}
            <div class="col-lg-5 d-none d-lg-block" data-aos="fade-up" data-aos-delay="100">
                <div class="bq-sidebar">

                    {{-- Call card --}}
                    <div class="bq-call-card mb-4">
                        <div class="bq-call-header">
                            <span class="offer-sidebar-badge mb-2 d-inline-block">Prefer to Call?</span>
                            <h4 class="text-white fw-bold mb-1 mt-2" style="font-family:'DM Sans',sans-serif;">Speak to a Travel Expert</h4>
                            <p class="mb-0 small" style="color:rgba(255,255,255,.6);">Available 24/7 — No hold music, no robots.</p>
                        </div>
                        <div class="bq-call-body">
                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold w-100 py-3 fw-bold font-monospace d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <i class="fa-solid fa-phone"></i> {{ $callSettings->phone }}
                                </a>
                            @else
                                <a href="tel:+18005550199" class="btn btn-gold w-100 py-3 fw-bold font-monospace d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <i class="fa-solid fa-phone"></i> +1 (800) 555-0199
                                </a>
                            @endif
                            <p class="text-center text-muted small mb-0">
                                <i class="fa-solid fa-clock text-gold me-1"></i>Response within 2 hours
                            </p>
                        </div>
                    </div>

                    {{-- Why book with us --}}
                    <div class="bq-why-card mb-4">
                        <h6 class="fw-bold text-navy mb-3 text-uppercase" style="font-size:.75rem;letter-spacing:.8px;">Why Book with Us</h6>
                        <ul class="bq-why-list">
                            <li>
                                <div class="bq-why-icon"><i class="fa-solid fa-tags"></i></div>
                                <div>
                                    <span class="fw-bold text-navy d-block" style="font-size:.9rem;">Best Price Guarantee</span>
                                    <span class="text-muted" style="font-size:.8rem;">Phone-exclusive fares up to 30% cheaper than online.</span>
                                </div>
                            </li>
                            <li>
                                <div class="bq-why-icon"><i class="fa-solid fa-headset"></i></div>
                                <div>
                                    <span class="fw-bold text-navy d-block" style="font-size:.9rem;">24/7 Concierge Support</span>
                                    <span class="text-muted" style="font-size:.8rem;">Real agents, not bots. Always a human on the line.</span>
                                </div>
                            </li>
                            <li>
                                <div class="bq-why-icon"><i class="fa-solid fa-shield-halved"></i></div>
                                <div>
                                    <span class="fw-bold text-navy d-block" style="font-size:.9rem;">Secure &amp; Encrypted</span>
                                    <span class="text-muted" style="font-size:.8rem;">Your data is fully protected with SSL encryption.</span>
                                </div>
                            </li>
                            <li>
                                <div class="bq-why-icon"><i class="fa-solid fa-rotate-left"></i></div>
                                <div>
                                    <span class="fw-bold text-navy d-block" style="font-size:.9rem;">Flexible Cancellations</span>
                                    <span class="text-muted" style="font-size:.8rem;">Change or cancel with our dedicated modifications team.</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    {{-- Trust badges --}}
                    <div class="offer-sidebar-trust" style="border-radius:14px;border:1px solid #E2E8F0;">
                        <div class="offer-sidebar-trust-item"><i class="fa-solid fa-shield-halved text-gold"></i><span>Secure</span></div>
                        <div class="offer-sidebar-trust-item"><i class="fa-solid fa-lock text-gold"></i><span>Price Match</span></div>
                        <div class="offer-sidebar-trust-item"><i class="fa-solid fa-headset text-gold"></i><span>24/7</span></div>
                    </div>

                </div>
            </div>

        </div>
        @endif
    </div>
</section>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.next-step-btn').on('click', function() {
        var currentStep = $(this).closest('.wizard-step-block');
        var nextStepNum = $(this).data('next');
        var isValid = true;
        currentStep.find('input[required], select[required]').each(function() {
            if (!this.value) { $(this).closest('.bq-input-wrap, .mb-3, .col-md-6, .col-md-12, .col-4').addClass('bq-invalid'); isValid = false; }
            else { $(this).closest('.bq-input-wrap, .mb-3, .col-md-6, .col-md-12, .col-4').removeClass('bq-invalid'); }
        });
        if (!isValid) { showToastMessage('Please complete all required fields.'); return; }
        currentStep.hide();
        $('#wizard-step-' + nextStepNum).fadeIn(300);
        $('.bq-step').removeClass('active completed');
        for (var i = 1; i < nextStepNum; i++) { $('#step-ind-' + i).addClass('completed'); }
        $('#step-ind-' + nextStepNum).addClass('active');
        if (nextStepNum == 3) {
            var from = $('#wizard-from').val(), to = $('#wizard-to').val();
            var depart = $('#wizard-depart').val(), ret = $('#wizard-return').val();
            var tripType = $('input[name="trip_type"]:checked').val();
            $('#rev-route').text(from + ' → ' + to);
            $('#rev-dates').text(depart + (ret && tripType !== 'one_way' ? ' → ' + ret : ' (One Way)'));
            $('#rev-class').text($('#wizard-cabin').val().replace('_', ' '));
            var ad = $('#wizard-adults').val(), ch = $('#wizard-children').val(), inf = $('#wizard-infants').val();
            $('#rev-pass').text(ad + ' Adult' + (ad > 1 ? 's' : '') + (ch > 0 ? ', ' + ch + ' Child' : '') + (inf > 0 ? ', ' + inf + ' Infant' : ''));
            $('#rev-contact').text($('#wizard-name').val() + ' — ' + $('#wizard-email').val() + ' | ' + $('#wizard-phone').val());
        }
    });

    $('.prev-step-btn').on('click', function() {
        var currentStep = $(this).closest('.wizard-step-block');
        var prevStepNum = $(this).data('prev');
        currentStep.hide();
        $('#wizard-step-' + prevStepNum).fadeIn(300);
        $('.bq-step').removeClass('active completed');
        for (var i = 1; i < prevStepNum; i++) { $('#step-ind-' + i).addClass('completed'); }
        $('#step-ind-' + prevStepNum).addClass('active');
    });

    $('input[name="trip_type"]').on('change', function() {
        if (this.value === 'one_way') {
            $('#wizard-return-wrapper').css('opacity', '0.45');
            $('#wizard-return').prop('required', false).prop('disabled', true).val('');
        } else {
            $('#wizard-return-wrapper').css('opacity', '1');
            $('#wizard-return').prop('required', true).prop('disabled', false);
        }
    });

    function showToastMessage(msg) {
        var toastContainer = $('.toast-container');
        var toastHtml = `<div class="toast align-items-center text-white bg-danger border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex"><div class="toast-body d-flex align-items-center"><i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>${msg}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`;
        var toastElement = $(toastHtml).appendTo(toastContainer);
        new bootstrap.Toast(toastElement[0]).show();
        toastElement.on('hidden.bs.toast', function () { $(this).remove(); });
    }
});
</script>

<style>
/* ── Wizard Card ─────────────────────────────── */
.bq-wizard-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(7,17,31,.07);
    box-shadow: 0 8px 32px rgba(7,17,31,.08);
    overflow: hidden;
}

.bq-form-body { padding: 28px; }

/* ── Step Progress ───────────────────────────── */
.bq-steps {
    display: flex;
    align-items: center;
    padding: 24px 28px 20px;
    border-bottom: 1px solid #E2E8F0;
    background: linear-gradient(135deg, #fafbfc, #f8fafc);
}
.bq-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
    opacity: .4;
    transition: opacity .3s ease;
}
.bq-step.active  { opacity: 1; }
.bq-step.completed { opacity: .7; }

.bq-step-circle {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: #E2E8F0;
    color: #64748B;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .9rem;
    transition: all .3s ease;
    border: 2px solid transparent;
}
.bq-step.active .bq-step-circle {
    background: #F59E0B;
    color: #07111F;
    border-color: rgba(245,158,11,.3);
    box-shadow: 0 4px 16px rgba(245,158,11,.35);
}
.bq-step.completed .bq-step-circle {
    background: #07111F;
    color: #fff;
}
.bq-step.completed .bq-step-circle::before {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: .8rem;
}
.bq-step.completed .bq-step-circle { font-size: 0; }

.bq-step-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748B;
    white-space: nowrap;
}
.bq-step.active  .bq-step-label { color: #07111F; }
.bq-step.completed .bq-step-label { color: #07111F; }

.bq-step-line {
    flex: 1;
    height: 2px;
    background: #E2E8F0;
    margin: 0 8px;
    margin-bottom: 24px;
}

/* ── Step Heading ───────────────────────────── */
.bq-step-heading {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 1px solid #E2E8F0;
}
.bq-step-heading-icon {
    width: 44px; height: 44px; flex-shrink: 0;
    border-radius: 12px;
    background: linear-gradient(135deg, #07111F, #0d1f38);
    color: #F59E0B;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(7,17,31,.2);
}

/* ── Trip Pills ─────────────────────────────── */
.bq-trip-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.bq-trip-input { display: none; }
.bq-trip-pill {
    padding: 8px 18px;
    border-radius: 100px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFC;
    font-size: .82rem;
    font-weight: 700;
    color: #64748B;
    cursor: pointer;
    transition: all .2s ease;
    user-select: none;
}
.bq-trip-pill:hover { border-color: rgba(7,17,31,.2); color: #07111F; }
.bq-trip-input:checked + .bq-trip-pill {
    background: #07111F;
    border-color: #07111F;
    color: #fff;
    box-shadow: 0 4px 14px rgba(7,17,31,.2);
}

/* ── Inputs ─────────────────────────────────── */
.bq-label {
    display: block;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748B;
    margin-bottom: 6px;
}
.bq-input-wrap {
    display: flex;
    align-items: center;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    background: #F8FAFC;
    overflow: hidden;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.bq-input-wrap:focus-within {
    border-color: #F59E0B;
    box-shadow: 0 0 0 3px rgba(245,158,11,.1);
    background: #fffdf5;
}
.bq-input-icon {
    padding: 0 12px;
    color: #94a3b8;
    font-size: .9rem;
    flex-shrink: 0;
}
.bq-input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    padding: 11px 12px 11px 0;
    font-family: 'DM Sans', sans-serif;
    font-size: .9rem;
    color: #07111F;
}
.bq-input::placeholder { color: #94a3b8; }
.bq-textarea {
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 10px !important;
    padding: 11px 14px !important;
    font-family: 'DM Sans', sans-serif;
    font-size: .9rem;
    width: 100%;
    outline: none;
    resize: vertical;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.bq-textarea:focus {
    border-color: #F59E0B !important;
    box-shadow: 0 0 0 3px rgba(245,158,11,.1) !important;
}

/* Section divider */
.bq-section-divider {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .8rem;
    font-weight: 700;
    color: #07111F;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.bq-section-divider::before, .bq-section-divider::after {
    content: ''; flex: 1; height: 1px; background: #E2E8F0;
}

/* Step footer */
.bq-step-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 20px;
    margin-top: 20px;
    border-top: 1px solid #E2E8F0;
}

/* ── Review Card ────────────────────────────── */
.bq-review-card {
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    overflow: hidden;
}
.bq-review-header {
    background: linear-gradient(135deg, #07111F, #0d1f38);
    color: #F59E0B;
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
}
.bq-review-body { padding: 20px; background: #fff; }
.bq-review-label {
    display: block;
    font-size: .68rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748B;
    font-weight: 700;
    margin-bottom: 3px;
}
.bq-review-value {
    font-weight: 700;
    color: #07111F;
    font-size: .9rem;
    display: block;
}

/* ── Sidebar ─────────────────────────────────── */
.bq-sidebar { position: sticky; top: 100px; }

.bq-call-card {
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #E2E8F0;
    box-shadow: 0 8px 28px rgba(7,17,31,.1);
}
.bq-call-header {
    background: linear-gradient(135deg, #07111F, #0d1f38);
    padding: 22px 24px 20px;
    position: relative;
    overflow: hidden;
}
.bq-call-header::before {
    content: ''; position: absolute;
    top: -40px; right: -40px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(245,158,11,.06);
    pointer-events: none;
}
.bq-call-body { padding: 20px; background: #fff; }

.bq-why-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
    padding: 20px;
    box-shadow: 0 4px 16px rgba(7,17,31,.05);
}
.bq-why-list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.bq-why-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.bq-why-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 10px;
    background: linear-gradient(135deg, #07111F, #0d1f38);
    color: #F59E0B;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
}

/* ── Success ─────────────────────────────────── */
.bq-success-card {
    background: #fff;
    border-radius: 20px;
    padding: 48px 40px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 8px 32px rgba(7,17,31,.08);
}
.bq-success-icon {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem;
    box-shadow: 0 8px 24px rgba(34,197,94,.35);
}
.bq-ref-box {
    background: #F8FAFC;
    border: 2px dashed rgba(245,158,11,.4);
    border-radius: 14px;
    padding: 20px 32px;
    max-width: 340px;
}

/* ── Mobile ──────────────────────────────────── */
@media (max-width: 767.98px) {
    .bq-form-body { padding: 18px; }
    .bq-steps { padding: 16px 18px 14px; overflow-x: auto; }
    .bq-step-label { font-size: .62rem; }
    .bq-step-circle { width: 30px; height: 30px; font-size: .8rem; }
    .bq-step-heading { gap: 10px; }
    .bq-step-heading-icon { width: 38px; height: 38px; font-size: .95rem; }
    .bq-success-card { padding: 32px 20px; }
}
@media (max-width: 480px) {
    .bq-trip-pills { gap: 6px; }
    .bq-trip-pill { padding: 7px 14px; font-size: .78rem; }
    .bq-step-footer { flex-direction: column-reverse; gap: 10px; }
    .bq-step-footer .btn { width: 100%; justify-content: center; }
}
</style>
@endsection
