@extends('layouts.frontend')

@section('content')
<!-- Page Banner -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">Explore Top Destinations</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
            Find cheap flight routes and exclusive deals to major cities across the USA and worldwide.
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Destinations Section -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <!-- Interactive Category Tabs -->
        <div class="row mb-5 justify-content-center" data-aos="fade-up">
            <div class="col-lg-6 text-center">
                <ul class="nav nav-pills custom-search-tabs justify-content-center border-0" id="destinationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2.5 text-uppercase fw-semibold text-decoration-none" 
                                id="domestic-tab" data-bs-toggle="pill" data-bs-target="#domestic-panes" 
                                type="button" role="tab" aria-controls="domestic-panes" aria-selected="true">
                            Domestic USA
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2.5 text-uppercase fw-semibold text-decoration-none ms-2" 
                                id="international-tab" data-bs-toggle="pill" data-bs-target="#international-panes" 
                                type="button" role="tab" aria-controls="international-panes" aria-selected="false">
                            International
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Panes Content -->
        <div class="tab-content" id="destinationTabsContent">
            <!-- Domestic Tab -->
            <div class="tab-pane fade show active" id="domestic-panes" role="tabpanel" aria-labelledby="domestic-tab">
                @if($domesticDestinations->isEmpty())
                    <div class="text-center py-5" data-aos="fade-up">
                        <i class="fa-solid fa-map-location-dot fs-1 text-muted mb-3"></i>
                        <h3>No Domestic Destinations</h3>
                        <p class="text-muted">We are currently updating our domestic routes database. Please check back shortly.</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($domesticDestinations as $dest)
                            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                                <a href="{{ route('destinations.show', $dest->slug) }}" class="text-decoration-none">
                                    <div class="dest-card position-relative overflow-hidden rounded-4 shadow-sm border border-light" style="height: 320px;">
                                        <!-- Image with 105% scale zoom hover -->
                                        <img src="{{ $dest->featured_image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80&auto=format' }}" 
                                             alt="{{ $dest->name }}" 
                                             class="w-100 h-100 object-fit-cover transition-transform">
                                        
                                        <!-- Airport Code top Badge -->
                                        @if(!empty($dest->airport_code))
                                            <span class="position-absolute top-3.5 start-3.5 badge bg-navy text-gold text-uppercase fw-bold px-2.5 py-1.5 font-monospace shadow">
                                                {{ $dest->airport_code }}
                                            </span>
                                        @endif

                                        <!-- Dark overlay with details -->
                                        <div class="overlay d-flex flex-column justify-content-end p-4">
                                            <h3 class="h4 text-white fw-bold mb-1">{{ $dest->name }}</h3>
                                            <span class="text-muted-white small mb-3">
                                                <i class="fa-solid fa-location-dot me-1 text-gold"></i> {{ $dest->state ?? 'USA' }}
                                            </span>
                                            
                                            <div class="d-flex align-items-center justify-content-between border-top border-secondary pt-2">
                                                <span class="text-white small">Flights starting from</span>
                                                <span class="price fw-bold font-monospace fs-5">${{ number_format($dest->starting_price, 0) }}</span>
                                            </div>
                                            
                                            <div class="text-gold fw-semibold small mt-2 d-flex align-items-center gap-1">
                                                Explore Flights <i class="fa-solid fa-arrow-right fs-9"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- International Tab -->
            <div class="tab-pane fade" id="international-panes" role="tabpanel" aria-labelledby="international-tab">
                @if($internationalDestinations->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa-solid fa-map-location-dot fs-1 text-muted mb-3"></i>
                        <h3>No International Destinations</h3>
                        <p class="text-muted">We are currently expanding our overseas routes contracts. Call us for custom routes.</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($internationalDestinations as $dest)
                            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                                <a href="{{ route('destinations.show', $dest->slug) }}" class="text-decoration-none">
                                    <div class="dest-card position-relative overflow-hidden rounded-4 shadow-sm border border-light" style="height: 320px;">
                                        <!-- Image -->
                                        <img src="{{ $dest->featured_image ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80&auto=format' }}" 
                                             alt="{{ $dest->name }}" 
                                             class="w-100 h-100 object-fit-cover transition-transform">
                                        
                                        <!-- Airport Code Badge -->
                                        @if(!empty($dest->airport_code))
                                            <span class="position-absolute top-3.5 start-3.5 badge bg-navy text-gold text-uppercase fw-bold px-2.5 py-1.5 font-monospace shadow">
                                                {{ $dest->airport_code }}
                                            </span>
                                        @endif

                                        <!-- Dark overlay with details -->
                                        <div class="overlay d-flex flex-column justify-content-end p-4">
                                            <h3 class="h4 text-white fw-bold mb-1">{{ $dest->name }}</h3>
                                            <span class="text-muted-white small mb-3">
                                                <i class="fa-solid fa-earth-americas me-1 text-gold"></i> {{ $dest->country ?? 'International' }}
                                            </span>
                                            
                                            <div class="d-flex align-items-center justify-content-between border-top border-secondary pt-2">
                                                <span class="text-white small">Flights starting from</span>
                                                <span class="price fw-bold font-monospace fs-5">${{ number_format($dest->starting_price, 0) }}</span>
                                            </div>
                                            
                                            <div class="text-gold fw-semibold small mt-2 d-flex align-items-center gap-1">
                                                Explore Flights <i class="fa-solid fa-arrow-right fs-9"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
