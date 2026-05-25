@extends('layouts.frontend')

@section('content')

<!-- Header Banner -->
<div style="background-color: #07111F; padding-top: 110px; padding-bottom: 25px;" class="text-white border-bottom border-secondary">
    <div class="container">
        @include('partials.frontend.breadcrumb')
        <h1 class="h3 display-font text-white mb-0">Confirm Flight Details</h1>
    </div>
</div>

<section class="section-alt py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Left Side Details (8 Cols) -->
            <div class="col-lg-8">
                <!-- 1. Flight Summary Card -->
                <div class="card-flight p-4 bg-white mb-4 text-start">
                    <div class="d-flex justify-content-between align-items-center mb-3.5 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $flight['airline_logo'] }}" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover;" alt="Airline logo">
                            <div>
                                <h4 class="h6 mb-0 text-navy fw-bold" style="font-family: 'DM Sans', sans-serif;">{{ $flight['airline_name'] }}</h4>
                                <span class="text-muted small">Flight ID: #{{ $flight['id'] }} | Carrier: {{ $flight['airline_code'] }}</span>
                            </div>
                        </div>
                        <span class="badge bg-gold text-navy text-uppercase">{{ $flight['cabin_class'] }}</span>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-sm-5 text-center text-sm-start">
                            <div class="text-muted small text-uppercase tracking-wider">Departure</div>
                            <h3 class="text-navy fw-bold my-1" style="font-size: 1.5rem;">{{ $flight['departure_time'] }}</h3>
                            <div class="fw-semibold text-navy">{{ $flight['from'] }} Airport</div>
                            <div class="text-muted small">Terminal 4, Gate 12</div>
                        </div>
                        <div class="col-sm-2 my-3 my-sm-0 text-center position-relative d-flex flex-column align-items-center">
                            <span class="small text-muted" style="font-size: 0.75rem;">{{ $flight['duration'] }}</span>
                            <i class="fa-solid fa-plane text-gold fs-5 my-1"></i>
                            <span class="badge border border-secondary text-secondary bg-light" style="font-size: 0.7rem;">
                                {{ $flight['stops'] == 0 ? 'Nonstop' : $flight['stops'] . ' Stop' }}
                            </span>
                        </div>
                        <div class="col-sm-5 text-center text-sm-end">
                            <div class="text-muted small text-uppercase tracking-wider">Arrival</div>
                            <h3 class="text-navy fw-bold my-1" style="font-size: 1.5rem;">{{ $flight['arrival_time'] }}</h3>
                            <div class="fw-semibold text-navy">{{ $flight['to'] }} Airport</div>
                            <div class="text-muted small">Terminal B, Gate 2</div>
                        </div>
                    </div>
                </div>

                <!-- 2. Fare Breakdown Table -->
                <div class="card-flight p-4 bg-white mb-4 text-start">
                    <h5 class="fw-bold text-navy mb-3 pb-2 border-bottom" style="font-family: 'DM Sans', sans-serif;">Fare Breakdown</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr style="font-size: 0.95rem;">
                                    <td class="text-secondary ps-0">Base airfare (1 Adult)</td>
                                    <td class="text-end pe-0 font-monospace fw-semibold text-navy">${{ number_format($flight['base_fare'], 2) }}</td>
                                </tr>
                                <tr style="font-size: 0.95rem;">
                                    <td class="text-secondary ps-0">Taxes & governmental surcharges</td>
                                    <td class="text-end pe-0 font-monospace fw-semibold text-navy">${{ number_format($flight['taxes'], 2) }}</td>
                                </tr>
                                <tr style="font-size: 0.95rem;">
                                    <td class="text-secondary ps-0">Ticketing & security processing fees</td>
                                    <td class="text-end pe-0 font-monospace fw-semibold text-navy">${{ number_format($flight['fees'], 2) }}</td>
                                </tr>
                                <tr class="border-top" style="font-size: 1.1rem;">
                                    <td class="text-navy fw-bold ps-0 pt-3">Total Ticket Cost</td>
                                    <td class="text-end pe-0 font-monospace fw-bold text-gold pt-3" style="font-size: 1.3rem;">${{ number_format($flight['total'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Baggage Allowance & Support -->
                <div class="card-flight p-4 bg-white mb-4 text-start">
                    <h5 class="fw-bold text-navy mb-3 pb-2 border-bottom" style="font-family: 'DM Sans', sans-serif;">Baggage Allowance</h5>
                    <div class="row align-items-center">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-navy" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="fa-solid fa-suitcase-rolling text-gold"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-navy" style="font-size: 0.9rem;">Cabin Baggage</h6>
                                    <p class="text-muted small mb-0">1 Personal Item + 1 Carry-on Included</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-navy" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                    <i class="fa-solid fa-suitcase text-gold"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-navy" style="font-size: 0.9rem;">Checked Baggage</h6>
                                    <p class="text-muted small mb-0">{{ $flight['baggage_allowance'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Cancellation & Fare Rules Accordion -->
                <div class="accordion custom-accordion mb-4" id="policyAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cancellationCollapse">
                                <i class="fa-solid fa-ban me-2 text-gold"></i> Cancellation & Modification Policy
                            </button>
                        </h2>
                        <div id="cancellationCollapse" class="accordion-collapse collapse" data-bs-parent="#policyAccordion">
                            <div class="accordion-body text-start">
                                {{ $flight['cancellation_policy'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Trust badges strip -->
                <div class="row g-3 py-3 border-top border-bottom">
                    <div class="col-4 text-center">
                        <i class="fa-solid fa-shield-halved text-gold fs-4 mb-1"></i>
                        <div class="small fw-semibold text-navy">100% Encrypted Payment</div>
                    </div>
                    <div class="col-4 text-center">
                        <i class="fa-solid fa-circle-check text-gold fs-4 mb-1"></i>
                        <div class="small fw-semibold text-navy">Instant Fare Lock</div>
                    </div>
                    <div class="col-4 text-center">
                        <i class="fa-solid fa-thumbs-up text-gold fs-4 mb-1"></i>
                        <div class="small fw-semibold text-navy">No Booking Commissions</div>
                    </div>
                </div>
            </div>

            <!-- Sticky Right Checkout Enquiry Form (4 Cols) -->
            <div class="col-lg-4">
                <div class="sticky-widget">
                    <div class="card-flight p-4 bg-white text-start">
                        <h5 class="fw-bold text-navy mb-1.5" style="font-family: 'DM Sans', sans-serif;">Send Booking Enquiry</h5>
                        <p class="text-muted small mb-3.5">Submit your contact info to instantly lock this flight fare with our booking desk.</p>
                        
                        <form action="{{ route('booking.submit') }}" method="POST">
                            @csrf
                            <!-- Hidden Parameters pre-populating Search details -->
                            <input type="hidden" name="from_airport" value="{{ $flight['from'] }}">
                            <input type="hidden" name="to_airport" value="{{ $flight['to'] }}">
                            <input type="hidden" name="departure_date" value="{{ request('depart', date('Y-m-d', strtotime('+7 days'))) }}">
                            <input type="hidden" name="return_date" value="{{ request('return') }}">
                            <input type="hidden" name="adults" value="1">
                            <input type="hidden" name="cabin_class" value="{{ strtolower(str_replace(' ', '_', $flight['cabin_class'])) }}">
                            <input type="hidden" name="trip_type" value="{{ request('return') ? 'round_trip' : 'one_way' }}">
                            <input type="hidden" name="preferred_airline" value="{{ $flight['airline_name'] }}">
                            <input type="hidden" name="budget" value="${{ $flight['price'] }}">

                            <div class="mb-3">
                                <label for="enq-name" class="form-label text-navy small fw-semibold">Full Name *</label>
                                <input type="text" name="name" class="form-control" id="enq-name" placeholder="John Doe" required style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label for="enq-email" class="form-label text-navy small fw-semibold">Email Address *</label>
                                <input type="email" name="email" class="form-control" id="enq-email" placeholder="john@example.com" required style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label for="enq-phone" class="form-label text-navy small fw-semibold">Mobile Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" id="enq-phone" placeholder="+1 (555) 000-0000" required style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label for="enq-req" class="form-label text-navy small fw-semibold">Special Requests (Optional)</label>
                                <textarea name="special_requests" class="form-control" id="enq-req" rows="3" placeholder="Seating preference, meal options, etc." style="border-radius: 8px; font-size: 0.85rem;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-gold w-100 py-2.5 font-monospace text-uppercase" style="font-weight: 700;">
                                Submit Reservation <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Call CTA Banner below widget -->
                    @if(!empty($callSettings) && $callSettings->status)
                        <div class="mt-4 p-4 rounded text-center border" style="background-color: #07111F; color: #fff;">
                            <h6 class="text-white mb-2 fw-bold" style="font-family: 'DM Sans', sans-serif;">Want to book over the phone?</h6>
                            <p class="text-white-50 small mb-3">Speak directly with our ticketing desk for unpublished phone-only flight promotions.</p>
                            <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold w-100 font-monospace text-navy py-2" style="font-weight: 700; font-size: 1rem;">
                                <i class="fa-solid fa-phone"></i> {{ $callSettings->phone }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
