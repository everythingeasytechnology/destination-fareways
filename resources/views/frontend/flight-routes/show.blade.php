@extends('layouts.frontend')

@section('styles')
@php
    $heroBannerImg = null;
    if (!empty($route->banner_image)) {
        $heroBannerImg = str_starts_with($route->banner_image, 'http') ? $route->banner_image : asset('storage/' . ltrim($route->banner_image, '/'));
    } elseif (!empty($route->featured_image)) {
        $heroBannerImg = str_starts_with($route->featured_image, 'http') ? $route->featured_image : asset('storage/' . ltrim($route->featured_image, '/'));
    }
@endphp
@if($heroBannerImg)
    <link rel="preload" as="image" href="{{ $heroBannerImg }}" fetchpriority="high">
@endif
@endsection

@section('content')

@php
    $resolveImg = function(?string $img): ?string {
        if (empty($img)) return null;
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) return $img;
        return asset('storage/' . ltrim($img, '/'));
    };
    $bannerImg = $resolveImg($route->banner_image) ?? $resolveImg($route->featured_image);

    // Pre-fill the search form with this route's origin & destination
    $searchDefaults = [
        'from' => $route->origin_airport_code ?: $route->origin_city,
        'to'   => $route->destination_airport_code ?: $route->destination_city,
    ];
@endphp

<!-- Page Header / Banner -->
<section class="bg-navy text-white mt-5 position-relative overflow-hidden" style="min-height: 300px;">
    @if($bannerImg)
        <div class="position-absolute w-100 h-100" style="top:0;left:0;z-index:0;">
            <img src="{{ $bannerImg }}" alt="{{ $route->title }}" class="w-100 h-100 object-fit-cover" style="opacity: 0.22;">
        </div>
    @endif
    <div class="offers-hero-pattern"></div>

    <div class="container py-5 text-center position-relative" style="z-index: 2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <span class="live-dot"></span>
            <span>{{ $route->is_domestic ? 'Domestic Route' : 'International Route' }}</span>
        </div>

        {{-- Route Arrow Display --}}
        <div class="d-flex align-items-center justify-content-center gap-3 mb-3" data-aos="fade-up" data-aos-delay="60">
            <div class="text-center">
                <div class="fw-bold text-white" style="font-size: 1.8rem; line-height: 1;">{{ $route->origin_city }}</div>
                @if($route->origin_airport_code)
                    <span class="badge bg-gold text-navy font-monospace mt-1" style="background-color: #F59E0B !important; font-size: 0.85rem;">{{ $route->origin_airport_code }}</span>
                @endif
            </div>
            <div class="text-center px-2">
                <i class="fa-solid fa-plane text-gold" style="font-size: 2rem;"></i>
            </div>
            <div class="text-center">
                <div class="fw-bold text-white" style="font-size: 1.8rem; line-height: 1;">{{ $route->destination_city }}</div>
                @if($route->destination_airport_code)
                    <span class="badge bg-gold text-navy font-monospace mt-1" style="background-color: #F59E0B !important; font-size: 0.85rem;">{{ $route->destination_airport_code }}</span>
                @endif
            </div>
        </div>

        @if($route->short_desc)
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">{{ $route->short_desc }}</p>
        @endif

        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="140">
            @include('partials.frontend.breadcrumb')
        </div>

        {{-- Route meta pills --}}
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="180">
            @if($route->starting_price > 0)
            <div class="offers-stat-pill">
                <i class="fa-solid fa-tag text-gold me-2"></i>From ${{ number_format($route->starting_price, 0) }}
            </div>
            @endif
            @if($route->flight_duration)
            <div class="offers-stat-pill">
                <i class="fa-regular fa-clock text-gold me-2"></i>{{ $route->flight_duration }}
            </div>
            @endif
            @if($route->frequency)
            <div class="offers-stat-pill">
                <i class="fa-solid fa-calendar-days text-gold me-2"></i>{{ $route->frequency }}
            </div>
            @endif
        </div>

        {{-- Flight Search Widget --}}
        <div class="row justify-content-center mt-5" data-aos="fade-up" data-aos-delay="240">
            <div class="col-xl-11 col-lg-12">
                @include('partials.frontend.flight-search-form', ['showTripTabs' => true])
            </div>
        </div>

        {{-- Trust Badges --}}
        <div class="page-hero-trust-row mt-4" data-aos="fade-up" data-aos-delay="300">
            <div class="page-hero-trust-item">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Secure Booking</span>
            </div>
            <div class="page-hero-trust-sep"></div>
            <div class="page-hero-trust-item">
                <i class="fa-solid fa-headset"></i>
                <span>24/7 Support</span>
            </div>
            <div class="page-hero-trust-sep"></div>
            <div class="page-hero-trust-item">
                <i class="fa-solid fa-tag"></i>
                <span>Best Price Guarantee</span>
            </div>
            <div class="page-hero-trust-sep"></div>
            <div class="page-hero-trust-item">
                <i class="fa-solid fa-rotate-left"></i>
                <span>Flexible Changes</span>
            </div>
        </div>

    </div>
</section>

<!-- Main Content -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-5">

            <!-- Left: Article -->
            <div class="col-lg-8">

                {{-- Airlines --}}
                @if($route->airlines)
                <div class="d-flex align-items-center gap-2 mb-4 p-3 border rounded-3 bg-softgray">
                    <i class="fa-solid fa-plane-departure text-gold fs-5"></i>
                    <div>
                        <div class="fw-bold text-navy small">Airlines on This Route</div>
                        <div class="text-muted" style="font-size: 0.88rem;">{{ $route->airlines }}</div>
                    </div>
                </div>
                @endif

                {{-- Rich description --}}
                @if($route->description)
                <div class="prose-content" style="line-height: 1.8; color: #374151;">
                    {!! $route->description !!}
                </div>
                @endif

                {{-- FAQ Section --}}
                @php
                    $faqs = $route->faq_schema ? json_decode($route->faq_schema, true) : [];
                    $faqs = is_array($faqs) ? array_filter($faqs, fn($f) => !empty($f['question'])) : [];
                @endphp
                @if(!empty($faqs))
                <div class="mt-5">
                    <h2 class="h4 fw-bold text-navy mb-4">
                        <i class="fa-solid fa-circle-question text-gold me-2"></i>Frequently Asked Questions
                    </h2>
                    <div class="accordion" id="faq-accordion">
                        @foreach(array_values($faqs) as $i => $faq)
                        <div class="accordion-item border mb-2 rounded-3 overflow-hidden">
                            <h3 class="accordion-header" id="faq-h-{{ $i }}">
                                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} fw-semibold text-navy"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq-c-{{ $i }}"
                                        aria-expanded="{{ $i === 0 ? 'true' : 'false' }}"
                                        style="font-size: 0.95rem;">
                                    {{ $faq['question'] }}
                                </button>
                            </h3>
                            <div id="faq-c-{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                 data-bs-parent="#faq-accordion">
                                <div class="accordion-body text-muted" style="font-size: 0.92rem; line-height: 1.7;">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Book CTA --}}
                <div class="mt-5 p-4 bg-navy text-white rounded-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                    <div>
                        <h5 class="fw-bold mb-1">Ready to book?</h5>
                        <p class="mb-0 text-muted-white" style="font-size: 0.9rem;">Our agents find the best fares on {{ $route->origin_city }} → {{ $route->destination_city }} routes — call or enquire now.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('booking.enquiry') }}?from={{ urlencode($route->origin_airport_code ?: $route->origin_city) }}&to={{ urlencode($route->destination_airport_code ?: $route->destination_city) }}" class="btn btn-gold px-4 rounded-pill" style="background-color:#F59E0B;color:#07111F;font-weight:700;border:none;">
                            <i class="fa-solid fa-ticket me-2"></i>Book This Route
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light px-4 rounded-pill">
                            <i class="fa-solid fa-headset me-2"></i>Get Help
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar -->
            <div class="col-lg-4">

                {{-- Quick booking card --}}
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-softgray sticky-top" style="top: 100px; z-index: 10;">
                    <h5 class="fw-bold text-navy mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-bolt text-gold me-2"></i>Quick Enquiry
                    </h5>
                    <div class="mb-3 p-3 border rounded-3 bg-white text-center">
                        <div class="text-muted small mb-1">Starting from</div>
                        @if($route->starting_price > 0)
                            <div class="text-gold fw-bold" style="font-size: 1.8rem;">${{ number_format($route->starting_price, 0) }}</div>
                        @else
                            <div class="text-navy fw-bold" style="font-size: 1.2rem;">Call for Price</div>
                        @endif
                        <div class="text-muted small">{{ $route->origin_city }} → {{ $route->destination_city }}</div>
                    </div>
                    <a href="{{ route('booking.enquiry') }}?from={{ urlencode($route->origin_airport_code ?: $route->origin_city) }}&to={{ urlencode($route->destination_airport_code ?: $route->destination_city) }}" class="btn btn-action w-100 rounded-pill mb-2">
                        <i class="fa-solid fa-ticket me-2"></i>Enquire Now
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                        <i class="fa-solid fa-headset me-2"></i>Speak to an Agent
                    </a>

                    @if($route->flight_duration || $route->frequency || $route->airlines)
                    <hr>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 0.83rem;">
                        @if($route->flight_duration)
                        <li class="d-flex align-items-center gap-2 text-muted">
                            <i class="fa-regular fa-clock text-gold fa-fw"></i>
                            <span><strong>Duration:</strong> {{ $route->flight_duration }}</span>
                        </li>
                        @endif
                        @if($route->frequency)
                        <li class="d-flex align-items-center gap-2 text-muted">
                            <i class="fa-solid fa-calendar-check text-gold fa-fw"></i>
                            <span>{{ $route->frequency }}</span>
                        </li>
                        @endif
                        @if($route->airlines)
                        <li class="d-flex align-items-start gap-2 text-muted">
                            <i class="fa-solid fa-plane text-gold fa-fw mt-1"></i>
                            <span>{{ $route->airlines }}</span>
                        </li>
                        @endif
                    </ul>
                    @endif
                </div>

                {{-- Related routes --}}
                @if($related->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-3 p-4">
                    <h6 class="fw-bold text-navy mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-route text-gold me-2"></i>Related Routes
                    </h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        @foreach($related as $rel)
                        <li>
                            <a href="{{ route('flight-routes.show', $rel->slug) }}" class="d-flex justify-content-between align-items-center text-decoration-none text-navy py-2 border-bottom" style="font-size: 0.87rem;">
                                <span>
                                    <i class="fa-solid fa-arrow-right text-gold me-1" style="font-size: 0.65rem;"></i>
                                    {{ $rel->origin_city }} → {{ $rel->destination_city }}
                                    @if($rel->origin_airport_code && $rel->destination_airport_code)
                                        <span class="text-muted font-monospace" style="font-size: 0.7rem;">({{ $rel->origin_airport_code }}–{{ $rel->destination_airport_code }})</span>
                                    @endif
                                </span>
                                @if($rel->starting_price > 0)
                                    <span class="text-gold fw-bold font-monospace" style="font-size: 0.82rem;">${{ number_format($rel->starting_price, 0) }}</span>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('flight-routes.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill mt-3 w-100">View All Routes</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Mobile Sticky CTA Bar --}}
@include('partials.frontend.mobile-cta')

@endsection
