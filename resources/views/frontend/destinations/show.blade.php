@extends('layouts.frontend')

@section('content')
<!-- Destination Hero Banner -->
<section class="position-relative text-white py-5 d-flex align-items-center dest-hero-section" style="min-height: 450px;">
    <!-- Background Banner Image -->
    <div class="position-absolute inset-0 z-0">
        <img src="{{ $destination->banner_image ?? $destination->featured_image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1600&q=80&auto=format' }}" 
             alt="{{ $destination->name }}" 
             class="w-100 h-100 object-fit-cover" 
             style="filter: brightness(45%); object-position: center;">
    </div>
    
    <!-- Hero Content Overlay -->
    <div class="container position-relative z-1 py-5 text-center text-md-start">
        <div class="row align-items-center g-4">
            <div class="col-md-8" data-aos="fade-up">
                @if(!empty($destination->airport_code))
                    <span class="badge bg-gold text-navy text-uppercase fw-bold px-3 py-2 font-monospace fs-7 shadow-sm mb-3">
                        Airport Code: {{ $destination->airport_code }}
                    </span>
                @endif
                <h1 class="display-3 fw-bold text-white mb-2">{{ $destination->name }}</h1>
                <p class="lead text-muted-white mb-0 fs-5">
                    <i class="fa-solid fa-location-dot text-gold me-2"></i> 
                    @if($destination->is_domestic)
                        {{ $destination->state ?? 'United States' }}, USA
                    @else
                        {{ $destination->country ?? 'International' }}
                    @endif
                </p>
            </div>
            
            <div class="col-md-4 text-center text-md-end" data-aos="fade-up" data-aos-delay="100">
                <div class="d-inline-block p-4 rounded-4 shadow-lg text-center" style="background-color: rgba(7, 17, 31, 0.85); backdrop-filter: blur(10px); border: 1.5px solid rgba(245, 158, 11, 0.4); max-width: 280px; width: 100%;">
                    <span class="d-block small text-uppercase fw-bold mb-1" style="color: rgba(255,255,255,0.7) !important; letter-spacing: 0.5px; font-size: 0.75rem;">Flights starting from</span>
                    <span class="price fs-2 fw-extrabold font-monospace d-block mb-3 text-gold" style="color: #F59E0B !important;">
                        ${{ number_format($destination->starting_price, 0) }}
                    </span>
                    <a href="#booking-form-section" class="btn btn-gold w-100 px-4 py-2.5 text-navy fw-bold text-nowrap rounded-3 shadow transition-lift" style="background-color: #F59E0B !important; color: #07111F !important; border: none;">
                        Book Flight Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Block -->
<section class="py-5 bg-white dest-content-section">
    <div class="container py-3">
        <!-- 3 Info Pills Strip -->
        <div class="row g-4 mb-5" data-aos="fade-up">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 transition-lift-card" style="border-top: 3px solid #F59E0B !important; border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                    <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-tag text-gold fs-5" style="color: #F59E0B !important;"></i>
                        </div>
                        <span class="text-muted d-block small text-uppercase fw-semibold tracking-wider mb-1">Starting From</span>
                        <span class="fw-bold text-navy fs-5 font-monospace">${{ number_format($destination->starting_price, 0) }} <span class="fs-8 text-muted fw-normal">(One-way)</span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 transition-lift-card" style="border-top: 3px solid #F59E0B !important; border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                    <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-calendar-check text-gold fs-5" style="color: #F59E0B !important;"></i>
                        </div>
                        <span class="text-muted d-block small text-uppercase fw-semibold tracking-wider mb-1">Best Time to Visit</span>
                        <span class="fw-bold text-navy fs-5">{{ $destination->best_time_to_visit ?? 'Year-round' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 transition-lift-card" style="border-top: 3px solid #F59E0B !important; border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                    <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">
                        <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-cloud-sun text-gold fs-5" style="color: #F59E0B !important;"></i>
                        </div>
                        <span class="text-muted d-block small text-uppercase fw-semibold tracking-wider mb-1">Climate Description</span>
                        <span class="fw-bold text-navy fs-5">{{ $destination->climate ?? 'Moderate / Mild' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <!-- Left Side: Editorial description and Lightbox Gallery -->
            <div class="col-lg-8" data-aos="fade-up">
                <!-- Navigation Breadcrumbs -->
                <div class="mb-4">
                    @include('partials.frontend.breadcrumb')
                </div>

                <h2 class="h3 fw-bold text-navy mb-3">About {{ $destination->name }}</h2>
                <div class="prose-content mb-5">
                    {!! $destination->description !!}
                </div>

                <!-- CSS Lightbox Gallery Grid -->
                <h3 class="h4 fw-bold text-navy mb-4">Destination Gallery</h3>
                
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

                <div class="row g-3 gallery-grid mb-5">
                    @foreach($images as $img)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="gallery-item position-relative overflow-hidden rounded-3 border border-light shadow-sm" style="height: 140px; cursor: pointer;" onclick="openLightbox('{{ $img }}')">
                                <img src="{{ $img }}" alt="{{ $destination->name }} Gallery Image" class="w-100 h-100 object-fit-cover">
                                <div class="overlay-zoom">
                                    <i class="fa-solid fa-magnifying-glass-plus fs-4 text-gold" style="color: #F59E0B !important;"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Sticky Sidebar: Quick Enquiry Form -->
            <div class="col-lg-4" id="booking-form-section">
                <div class="sticky-top" style="top: 100px; z-index: 10;" data-aos="fade-up">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <!-- Premium Navy Header -->
                        <div class="bg-navy p-4 text-center position-relative">
                            <span class="badge bg-gold text-navy text-uppercase fw-bold rounded px-2.5 py-1.5 fs-9 mb-2 shadow-sm" style="color: #07111F !important; background-color: #F59E0B !important;">
                                Wholesale Phone Rates
                            </span>
                            <h4 class="h5 fw-bold text-white mb-1">Book Flights to {{ $destination->name }}</h4>
                            <p class="text-white-50 mb-0 small" style="font-size: 0.82rem;">Submit details below to unlock phone-only discounts.</p>
                        </div>
                        
                        <!-- Form Body -->
                        <div class="card-body p-4 bg-white">
                            <form action="{{ route('booking.submit') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="to_airport" value="{{ $destination->name }} ({{ $destination->airport_code ?? '' }})">
                                <input type="hidden" name="cabin_class" value="economy">
                                <input type="hidden" name="trip_type" value="one_way">
                                <input type="hidden" name="adults" value="1">

                                <!-- Input: Departure -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1.5 fw-semibold uppercase-label">Departure Airport</label>
                                    <div class="input-group search-input-group shadow-sm border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-plane-departure"></i></span>
                                        <input type="text" name="from_airport" class="form-control bg-light border-0 py-2.5 fs-7.5" placeholder="e.g. JFK or New York" required style="outline: none; box-shadow: none;">
                                    </div>
                                </div>

                                <!-- Input: Full Name -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1.5 fw-semibold uppercase-label">Full Name</label>
                                    <div class="input-group search-input-group shadow-sm border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" name="name" class="form-control bg-light border-0 py-2.5 fs-7.5" placeholder="John Doe" required style="outline: none; box-shadow: none;">
                                    </div>
                                </div>

                                <!-- Input: Email -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1.5 fw-semibold uppercase-label">Email Address</label>
                                    <div class="input-group search-input-group shadow-sm border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control bg-light border-0 py-2.5 fs-7.5" placeholder="john@example.com" required style="outline: none; box-shadow: none;">
                                    </div>
                                </div>

                                <!-- Input: Phone Number -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1.5 fw-semibold uppercase-label">Phone Number</label>
                                    <div class="input-group search-input-group shadow-sm border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-phone"></i></span>
                                        <input type="tel" name="phone" class="form-control bg-light border-0 py-2.5 fs-7.5" placeholder="+1 (555) 000-0000" required style="outline: none; box-shadow: none;">
                                    </div>
                                </div>

                                <!-- Input: Travel Date -->
                                <div class="mb-4">
                                    <label class="form-label text-muted fs-8 mb-1.5 fw-semibold uppercase-label">Travel Date</label>
                                    <div class="input-group search-input-group shadow-sm border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-light border-0 text-muted"><i class="fa-solid fa-calendar-days"></i></span>
                                        <input type="date" name="departure_date" class="form-control bg-light border-0 py-2.5 fs-7.5" required min="{{ date('Y-m-d') }}" style="outline: none; box-shadow: none;">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold w-100 py-3 text-navy fw-bold rounded-3 mb-2.5 transition-lift shadow" style="background-color: #F59E0B !important; color: #07111F !important; border: none;">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Booking Enquiry
                                </button>
                            </form>

                            <!-- Divider -->
                            <div class="d-flex align-items-center my-3">
                                <hr class="flex-grow-1 text-muted opacity-25">
                                <span class="px-2.5 text-muted small" style="font-size: 0.75rem; letter-spacing: 0.5px;">OR CALL DIRECT</span>
                                <hr class="flex-grow-1 text-muted opacity-25">
                            </div>

                            <!-- Direct helplines -->
                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}" class="btn btn-navy w-100 py-3 fw-bold font-monospace shadow text-white d-flex align-items-center justify-content-center gap-2 rounded-3 transition-lift" style="background-color: #07111F !important;">
                                    <i class="fa-solid fa-headset text-gold" style="color: #F59E0B !important;"></i> {{ $callSettings->phone }}
                                </a>
                            @else
                                <a href="tel:+18005550199" class="btn btn-navy w-100 py-3 fw-bold font-monospace shadow text-white d-flex align-items-center justify-content-center gap-2 rounded-3 transition-lift" style="background-color: #07111F !important;">
                                    <i class="fa-solid fa-headset text-gold" style="color: #F59E0B !important;"></i> +1 (800) 555-0199
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal Overlay -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 position-relative text-center">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-lg fs-5" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1060;"></button>
                <img id="lightbox-image" src="" alt="Lightbox View" class="img-fluid rounded-3 border border-dark border-opacity-50">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openLightbox(url) {
        document.getElementById('lightbox-image').src = url;
        var myModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
        myModal.show();
    }

    // Bootstrap validation trigger
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<style>
/* 3 Info Pills Strip - Premium Lift Animation */
.transition-lift-card {
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.transition-lift-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(7, 17, 31, 0.08) !important;
}

/* Sidebar Custom Labels */
.uppercase-label {
    letter-spacing: 0.5px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.72rem !important;
}

/* Custom Gallery Portfolio Overlay Zoom */
.gallery-item {
    position: relative;
    overflow: hidden;
}
.gallery-item img {
    transition: transform 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.gallery-item:hover img {
    transform: scale(1.08);
}
.gallery-item .overlay-zoom {
    position: absolute;
    inset: 0;
    background: rgba(7, 17, 31, 0.4);
    backdrop-filter: blur(2px);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}
.gallery-item:hover .overlay-zoom {
    opacity: 1;
}

/* Page Spacing Controls to Eliminate White Gaps */
.dest-hero-section {
    margin-top: 0 !important;
    padding-top: 110px !important;
}

@media (max-width: 767.98px) {
    /* Seamless hero overlap under fixed navbar + tighter layout */
    .dest-hero-section {
        min-height: 380px !important;
        padding-top: 80px !important;
        padding-bottom: 24px !important;
    }
    
    /* Reduce top space of the content block below the hero image */
    .dest-content-section {
        padding-top: 24px !important;
        padding-bottom: 32px !important;
    }
}
</style>
@endsection
