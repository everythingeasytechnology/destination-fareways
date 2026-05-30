@extends('layouts.frontend')

@section('content')

<!-- Premium Destinations Hero — mirrors offers hero -->
<section class="offers-hero-section bg-navy text-white mt-5 position-relative overflow-hidden">
    <div class="offers-hero-pattern"></div>
    <div class="offers-hero-orb orb-1"></div>
    <div class="offers-hero-orb orb-2"></div>

    <div class="container py-5 text-center position-relative" style="z-index: 2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <span class="live-dot"></span>
            <span>Destinations — Updated Daily</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
            Explore Top Destinations
        </h1>
        <p class="lead mb-4 text-muted-white" data-aos="fade-up" data-aos-delay="120">
            Find cheap flight routes &amp; exclusive deals to major cities across the USA and worldwide.
        </p>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="160">
            @include('partials.frontend.breadcrumb')
        </div>

        <!-- Stats Pills -->
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="220">
            <div class="offers-stat-pill">
                <i class="fa-solid fa-map-location-dot text-gold me-2"></i>{{ ($domesticDestinations->count() + $internationalDestinations->count()) }}+ Destinations
            </div>
            <div class="offers-stat-pill">
                <i class="fa-solid fa-plane text-gold me-2"></i>50+ Partner Airlines
            </div>
            <div class="offers-stat-pill">
                <i class="fa-solid fa-piggy-bank text-gold me-2"></i>Best Price Guarantee
            </div>
        </div>
    </div>
</section>

<!-- Filter & Grid -->
<section class="py-5 bg-softgray offers-main-section">
    <div class="container">

        <!-- Filter Tabs -->
        <div class="row mb-5 justify-content-center" data-aos="fade-up">
            <div class="col-12 col-lg-auto">
                <div class="offers-filter-scroll">
                    <ul class="nav border-0 justify-content-center custom-search-tabs offers-filter-tabs" id="destinationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="pill"
                                    data-bs-target="#all-panes" type="button" role="tab" aria-selected="true">
                                <i class="fa-solid fa-border-all me-1"></i> All
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="domestic-tab" data-bs-toggle="pill"
                                    data-bs-target="#domestic-panes" type="button" role="tab" aria-selected="false">
                                <i class="fa-solid fa-flag-usa me-1"></i> Domestic USA
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="international-tab" data-bs-toggle="pill"
                                    data-bs-target="#international-panes" type="button" role="tab" aria-selected="false">
                                <i class="fa-solid fa-earth-americas me-1"></i> International
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="destinationTabsContent">

            @php
                $allDestinations = $domesticDestinations->merge($internationalDestinations);
            @endphp

            {{-- ── ALL TAB ── --}}
            <div class="tab-pane fade show active" id="all-panes" role="tabpanel" aria-labelledby="all-tab">
                @if($allDestinations->isEmpty())
                    <div class="text-center py-5 mx-auto" style="max-width: 460px;" data-aos="fade-up">
                        <div class="offers-empty-icon mb-4"><i class="fa-solid fa-map-location-dot"></i></div>
                        <h3 class="h4 fw-bold text-navy mb-2">No Destinations Yet</h3>
                        <p class="text-muted mb-4">We are currently updating our database. Call us for custom routes.</p>
                        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}" class="btn btn-gold px-4 py-2 d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-phone"></i> Call for Routes
                        </a>
                    </div>
                @else
                    <div class="row g-4 mb-5">
                        @foreach($allDestinations as $dest)
                            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}">
                                <a href="{{ route('destinations.show', $dest->slug) }}" class="offer-premium-card d-flex flex-column h-100 text-decoration-none">
                                    <!-- Image Zone -->
                                    <div class="offer-card-img-wrap">
                                        <img src="{{ $dest->featured_image ? asset('storage/' . $dest->featured_image) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80&auto=format' }}"
                                             alt="{{ $dest->name }}" loading="lazy" class="offer-card-img">
                                        <div class="offer-card-img-gradient"></div>

                                        <!-- Airport code badge -->
                                        @if(!empty($dest->airport_code))
                                            <div class="offer-card-top-badges">
                                                <span class="offer-badge-save">{{ $dest->airport_code }}</span>
                                            </div>
                                        @endif

                                        <!-- City name strip -->
                                        <div class="offer-card-route-strip">
                                            <span class="fw-bold text-uppercase">{{ $dest->name }}</span>
                                            @if($dest->is_domestic)
                                                <span class="offer-route-icon"><i class="fa-solid fa-location-dot"></i></span>
                                                <span class="fw-bold text-uppercase">{{ $dest->state ?? 'USA' }}</span>
                                            @else
                                                <span class="offer-route-icon"><i class="fa-solid fa-earth-americas"></i></span>
                                                <span class="fw-bold text-uppercase">{{ $dest->country ?? 'International' }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Body Zone -->
                                    <div class="offer-card-body-premium flex-grow-1 d-flex flex-column">
                                        <!-- Category tag -->
                                        <div class="offer-airline-tag mb-2">
                                            <i class="fa-solid {{ $dest->is_domestic ? 'fa-flag-usa' : 'fa-earth-americas' }} text-gold"></i>
                                            <span>{{ $dest->is_domestic ? 'Domestic USA' : 'International' }}</span>
                                        </div>

                                        <h3 class="offer-card-title-premium mb-1">{{ $dest->name }}</h3>

                                        <p class="offer-card-desc-premium flex-grow-1 mb-3">
                                            {{ Str::limit(strip_tags($dest->description ?? 'Discover amazing flight deals and exclusive routes to ' . $dest->name . '. Book now and save big.'), 90) }}
                                        </p>

                                        <!-- Price + meta -->
                                        <div class="offer-price-row-premium">
                                            <div>
                                                <div class="d-flex align-items-baseline gap-1">
                                                    <span class="offer-price-premium">${{ number_format($dest->starting_price, 0) }}</span>
                                                    <span class="offer-price-unit">/ one-way</span>
                                                </div>
                                            </div>
                                            @if(!empty($dest->best_time_to_visit))
                                                <div class="offer-validity">
                                                    <i class="fa-regular fa-calendar me-1"></i>
                                                    {{ Str::limit($dest->best_time_to_visit, 18) }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- CTA row -->
                                        <div class="offer-card-cta-row mt-3">
                                            <span class="offer-cta-label">Explore Flights</span>
                                            <span class="offer-cta-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── DOMESTIC TAB ── --}}
            <div class="tab-pane fade" id="domestic-panes" role="tabpanel" aria-labelledby="domestic-tab">
                @if($domesticDestinations->isEmpty())
                    <div class="text-center py-5 mx-auto" style="max-width: 460px;" data-aos="fade-up">
                        <div class="offers-empty-icon mb-4"><i class="fa-solid fa-flag-usa"></i></div>
                        <h3 class="h4 fw-bold text-navy mb-2">No Domestic Destinations</h3>
                        <p class="text-muted mb-4">We are currently updating our domestic routes. Please check back shortly.</p>
                        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}" class="btn btn-gold px-4 py-2 d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-phone"></i> Call for Routes
                        </a>
                    </div>
                @else
                    <div class="row g-4 mb-5">
                        @foreach($domesticDestinations as $dest)
                            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}">
                                <a href="{{ route('destinations.show', $dest->slug) }}" class="offer-premium-card d-flex flex-column h-100 text-decoration-none">
                                    <div class="offer-card-img-wrap">
                                        <img src="{{ $dest->featured_image ? asset('storage/' . $dest->featured_image) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80&auto=format' }}"
                                             alt="{{ $dest->name }}" loading="lazy" class="offer-card-img">
                                        <div class="offer-card-img-gradient"></div>
                                        @if(!empty($dest->airport_code))
                                            <div class="offer-card-top-badges">
                                                <span class="offer-badge-save">{{ $dest->airport_code }}</span>
                                            </div>
                                        @endif
                                        <div class="offer-card-route-strip">
                                            <span class="fw-bold text-uppercase">{{ $dest->name }}</span>
                                            <span class="offer-route-icon"><i class="fa-solid fa-location-dot"></i></span>
                                            <span class="fw-bold text-uppercase">{{ $dest->state ?? 'USA' }}</span>
                                        </div>
                                    </div>
                                    <div class="offer-card-body-premium flex-grow-1 d-flex flex-column">
                                        <div class="offer-airline-tag mb-2">
                                            <i class="fa-solid fa-flag-usa text-gold"></i>
                                            <span>Domestic USA</span>
                                        </div>
                                        <h3 class="offer-card-title-premium mb-1">{{ $dest->name }}</h3>
                                        <p class="offer-card-desc-premium flex-grow-1 mb-3">
                                            {{ Str::limit(strip_tags($dest->description ?? 'Discover amazing flight deals and exclusive routes to ' . $dest->name . '. Book now and save big.'), 90) }}
                                        </p>
                                        <div class="offer-price-row-premium">
                                            <div>
                                                <div class="d-flex align-items-baseline gap-1">
                                                    <span class="offer-price-premium">${{ number_format($dest->starting_price, 0) }}</span>
                                                    <span class="offer-price-unit">/ one-way</span>
                                                </div>
                                            </div>
                                            @if(!empty($dest->best_time_to_visit))
                                                <div class="offer-validity">
                                                    <i class="fa-regular fa-calendar me-1"></i>{{ Str::limit($dest->best_time_to_visit, 18) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="offer-card-cta-row mt-3">
                                            <span class="offer-cta-label">Explore Flights</span>
                                            <span class="offer-cta-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── INTERNATIONAL TAB ── --}}
            <div class="tab-pane fade" id="international-panes" role="tabpanel" aria-labelledby="international-tab">
                @if($internationalDestinations->isEmpty())
                    <div class="text-center py-5 mx-auto" style="max-width: 460px;" data-aos="fade-up">
                        <div class="offers-empty-icon mb-4"><i class="fa-solid fa-earth-americas"></i></div>
                        <h3 class="h4 fw-bold text-navy mb-2">No International Destinations</h3>
                        <p class="text-muted mb-4">We are currently expanding our overseas routes. Call us for custom international routes.</p>
                        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}" class="btn btn-gold px-4 py-2 d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-phone"></i> Call for Routes
                        </a>
                    </div>
                @else
                    <div class="row g-4 mb-5">
                        @foreach($internationalDestinations as $dest)
                            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}">
                                <a href="{{ route('destinations.show', $dest->slug) }}" class="offer-premium-card d-flex flex-column h-100 text-decoration-none">
                                    <div class="offer-card-img-wrap">
                                        <img src="{{ $dest->featured_image ? asset('storage/' . $dest->featured_image) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&q=80&auto=format' }}"
                                             alt="{{ $dest->name }}" loading="lazy" class="offer-card-img">
                                        <div class="offer-card-img-gradient"></div>
                                        @if(!empty($dest->airport_code))
                                            <div class="offer-card-top-badges">
                                                <span class="offer-badge-save">{{ $dest->airport_code }}</span>
                                            </div>
                                        @endif
                                        <div class="offer-card-route-strip">
                                            <span class="fw-bold text-uppercase">{{ $dest->name }}</span>
                                            <span class="offer-route-icon"><i class="fa-solid fa-earth-americas"></i></span>
                                            <span class="fw-bold text-uppercase">{{ $dest->country ?? 'International' }}</span>
                                        </div>
                                    </div>
                                    <div class="offer-card-body-premium flex-grow-1 d-flex flex-column">
                                        <div class="offer-airline-tag mb-2">
                                            <i class="fa-solid fa-earth-americas text-gold"></i>
                                            <span>International</span>
                                        </div>
                                        <h3 class="offer-card-title-premium mb-1">{{ $dest->name }}</h3>
                                        <p class="offer-card-desc-premium flex-grow-1 mb-3">
                                            {{ Str::limit(strip_tags($dest->description ?? 'Discover amazing flight deals and exclusive routes to ' . $dest->name . '. Book now and save big.'), 90) }}
                                        </p>
                                        <div class="offer-price-row-premium">
                                            <div>
                                                <div class="d-flex align-items-baseline gap-1">
                                                    <span class="offer-price-premium">${{ number_format($dest->starting_price, 0) }}</span>
                                                    <span class="offer-price-unit">/ one-way</span>
                                                </div>
                                            </div>
                                            @if(!empty($dest->best_time_to_visit))
                                                <div class="offer-validity">
                                                    <i class="fa-regular fa-calendar me-1"></i>{{ Str::limit($dest->best_time_to_visit, 18) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="offer-card-cta-row mt-3">
                                            <span class="offer-cta-label">Explore Flights</span>
                                            <span class="offer-cta-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div><!-- /tab-content -->
    </div>
</section>

<!-- Premium CTA Strip — identical to offers page -->
<section class="py-5 bg-white border-top border-light">
    <div class="container">
        <div class="offers-cta-card" data-aos="fade-up">
            <div class="offers-cta-glow"></div>
            <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
                <div class="col-12 col-lg-7">
                    <span class="badge bg-gold text-navy px-3 py-1 mb-2 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">EXCLUSIVE ACCESS</span>
                    <h3 class="h2 fw-bold text-white mb-2">Want Unpublished Bulk Fares?</h3>
                    <p class="mb-0" style="color: rgba(255,255,255,0.72);">
                        Our wholesale contracts unlock private rates up to 40% below what you see online. One call is all it takes.
                    </p>
                </div>
                <div class="col-12 col-lg-5 text-center text-lg-end">
                    <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
                       class="btn btn-gold px-4 py-3 d-inline-flex align-items-center gap-2 fw-bold fs-6">
                        <i class="fa-solid fa-phone"></i> Call Reservations Now
                    </a>
                    <p class="mt-2 mb-0 small" style="color: rgba(255,255,255,0.5);">Available 24/7 — No hold music</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
