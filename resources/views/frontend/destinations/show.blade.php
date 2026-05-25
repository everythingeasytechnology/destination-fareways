@extends('layouts.frontend')

@section('content')
<!-- Destination Hero Banner -->
<section class="position-relative text-white py-5 d-flex align-items-center mt-5" style="min-height: 450px;">
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
            
            <div class="col-md-4 text-md-end" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white text-navy d-inline-block p-4 rounded-4 shadow-lg border border-light text-center">
                    <span class="text-muted d-block small text-uppercase fw-semibold mb-1">Flights starting from</span>
                    <span class="price fs-2 fw-bold font-monospace d-block mb-3">
                        ${{ number_format($destination->starting_price, 0) }}
                    </span>
                    <a href="#booking-form-section" class="btn btn-gold w-100 px-4 py-2 text-navy fw-bold text-nowrap">
                        Book Flight Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Block -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <!-- 3 Info Pills Strip -->
        <div class="row g-4 mb-5" data-aos="fade-up">
            <div class="col-md-4">
                <div class="p-3 border rounded-3 text-center bg-softgray h-100 d-flex flex-column justify-content-center">
                    <i class="fa-solid fa-tag text-gold fs-4 mb-2"></i>
                    <span class="text-muted d-block small text-uppercase">Starting From</span>
                    <span class="fw-bold text-navy fs-6 font-monospace">${{ number_format($destination->starting_price, 0) }} (One-way)</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded-3 text-center bg-softgray h-100 d-flex flex-column justify-content-center">
                    <i class="fa-solid fa-calendar-check text-gold fs-4 mb-2"></i>
                    <span class="text-muted d-block small text-uppercase">Best Time to Visit</span>
                    <span class="fw-bold text-navy fs-6">{{ $destination->best_time_to_visit ?? 'Year-round' }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded-3 text-center bg-softgray h-100 d-flex flex-column justify-content-center">
                    <i class="fa-solid fa-cloud-sun text-gold fs-4 mb-2"></i>
                    <span class="text-muted d-block small text-uppercase">Climate Description</span>
                    <span class="fw-bold text-navy fs-6">{{ $destination->climate ?? 'Moderate / Mild' }}</span>
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
                                <img src="{{ $img }}" alt="{{ $destination->name }} Gallery Image" class="w-100 h-100 object-fit-cover transition-transform">
                                <div class="position-absolute inset-0 bg-dark bg-opacity-25 opacity-0 transition-opacity d-flex align-items-center justify-content-center text-white">
                                    <i class="fa-solid fa-magnifying-glass-plus fs-4"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Sticky Sidebar: Quick Enquiry Form -->
            <div class="col-lg-4" id="booking-form-section">
                <div class="sticky-top" style="top: 100px; z-index: 10;" data-aos="fade-up">
                    <div class="card card-flight border-light shadow-sm">
                        <div class="card-body p-4 bg-softgray rounded-3">
                            <span class="badge bg-navy text-gold text-uppercase fw-bold rounded px-2.5 py-1 fs-9 mb-3">
                                Booking Inquiry
                            </span>
                            <h4 class="h5 fw-bold text-navy mb-1.5">Book flights to {{ $destination->name }}</h4>
                            <p class="text-muted small mb-4">Submit our quick request, and get wholesale phone rates from our concierges.</p>

                            <form action="{{ route('booking.submit') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="to_airport" value="{{ $destination->name }} ({{ $destination->airport_code ?? '' }})">
                                <input type="hidden" name="cabin_class" value="economy">
                                <input type="hidden" name="trip_type" value="one_way">
                                <input type="hidden" name="adults" value="1">

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Departure Airport</label>
                                    <input type="text" name="from_airport" class="form-control bg-white py-2.5" placeholder="e.g. JFK or New York" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Full Name</label>
                                    <input type="text" name="name" class="form-control bg-white py-2.5" placeholder="John Doe" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Email</label>
                                    <input type="email" name="email" class="form-control bg-white py-2.5" placeholder="john@example.com" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control bg-white py-2.5" placeholder="+1 (555) 000-0000" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted fs-8 mb-1">Preferred Travel Date</label>
                                    <input type="date" name="departure_date" class="form-control bg-white py-2.5" required min="{{ date('Y-m-d') }}">
                                </div>

                                <button type="submit" class="btn btn-gold w-100 py-3 text-navy fw-bold rounded-3 mb-2.5 transition-lift">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Booking Enquiry
                                </button>
                            </form>

                            <!-- Direct helplines -->
                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}" class="btn btn-outline-navy w-100 py-3 fw-semibold font-monospace transition-lift bg-white">
                                    <i class="fa-solid fa-phone text-gold me-2"></i> Call Now: {{ $callSettings->phone }}
                                </a>
                            @else
                                <a href="tel:+18005550199" class="btn btn-outline-navy w-100 py-3 fw-semibold font-monospace transition-lift bg-white">
                                    <i class="fa-solid fa-phone text-gold me-2"></i> Call: +1 (800) 555-0199
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
@endsection
