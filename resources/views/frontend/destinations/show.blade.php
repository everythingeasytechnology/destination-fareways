@extends('layouts.frontend')

@section('content')

@php
    $heroImg = $destination->banner_image
        ?? ($destination->featured_image ? asset('storage/' . $destination->featured_image) : null)
        ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1600&q=80&auto=format';
@endphp

{{-- ─────────────────────────────────────────────────────────────
     HERO  (mirrors offer-detail-hero exactly)
────────────────────────────────────────────────────────────── --}}
<section class="offer-detail-hero mt-5 position-relative overflow-hidden" style="min-height: 380px;">
    <div class="offer-detail-hero-bg" style="background-image: url('{{ $heroImg }}');"></div>
    <div class="offer-detail-hero-overlay"></div>

    <div class="container position-relative py-5" style="z-index: 2; padding-top: 80px !important;">
        <div class="row justify-content-center text-center pt-3">
            <div class="col-lg-9">
                <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
                    <i class="fa-solid fa-location-dot text-gold"></i>
                    <span>{{ $destination->is_domestic ? 'Domestic USA' : 'International' }} Destination</span>
                </div>

                <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
                    {{ $destination->name }}
                </h1>

                <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
                    <i class="fa-solid fa-location-dot text-gold me-2"></i>
                    @if($destination->is_domestic)
                        {{ $destination->state ?? 'United States' }}, USA
                    @else
                        {{ $destination->country ?? 'International' }}
                    @endif
                </p>

                <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="140">
                    @include('partials.frontend.breadcrumb')
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────────────────────────
     CONTENT BODY
────────────────────────────────────────────────────────────── --}}
<section class="py-5 bg-softgray">
    <div class="container py-2">
        <div class="row g-5">

            {{-- ── LEFT: Info + Description + Gallery ── --}}
            <div class="col-lg-8">

                {{-- Route / Info card (mirrors offer-route-premium-card) --}}
                <div class="offer-route-premium-card mb-5" data-aos="fade-up">
                    <div class="row align-items-center g-3">
                        <div class="col text-center text-md-start">
                            <span class="d-block text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.7px;">Destination</span>
                            <span class="offer-city-name">{{ $destination->name }}</span>
                        </div>
                        <div class="col-auto text-center">
                            <div class="offer-route-visual">
                                <div class="route-dot"></div>
                                <div class="route-line">
                                    <i class="fa-solid fa-plane text-gold route-plane-icon"></i>
                                </div>
                                <div class="route-dot"></div>
                            </div>
                            @if(!empty($destination->airport_code))
                                <div class="text-muted small mt-2" style="font-size:.72rem;letter-spacing:.4px;text-transform:uppercase;">
                                    {{ $destination->airport_code }} Airport
                                </div>
                            @endif
                        </div>
                        <div class="col text-center text-md-end">
                            <span class="d-block text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.7px;">Region</span>
                            <span class="offer-city-name">
                                @if($destination->is_domestic)
                                    {{ $destination->state ?? 'USA' }}
                                @else
                                    {{ $destination->country ?? 'International' }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Meta specs (mirrors offer-meta-specs) --}}
                <div class="offer-meta-specs row g-3 mb-5" data-aos="fade-up">
                    <div class="col-6 col-md-4">
                        <div class="offer-meta-tile">
                            <i class="fa-solid fa-tag text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Starting From</span>
                            <span class="fw-semibold text-navy small">${{ number_format($destination->starting_price, 0) }} / one-way</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="offer-meta-tile">
                            <i class="fa-solid fa-calendar-check text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Best Time to Visit</span>
                            <span class="fw-semibold text-navy small">{{ $destination->best_time_to_visit ?? 'Year-round' }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="offer-meta-tile">
                            <i class="fa-solid fa-cloud-sun text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Climate</span>
                            <span class="fw-semibold text-navy small">{{ $destination->climate ?? 'Moderate / Mild' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Rich description --}}
                <div class="prose-content mb-5" data-aos="fade-up">
                    {!! $destination->description !!}
                </div>

                {{-- Trust row (mirrors offer-trust-row) --}}
                <div class="offer-trust-row d-flex flex-wrap gap-3 mb-5" data-aos="fade-up">
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

                {{-- Gallery --}}
                <h3 class="h4 fw-bold text-navy mb-4" data-aos="fade-up">Destination Gallery</h3>
                @php
                    $gallery = $destination->gallery;
                    $fallbackGallery = [
                        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80&auto=format',
                        'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&q=80&auto=format',
                        'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&q=80&auto=format',
                        'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80&auto=format',
                    ];
                    $images = is_array($gallery) && count($gallery) > 0 ? $gallery : $fallbackGallery;
                @endphp

                <div class="row g-3 gallery-grid mb-3" data-aos="fade-up">
                    @foreach($images as $img)
                        <div class="col-6 col-sm-4 col-lg-3">
                            <div class="gallery-item position-relative overflow-hidden rounded-3" style="height:140px;cursor:pointer;" onclick="openLightbox('{{ $img }}')">
                                <img src="{{ $img }}" alt="{{ $destination->name }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                                <div class="overlay-zoom">
                                    <i class="fa-solid fa-magnifying-glass-plus fs-4 text-gold"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── RIGHT: Sticky Sidebar (exact offer-sidebar-card structure) ── --}}
            <div class="col-lg-4" id="booking-form-section">
                <div class="offer-sidebar-sticky">

                    {{-- Price / Booking Card --}}
                    <div class="offer-sidebar-card mb-4" data-aos="fade-up">

                        {{-- Header --}}
                        <div class="offer-sidebar-header">
                            <span class="offer-sidebar-badge">Guaranteed Lowest Rate</span>
                            <div class="d-flex align-items-baseline gap-2 mt-2">
                                <span class="offer-sidebar-price">${{ number_format($destination->starting_price, 0) }}</span>
                                <span class="small text-muted-white">/ one-way</span>
                            </div>
                            <div class="offer-sidebar-savings mt-1">
                                <i class="fa-solid fa-circle-check me-1"></i>
                                Best price guaranteed on all routes
                            </div>
                            <p class="small mt-2 mb-0" style="color:rgba(255,255,255,.6);">
                                Taxes &amp; fees included. Call for private bulk fares.
                            </p>
                        </div>

                        {{-- Booking Form --}}
                        <div class="offer-sidebar-body">
                            <form id="dest-booking-form" action="{{ route('booking.submit') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="to_airport" value="{{ $destination->name }} ({{ $destination->airport_code ?? '' }})">
                                <input type="hidden" name="cabin_class" value="economy">
                                <input type="hidden" name="trip_type" value="one_way">
                                <input type="hidden" name="adults" value="1">

                                <h4 class="h6 fw-bold text-navy mb-3 text-uppercase" style="letter-spacing:.6px;">Quick Booking Enquiry</h4>

                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Departure Airport</label>
                                    <input type="text" name="from_airport" class="form-control offer-form-input" placeholder="e.g. JFK or New York" required>
                                </div>
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
                                    <label class="form-label small text-muted mb-1">Preferred Travel Date</label>
                                    <input type="date" name="departure_date" class="form-control offer-form-input" required min="{{ date('Y-m-d') }}">
                                </div>

                                <button type="submit" class="btn btn-gold w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <i class="fa-solid fa-paper-plane"></i> Submit Enquiry
                                </button>
                            </form>

                            {{-- Divider --}}
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

                        {{-- Trust micro-badges --}}
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

                    {{-- Best Time Alert (mirrors offer-expiry-alert) --}}
                    @if(!empty($destination->best_time_to_visit))
                        <div class="offer-expiry-alert" data-aos="fade-up">
                            <i class="fa-regular fa-calendar text-gold fs-5 me-2 flex-shrink-0"></i>
                            <div>
                                <div class="fw-bold text-navy small">Best Time to Visit</div>
                                <div class="text-muted fw-semibold small">{{ $destination->best_time_to_visit }}</div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ── Mobile Sticky CTA (exact offer-mobile-cta) ── --}}
<div class="offer-mobile-cta d-lg-none">
    <div class="d-flex gap-2 align-items-center">
        <div class="flex-grow-1">
            <span class="d-block text-muted-white small fw-semibold text-uppercase" style="font-size:.68rem;letter-spacing:.5px;">From</span>
            <span class="price fw-bold fs-5 text-gold font-monospace">${{ number_format($destination->starting_price, 0) }}</span>
            <span class="text-muted-white" style="font-size:.75rem;"> / one-way</span>
        </div>
        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
           class="btn btn-gold px-4 py-2 fw-bold text-navy d-flex align-items-center gap-2 flex-shrink-0">
            <i class="fa-solid fa-phone"></i> Call Now
        </a>
        <a href="#booking-form-section"
           class="btn btn-outline-light px-4 py-2 fw-semibold d-flex align-items-center gap-2 flex-shrink-0 offer-enquire-btn">
            Enquire
        </a>
    </div>
</div>

{{-- Lightbox --}}
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 position-relative text-center">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-lg fs-5" data-bs-dismiss="modal" aria-label="Close" style="z-index:1060;"></button>
                <img id="lightbox-image" src="" alt="Gallery" class="img-fluid rounded-3">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openLightbox(url) {
        document.getElementById('lightbox-image').src = url;
        new bootstrap.Modal(document.getElementById('lightboxModal')).show();
    }
    (() => {
        'use strict';
        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', e => {
                if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<style>
/* Gallery */
.gallery-item img { transition: transform .5s cubic-bezier(.165,.84,.44,1); }
.gallery-item:hover img { transform: scale(1.08); }
.gallery-item .overlay-zoom {
    position:absolute; inset:0;
    background:rgba(7,17,31,.4); backdrop-filter:blur(2px);
    opacity:0; transition:opacity .3s ease;
    display:flex; align-items:center; justify-content:center;
}
.gallery-item:hover .overlay-zoom { opacity:1; }

/* Sidebar sticky desktop */
@media (min-width:992px) {
    .offer-sidebar-sticky { position:sticky; top:100px; }
}

/* Mobile padding for sticky bar */
@media (max-width:991.98px) {
    body:has(.offer-mobile-cta) main { padding-bottom: 80px; }
    .offer-sidebar-sticky { position: static !important; }
}
</style>
@endsection
