@extends('layouts.frontend')

@section('content')
@php
    $offerImageUrl = function ($path, $fallback) {
        if (empty($path)) {
            return $fallback;
        }
        return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    };
    $heroImg = $offerImageUrl($offer->banner_image ?? $offer->image, 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1600&q=80&auto=format');
@endphp

<!-- Immersive Hero -->
<section class="offer-detail-hero mt-5 position-relative overflow-hidden" style="min-height: 380px;">
    <div class="offer-detail-hero-bg" style="background-image: url('{{ $heroImg }}');"></div>
    <div class="offer-detail-hero-overlay"></div>

    <div class="container position-relative py-5" style="z-index: 2; padding-top: 80px !important;">
        <div class="row justify-content-center text-center pt-3">
            <div class="col-lg-9">
                <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
                    <i class="fa-solid fa-gem text-gold"></i>
                    <span>Exclusive Deal</span>
                </div>
                <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
                    {{ $offer->title }}
                </h1>
                @if(!empty($offer->subtitle))
                    <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
                        {{ $offer->subtitle }}
                    </p>
                @endif
                <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="140">
                    @include('partials.frontend.breadcrumb')
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Body -->
<section class="py-5 bg-softgray">
    <div class="container py-2">
        <div class="row g-5">

            <!-- Left: Details -->
            <div class="col-lg-8">

                <!-- Route Visualization Card -->
                <div class="offer-route-premium-card mb-5" data-aos="fade-up">
                    <div class="row align-items-center g-3">
                        <div class="col text-center text-md-start">
                            <span class="d-block text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing: 0.7px;">Departing From</span>
                            <span class="offer-city-name">{{ $offer->from_city ?? 'Any USA Airport' }}</span>
                        </div>
                        <div class="col-auto text-center">
                            <div class="offer-route-visual">
                                <div class="route-dot"></div>
                                <div class="route-line">
                                    <i class="fa-solid fa-plane text-gold route-plane-icon"></i>
                                </div>
                                <div class="route-dot"></div>
                            </div>
                            @if(!empty($offer->airline))
                                <div class="text-muted small mt-2" style="font-size: 0.72rem; letter-spacing: 0.4px; text-transform: uppercase;">
                                    {{ $offer->airline }}
                                </div>
                            @endif
                        </div>
                        <div class="col text-center text-md-end">
                            <span class="d-block text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing: 0.7px;">Arriving At</span>
                            <span class="offer-city-name">{{ $offer->to_city ?? 'Featured Destination' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Main Offer Image -->
                <div class="offer-detail-image-wrap mb-5" data-aos="fade-up">
                    <img src="{{ $heroImg }}" alt="{{ $offer->title }}" class="offer-detail-image-full">
                    @if(!empty($offer->discount_label))
                        <span class="offer-detail-discount-badge">{{ $offer->discount_label }}</span>
                    @endif
                </div>

                <!-- Rich Description -->
                <div class="prose-content mb-5" data-aos="fade-up">
                    {!! $offer->description !!}
                </div>

                <!-- Promo Code Widget -->
                @if(!empty($offer->promo_code))
                    <div class="offer-promo-card mb-5" data-aos="fade-up">
                        <div class="offer-promo-label">Apply Promo Code at Checkout</div>
                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-3">
                            <div class="offer-promo-code-box">
                                <i class="fa-solid fa-tag text-gold me-2"></i>
                                <span id="promo-code" class="fw-bold font-monospace fs-4 text-navy">{{ $offer->promo_code }}</span>
                            </div>
                            <button class="btn btn-gold px-4 py-2 d-flex align-items-center gap-2 fw-semibold" onclick="copyPromoCode()">
                                <i class="fa-regular fa-copy"></i>
                                <span id="copy-btn-text">Copy Code</span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Deal Meta Specs -->
                <div class="offer-meta-specs row g-3 mb-5" data-aos="fade-up">
                    <div class="col-6 col-md-3">
                        <div class="offer-meta-tile">
                            <i class="fa-solid fa-plane-up text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Carrier Airline</span>
                            <span class="fw-semibold text-navy small">{{ $offer->airline ?? 'Contracted Airlines' }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="offer-meta-tile">
                            <i class="fa-solid fa-suitcase text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Baggage</span>
                            <span class="fw-semibold text-navy small">Included Options</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="offer-meta-tile">
                            <i class="fa-solid fa-calendar-days text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Booking Starts</span>
                            <span class="fw-semibold text-navy small">
                                {{ $offer->valid_from ? $offer->valid_from->format('M d, Y') : 'Immediate' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="offer-meta-tile {{ !empty($offer->valid_until) ? 'offer-meta-tile-urgent' : '' }}">
                            <i class="fa-solid fa-hourglass-end text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Valid Until</span>
                            <span class="fw-semibold small {{ !empty($offer->valid_until) ? 'text-danger' : 'text-navy' }}">
                                {{ $offer->valid_until ? $offer->valid_until->format('M d, Y') : 'Limited Period' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Trust Row -->
                <div class="offer-trust-row d-flex flex-wrap gap-3 mb-3" data-aos="fade-up">
                    <div class="offer-trust-pill">
                        <i class="fa-solid fa-shield-halved text-gold"></i>
                        <span>Secure Booking</span>
                    </div>
                    <div class="offer-trust-pill">
                        <i class="fa-solid fa-lock text-gold"></i>
                        <span>Price Guarantee</span>
                    </div>
                    <div class="offer-trust-pill">
                        <i class="fa-solid fa-headset text-gold"></i>
                        <span>24/7 Support</span>
                    </div>
                    <div class="offer-trust-pill">
                        <i class="fa-solid fa-rotate-left text-gold"></i>
                        <span>Flexible Changes</span>
                    </div>
                </div>
            </div>

            <!-- Right Sticky Sidebar -->
            <div class="col-lg-4">
                <div class="offer-sidebar-sticky" style="top: 100px; z-index: 10;">

                    <!-- Price Card -->
                    <div class="offer-sidebar-card mb-4" data-aos="fade-up">
                        <!-- Card Header Gradient -->
                        <div class="offer-sidebar-header">
                            <span class="offer-sidebar-badge">Guaranteed Lowest Rate</span>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                @if(!empty($offer->original_price))
                                    <span class="text-decoration-line-through text-muted-white small font-monospace">
                                        ${{ number_format($offer->original_price, 0) }}
                                    </span>
                                @endif
                                <span class="offer-sidebar-price">${{ number_format($offer->offer_price, 0) }}</span>
                                <span class="small text-muted-white">/ one-way</span>
                            </div>
                            @if(!empty($offer->original_price) && $offer->original_price > $offer->offer_price)
                                @php $save = $offer->original_price - $offer->offer_price; @endphp
                                <div class="offer-sidebar-savings mt-1">
                                    <i class="fa-solid fa-circle-check me-1"></i>
                                    You save ${{ number_format($save, 0) }} on this deal
                                </div>
                            @endif
                            <p class="small mt-2 mb-0" style="color: rgba(255,255,255,0.6);">
                                Taxes &amp; security fees included. Call for private bulk fares.
                            </p>
                        </div>

                        <!-- Booking Form -->
                        <div class="offer-sidebar-body">
                            <form id="booking-form" action="{{ route('booking.submit') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="from_airport" value="{{ $offer->from_city ?? 'Any USA Airport' }}">
                                <input type="hidden" name="to_airport" value="{{ $offer->to_city ?? '' }}">
                                <input type="hidden" name="cabin_class" value="{{ (Str::contains(strtolower($offer->title), 'business') || Str::contains(strtolower($offer->short_desc ?? ''), 'business')) ? 'business' : 'economy' }}">
                                <input type="hidden" name="trip_type" value="one_way">
                                <input type="hidden" name="adults" value="1">

                                <h4 class="h6 fw-bold text-navy mb-3 text-uppercase" style="letter-spacing: 0.6px;">Quick Booking Enquiry</h4>

                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Full Name</label>
                                    <input type="text" name="name" class="form-control offer-form-input" placeholder="John Doe" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Email Address</label>
                                    <input type="email" name="email" class="form-control offer-form-input" placeholder="john@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control offer-form-input" placeholder="+1 (555) 000-0000" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small text-muted mb-1">Preferred Depart Date</label>
                                    <input type="date" name="departure_date" class="form-control offer-form-input" required min="{{ date('Y-m-d') }}">
                                </div>

                                <button type="submit" class="btn btn-gold w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <i class="fa-solid fa-paper-plane"></i> Submit Enquiry
                                </button>
                            </form>

                            <div class="offer-sidebar-divider">
                                <span>or call us directly</span>
                            </div>

                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}"
                                   class="btn btn-outline-navy w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2 font-monospace mt-3">
                                    <i class="fa-solid fa-phone text-gold"></i> {{ $callSettings->phone }}
                                </a>
                            @else
                                <a href="tel:+18005550199"
                                   class="btn btn-outline-navy w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2 font-monospace mt-3">
                                    <i class="fa-solid fa-phone text-gold"></i> +1 (800) 555-0199
                                </a>
                            @endif
                        </div>

                        <!-- Trust Micro-badges -->
                        <div class="offer-sidebar-trust">
                            <div class="offer-sidebar-trust-item">
                                <i class="fa-solid fa-shield-halved text-gold"></i>
                                <span>Secure</span>
                            </div>
                            <div class="offer-sidebar-trust-item">
                                <i class="fa-solid fa-lock text-gold"></i>
                                <span>Price Match</span>
                            </div>
                            <div class="offer-sidebar-trust-item">
                                <i class="fa-solid fa-headset text-gold"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </div>

                    <!-- Valid Until Alert -->
                    @if(!empty($offer->valid_until))
                        <div class="offer-expiry-alert" data-aos="fade-up">
                            <i class="fa-regular fa-clock text-gold fs-5 me-2 flex-shrink-0"></i>
                            <div>
                                <div class="fw-bold text-navy small">Offer Expires</div>
                                <div class="text-danger fw-semibold">{{ $offer->valid_until->format('F d, Y') }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Offers -->
        @if($relatedOffers->isNotEmpty())
            <div class="mt-5 pt-5 border-top border-light">
                <div class="d-flex align-items-end justify-content-between mb-4" data-aos="fade-up">
                    <h3 class="h3 fw-bold text-navy mb-0">Related Flight Offers</h3>
                </div>

                <div class="row g-4">
                    @foreach($relatedOffers as $related)
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <a href="{{ route('offers.show', $related->slug) }}" class="offer-premium-card d-flex flex-column h-100 text-decoration-none">
                                <div class="offer-card-img-wrap">
                                    <img src="{{ $offerImageUrl($related->image, 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80&auto=format') }}"
                                         alt="{{ $related->title }}" class="offer-card-img" loading="lazy">
                                    <div class="offer-card-img-gradient"></div>
                                    @if(!empty($related->discount_label))
                                        <div class="offer-card-top-badges">
                                            <span class="offer-badge-save">{{ $related->discount_label }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($related->from_city) && !empty($related->to_city))
                                        <div class="offer-card-route-strip">
                                            <span class="fw-bold text-uppercase">{{ $related->from_city }}</span>
                                            <span class="offer-route-icon"><i class="fa-solid fa-plane"></i></span>
                                            <span class="fw-bold text-uppercase">{{ $related->to_city }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="offer-card-body-premium flex-grow-1 d-flex flex-column">
                                    <div class="offer-airline-tag mb-2">
                                        <i class="fa-solid fa-plane-up text-gold"></i>
                                        <span>{{ $related->airline ?? 'Featured Carrier' }}</span>
                                    </div>
                                    <h4 class="offer-card-title-premium mb-1">{{ Str::limit($related->title, 50) }}</h4>
                                    <p class="offer-card-desc-premium flex-grow-1 mb-3">{{ Str::limit($related->short_desc, 70) }}</p>
                                    <div class="offer-price-row-premium">
                                        <div class="d-flex align-items-baseline gap-1">
                                            <span class="offer-price-premium">${{ number_format($related->offer_price, 0) }}</span>
                                            <span class="offer-price-unit">/ one-way</span>
                                        </div>
                                    </div>
                                    <div class="offer-card-cta-row mt-3">
                                        <span class="offer-cta-label">View Deal</span>
                                        <span class="offer-cta-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Mobile Sticky CTA -->
<div class="offer-mobile-cta d-lg-none">
    <div class="d-flex gap-2 align-items-center">
        <div class="flex-grow-1">
            <span class="d-block text-muted-white small fw-semibold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px;">From</span>
            <span class="price fw-bold fs-5 text-gold font-monospace">${{ number_format($offer->offer_price, 0) }}</span>
            <span class="text-muted-white" style="font-size: 0.75rem;"> / one-way</span>
        </div>
        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
           class="btn btn-gold px-4 py-2 fw-bold text-navy d-flex align-items-center gap-2 flex-shrink-0">
            <i class="fa-solid fa-phone"></i> Call Now
        </a>
        <a href="#booking-form"
           class="btn btn-outline-light px-4 py-2 fw-semibold d-flex align-items-center gap-2 flex-shrink-0 offer-enquire-btn">
            Enquire
        </a>
    </div>
</div>

@section('scripts')
<script>
    function copyPromoCode() {
        var text = document.getElementById("promo-code").innerText;
        navigator.clipboard.writeText(text).then(function () {
            var btnText = document.getElementById("copy-btn-text");
            btnText.innerText = "Copied!";
            setTimeout(function () { btnText.innerText = "Copy Code"; }, 3000);
        });
    }

    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection
@endsection
