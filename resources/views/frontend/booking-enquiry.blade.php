@extends('layouts.frontend')

@section('content')

<!-- Header Banner -->
<div style="background-color: #07111F; padding-top: 110px; padding-bottom: 25px;" class="text-white border-bottom border-secondary">
    <div class="container">
        @include('partials.frontend.breadcrumb')
        <h1 class="h3 display-font text-white mb-0">Booking Enquiry Wizard</h1>
    </div>
</div>

<section class="section-alt py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- If Success, Show Reference Number Card directly -->
                @if(session('success') && session('ref_number'))
                    <div class="card-flight p-5 bg-white text-center shadow-lg border border-success" data-aos="fade-up">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2.5rem;">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h2 class="display-font text-navy mb-3">Enquiry Submitted Successfully!</h2>
                        <p class="lead text-secondary mb-4" style="font-family: 'DM Sans', sans-serif;">
                            Thank you for choosing Destination Fareways. Your ticket enquiry has been successfully locked into our reservation queue.
                        </p>
                        
                        <div class="bg-light p-4 rounded-3 border mb-4 max-width-400 mx-auto">
                            <span class="text-muted d-block small uppercase text-uppercase fw-semibold mb-1">Your Booking Reference</span>
                            <span class="fs-3 fw-bold text-gold font-monospace tracking-wide">{{ session('ref_number') }}</span>
                        </div>

                        <p class="text-muted mb-5">
                            <i class="fa-solid fa-clock text-gold me-1"></i> A dedicated travel ticketing concierge will call or email you within <strong>2 hours</strong> to finalize options and lock your promotional fare discounts.
                        </p>

                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('home') }}" class="btn btn-navy">Return Home</a>
                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold font-monospace d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-phone"></i> Call Now: {{ $callSettings->phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Multi-Step Form Wrapper -->
                    <div class="card-flight p-4 bg-white text-start shadow-sm" data-aos="fade-up">
                        
                        <!-- Step Progress Indicator -->
                        <div class="row text-center mb-4.5 pb-3 border-bottom g-0">
                            <div class="col-4 step-indicator active" id="step-ind-1">
                                <div class="step-num bg-navy text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold" style="width: 32px; height: 32px; font-size: 0.9rem;">1</div>
                                <span class="small fw-bold text-navy" style="font-family: 'DM Sans', sans-serif;">Trip Details</span>
                            </div>
                            <div class="col-4 step-indicator" id="step-ind-2">
                                <div class="step-num bg-light text-muted rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold" style="width: 32px; height: 32px; font-size: 0.9rem;">2</div>
                                <span class="small fw-semibold text-muted" style="font-family: 'DM Sans', sans-serif;">Passenger Info</span>
                            </div>
                            <div class="col-4 step-indicator" id="step-ind-3">
                                <div class="step-num bg-light text-muted rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold" style="width: 32px; height: 32px; font-size: 0.9rem;">3</div>
                                <span class="small fw-semibold text-muted" style="font-family: 'DM Sans', sans-serif;">Review & Confirm</span>
                            </div>
                        </div>

                        <!-- Form Block -->
                        <form action="{{ route('booking.submit') }}" method="POST" id="enquiryWizardForm">
                            @csrf
                            
                            <!-- STEP 1: Trip Details -->
                            <div class="wizard-step-block" id="wizard-step-1">
                                <h5 class="fw-bold text-navy mb-4" style="font-family: 'DM Sans', sans-serif;"><i class="fa-solid fa-plane text-gold me-2"></i> Step 1: Trip & Flight Preferences</h5>
                                
                                <div class="row g-3">
                                    <!-- Trip Type -->
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label text-navy small fw-semibold">Trip Type *</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="trip_type" id="trip-rt" value="round_trip" checked>
                                                <label class="form-check-label small" for="trip-rt">Round Trip</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="trip_type" id="trip-ow" value="one_way">
                                                <label class="form-check-label small" for="trip-ow">One Way</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="trip_type" id="trip-mc" value="multi_city">
                                                <label class="form-check-label small" for="trip-mc">Multi-City</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- From Airport -->
                                    <div class="col-md-6">
                                        <label for="wizard-from" class="form-label text-navy small fw-semibold">Origin Airport or City *</label>
                                        <input type="text" name="from_airport" class="form-control py-2.5" id="wizard-from" placeholder="e.g. New York (JFK)" required value="{{ old('from_airport') }}">
                                    </div>

                                    <!-- To Airport -->
                                    <div class="col-md-6">
                                        <label for="wizard-to" class="form-label text-navy small fw-semibold">Destination Airport or City *</label>
                                        <input type="text" name="to_airport" class="form-control py-2.5" id="wizard-to" placeholder="e.g. Los Angeles (LAX)" required value="{{ old('to_airport') }}">
                                    </div>

                                    <!-- Depart Date -->
                                    <div class="col-md-6">
                                        <label for="wizard-depart" class="form-label text-navy small fw-semibold">Departure Date *</label>
                                        <input type="text" name="departure_date" class="form-control py-2.5 flatpickr-date bg-white" id="wizard-depart" placeholder="Select departure date" required value="{{ old('departure_date', date('Y-m-d', strtotime('+7 days'))) }}">
                                    </div>

                                    <!-- Return Date -->
                                    <div class="col-md-6" id="wizard-return-wrapper">
                                        <label for="wizard-return" class="form-label text-navy small fw-semibold">Return Date</label>
                                        <input type="text" name="return_date" class="form-control py-2.5 flatpickr-date bg-white" id="wizard-return" placeholder="Select return date" value="{{ old('return_date', date('Y-m-d', strtotime('+14 days'))) }}">
                                    </div>

                                    <!-- Cabin Class -->
                                    <div class="col-md-6">
                                        <label for="wizard-cabin" class="form-label text-navy small fw-semibold">Cabin Class *</label>
                                        <select name="cabin_class" id="wizard-cabin" class="form-select py-2.5" required>
                                            <option value="economy" {{ old('cabin_class') == 'economy' ? 'selected' : '' }}>Economy</option>
                                            <option value="premium_economy" {{ old('cabin_class') == 'premium_economy' ? 'selected' : '' }}>Premium Economy</option>
                                            <option value="business" {{ old('cabin_class') == 'business' ? 'selected' : '' }}>Business</option>
                                            <option value="first" {{ old('cabin_class') == 'first' ? 'selected' : '' }}>First Class</option>
                                        </select>
                                    </div>

                                    <!-- Preferred Airline -->
                                    <div class="col-md-6">
                                        <label for="wizard-airline" class="form-label text-navy small fw-semibold">Preferred Airline (Optional)</label>
                                        <input type="text" name="preferred_airline" class="form-control py-2.5" id="wizard-airline" placeholder="e.g. Delta, Emirates" value="{{ old('preferred_airline') }}">
                                    </div>

                                    <!-- Estimated Budget -->
                                    <div class="col-md-6">
                                        <label for="wizard-budget" class="form-label text-navy small fw-semibold">Estimated Budget (Optional)</label>
                                        <input type="text" name="budget" class="form-control py-2.5" id="wizard-budget" placeholder="e.g. Under $1000" value="{{ old('budget') }}">
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="button" class="btn btn-navy next-step-btn" data-next="2">
                                        Next: Passengers <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 2: Passenger Info -->
                            <div class="wizard-step-block" id="wizard-step-2" style="display: none;">
                                <h5 class="fw-bold text-navy mb-4" style="font-family: 'DM Sans', sans-serif;"><i class="fa-solid fa-users text-gold me-2"></i> Step 2: Passenger Details & Contact Information</h5>
                                
                                <div class="row g-3">
                                    <!-- Passengers breakdown counters -->
                                    <div class="col-md-4">
                                        <label for="wizard-adults" class="form-label text-navy small fw-semibold">Adults (12+) *</label>
                                        <input type="number" name="adults" class="form-control" id="wizard-adults" min="1" max="9" required value="{{ old('adults', 1) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="wizard-children" class="form-label text-navy small fw-semibold">Children (2-11)</label>
                                        <input type="number" name="children" class="form-control" id="wizard-children" min="0" max="9" value="{{ old('children', 0) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="wizard-infants" class="form-label text-navy small fw-semibold">Infants (0-2)</label>
                                        <input type="number" name="infants" class="form-control" id="wizard-infants" min="0" max="9" value="{{ old('infants', 0) }}">
                                    </div>

                                    <!-- Contact details -->
                                    <div class="col-md-12 pt-3 border-top mt-4 mb-2">
                                        <h6 class="text-navy fw-bold" style="font-family: 'DM Sans', sans-serif;">Primary Contact Person</h6>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="wizard-name" class="form-label text-navy small fw-semibold">Full Name *</label>
                                        <input type="text" name="name" class="form-control py-2.5" id="wizard-name" placeholder="John Doe" required value="{{ old('name') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="wizard-email" class="form-label text-navy small fw-semibold">Email Address *</label>
                                        <input type="email" name="email" class="form-control py-2.5" id="wizard-email" placeholder="john@example.com" required value="{{ old('email') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="wizard-phone" class="form-label text-navy small fw-semibold">Mobile Phone Number *</label>
                                        <input type="tel" name="phone" class="form-control py-2.5" id="wizard-phone" placeholder="+1 (555) 000-0000" required value="{{ old('phone') }}">
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-navy prev-step-btn" data-prev="1">
                                        <i class="fa-solid fa-arrow-left me-1"></i> Back
                                    </button>
                                    <button type="button" class="btn btn-navy next-step-btn" data-next="3">
                                        Next: Review <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 3: Review & Confirm -->
                            <div class="wizard-step-block" id="wizard-step-3" style="display: none;">
                                <h5 class="fw-bold text-navy mb-4" style="font-family: 'DM Sans', sans-serif;"><i class="fa-solid fa-circle-check text-gold me-2"></i> Step 3: Review & Submit Reservation Request</h5>
                                
                                <div class="bg-light p-4 rounded-3 border mb-4 text-start">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <span class="text-muted small d-block">Route</span>
                                            <span class="fw-bold text-navy" id="rev-route">JFK to LAX</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted small d-block">Itinerary Dates</span>
                                            <span class="fw-bold text-navy" id="rev-dates">2026-06-01 to 2026-06-15</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted small d-block">Cabin Class</span>
                                            <span class="fw-bold text-navy text-capitalize" id="rev-class">Economy</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted small d-block">Passengers</span>
                                            <span class="fw-bold text-navy" id="rev-pass">1 Adult, 0 Children</span>
                                        </div>
                                        <div class="col-12 border-top pt-3 mt-3">
                                            <span class="text-muted small d-block">Primary Contact</span>
                                            <span class="fw-bold text-navy" id="rev-contact">John Doe (john@example.com, +15550000)</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="wizard-requests" class="form-label text-navy small fw-semibold">Special Requests (Meal choices, seat preferences, assistance details, etc.)</label>
                                    <textarea name="special_requests" class="form-control" id="wizard-requests" rows="4" placeholder="Type here optional request notes..." style="border-radius: 8px;"></textarea>
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-navy prev-step-btn" data-prev="2">
                                        <i class="fa-solid fa-arrow-left me-1"></i> Back
                                    </button>
                                    <button type="submit" class="btn btn-gold text-uppercase font-monospace py-2.5 px-4" style="font-weight: 700;">
                                        Lock Seat Deal <i class="fa-solid fa-lock ms-1"></i>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.step-indicator {
    opacity: 0.4;
    transition: opacity 0.3s;
}
.step-indicator.active {
    opacity: 1;
}
.step-indicator.active .step-num {
    background-color: var(--gold) !important;
    color: var(--navy) !important;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Basic multi-step validation and tab navigation logic
    $('.next-step-btn').on('click', function(e) {
        var currentStep = $(this).closest('.wizard-step-block');
        var nextStepNum = $(this).data('next');
        
        // Custom simple field validation before stepping forward
        var isValid = true;
        currentStep.find('input[required], select[required]').each(function() {
            if (!this.value) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            showToastMessage('Please complete all required fields highlighted in red.');
            return;
        }

        // Proceed to next step
        currentStep.hide();
        $('#wizard-step-' + nextStepNum).fadeIn(300);
        
        // Update Indicator Badges
        $('.step-indicator').removeClass('active');
        $('#step-ind-' + nextStepNum).addClass('active');
        
        // Update review texts dynamically if entering Step 3
        if (nextStepNum == 3) {
            var from = $('#wizard-from').val();
            var to = $('#wizard-to').val();
            var depart = $('#wizard-depart').val();
            var ret = $('#wizard-return').val();
            var tripType = $('input[name="trip_type"]:checked').val();
            
            $('#rev-route').text(from + ' to ' + to);
            $('#rev-dates').text(depart + (ret && tripType !== 'one_way' ? ' to ' + ret : ' (One Way)'));
            $('#rev-class').text($('#wizard-cabin').val().replace('_', ' '));
            
            var ad = $('#wizard-adults').val();
            var ch = $('#wizard-children').val();
            var inf = $('#wizard-infants').val();
            $('#rev-pass').text(ad + ' Adult' + (ad > 1 ? 's' : '') + (ch > 0 ? ', ' + ch + ' Child' : '') + (inf > 0 ? ', ' + inf + ' Infant' : ''));
            
            $('#rev-contact').text($('#wizard-name').val() + ' (' + $('#wizard-email').val() + ' | ' + $('#wizard-phone').val() + ')');
        }
    });

    $('.prev-step-btn').on('click', function() {
        var currentStep = $(this).closest('.wizard-step-block');
        var prevStepNum = $(this).data('prev');
        
        currentStep.hide();
        $('#wizard-step-' + prevStepNum).fadeIn(300);
        
        $('.step-indicator').removeClass('active');
        $('#step-ind-' + prevStepNum).addClass('active');
    });

    // Handle Trip Type change dynamically to show/hide return dates
    $('input[name="trip_type"]').on('change', function() {
        if (this.value === 'one_way') {
            $('#wizard-return-wrapper').css('opacity', '0.5');
            $('#wizard-return').prop('required', false).prop('disabled', true).val('');
        } else {
            $('#wizard-return-wrapper').css('opacity', '1');
            $('#wizard-return').prop('required', true).prop('disabled', false);
        }
    });

    function showToastMessage(msg) {
        var toastContainer = $('.toast-container');
        var toastHtml = `
            <div class="toast align-items-center text-white bg-danger border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
                        ${msg}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        var toastElement = $(toastHtml).appendTo(toastContainer);
        var bsToast = new bootstrap.Toast(toastElement[0]);
        bsToast.show();
        toastElement.on('hidden.bs.toast', function () { $(this).remove(); });
    }
});
</script>
@endsection
