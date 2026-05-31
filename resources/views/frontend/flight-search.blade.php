@extends('layouts.frontend')

@section('content')

<!-- Condensed Hero Section -->
<div class="hero-section text-white position-relative" style="min-height: 70vh; padding-top: 120px; padding-bottom: 60px;">
    <div class="hero-overlay" style="opacity: 0.12;"></div>
    <div class="container hero-content py-4">
        @include('partials.frontend.breadcrumb')
        
        <div class="row justify-content-center text-center mb-4" data-aos="fade-up">
            <div class="col-lg-8">
                <h1 class="display-font text-white mb-2" style="font-size: clamp(2rem, 4vw, 3rem);">Search Flights</h1>
                <p class="lead text-white-50 mb-0" style="font-family: 'DM Sans', sans-serif; font-size: 1.05rem;">
                    Access exclusive private promotions, unpublished rates, and flexible reservation terms.
                </p>
            </div>
        </div>

        <!-- Centered Flight Search Form -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                @include('partials.frontend.flight-search-form')
            </div>
        </div>

        <!-- Popular Flight Searches Suggestions -->
        <div class="row justify-content-center mt-4" data-aos="fade-up">
            <div class="col-lg-10 text-center">
                <span class="text-white-50 small me-2">Popular suggestions:</span>
                @php
                    $suggs = [
                        ['from' => 'JFK', 'to' => 'LAX', 'label' => 'NYC to Los Angeles'],
                        ['from' => 'ORD', 'to' => 'MIA', 'label' => 'Chicago to Miami'],
                        ['from' => 'DFW', 'to' => 'LAS', 'label' => 'Dallas to Las Vegas'],
                        ['from' => 'MIA', 'to' => 'JFK', 'label' => 'Miami to NYC'],
                    ];
                @endphp
                <div class="d-inline-flex flex-wrap gap-2 justify-content-center mt-2 mt-sm-0">
                    @foreach($suggs as $s)
                        <a href="{{ route('flights.results') }}?from={{ $s['from'] }}&to={{ $s['to'] }}" class="badge border border-secondary text-white-50 text-decoration-none hover-white py-2 px-3 rounded-pill bg-dark" style="font-size: 0.8rem; font-family: 'DM Sans', sans-serif;">
                            {{ $s['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Why Book With Us Section -->
<section class="bg-white border-bottom">
    <div class="container">
        <div class="section-title-wrapper center" data-aos="fade-up">
            <h2 class="display-font text-navy">Why Book With Destination Fareways</h2>
            <p class="text-muted">Uncompromising quality and elite flight concierge systems.</p>
        </div>

        <div class="row g-4" data-aos="fade-up">
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-tags"></i></div>
                    <h4>Best Price Guarantee</h4>
                    <p>Access contracted and phone-exclusive airline tickets up to 30% cheaper.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-user-shield"></i></div>
                    <h4>Secure & Trusted</h4>
                    <p>Encrypted payment portals protecting your financial information.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-headset"></i></div>
                    <h4>24/7 Support</h4>
                    <p>Talk to our dedicated travel concierges directly. Zero wait times.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-ban"></i></div>
                    <h4>No Hidden Fees</h4>
                    <p>Transparent flat pricing with clear fare and taxes breakdowns.</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ── Mobile Sticky CTA ── --}}
<div class="offer-mobile-cta d-lg-none">
    <div class="d-flex gap-2 align-items-center">
        <div class="flex-grow-1">
            <span class="d-block text-muted-white small fw-semibold text-uppercase" style="font-size:.65rem;letter-spacing:.5px;">Best Price Guarantee</span>
            <span class="text-white fw-bold" style="font-size:.88rem;">Book Cheap Flights</span>
        </div>
        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
           class="btn btn-gold px-3 py-2 fw-bold text-navy d-flex align-items-center gap-1 flex-shrink-0" style="font-size:.82rem;">
            <i class="fa-solid fa-phone"></i> Call Now
        </a>
        <a href="{{ route('booking.enquiry') }}"
           class="btn btn-outline-light px-3 py-2 fw-semibold d-flex align-items-center gap-1 flex-shrink-0 offer-enquire-btn" style="font-size:.82rem;">
            Book Now
        </a>
    </div>
</div>

@endsection
