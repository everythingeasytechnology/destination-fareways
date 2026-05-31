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
@endphp

<!-- Premium Offers Hero -->
<section class="offers-hero-section bg-navy text-white mt-5 position-relative overflow-hidden">
    <div class="offers-hero-pattern"></div>
    <div class="offers-hero-orb orb-1"></div>
    <div class="offers-hero-orb orb-2"></div>

    <div class="container py-5 text-center position-relative" style="z-index: 2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <span class="live-dot"></span>
            <span>Live Deals — Updated Daily</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
            Exclusive Flight Offers
        </h1>
        <p class="lead mb-4 text-muted-white" data-aos="fade-up" data-aos-delay="120">
            Hand-picked domestic &amp; international promotions — unbeatable fares, verified daily.
        </p>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="160">
            @include('partials.frontend.breadcrumb')
        </div>

        <!-- Stats Pills -->
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="220">
            <div class="offers-stat-pill">
                <i class="fa-solid fa-ticket text-gold me-2"></i>{{ $offers->total() ?? $offers->count() }}+ Active Deals
            </div>
            <div class="offers-stat-pill">
                <i class="fa-solid fa-plane text-gold me-2"></i>50+ Partner Airlines
            </div>
            <div class="offers-stat-pill">
                <i class="fa-solid fa-piggy-bank text-gold me-2"></i>Up to 60% Savings
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
                <div class="offers-filter-scroll offers-filter-scroll--hscroll">
                    <ul class="nav border-0 justify-content-center custom-search-tabs offers-filter-tabs offers-filter-tabs--hscroll">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('type') ? 'active' : '' }}" href="{{ route('offers.index') }}">
                                <i class="fa-solid fa-border-all me-1"></i> All Deals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('type') === 'domestic' ? 'active' : '' }}" href="{{ route('offers.index', ['type' => 'domestic']) }}">
                                <i class="fa-solid fa-map me-1"></i> Domestic
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('type') === 'international' ? 'active' : '' }}" href="{{ route('offers.index', ['type' => 'international']) }}">
                                <i class="fa-solid fa-earth-americas me-1"></i> International
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('type') === 'business' ? 'active' : '' }}" href="{{ route('offers.index', ['type' => 'business']) }}">
                                <i class="fa-solid fa-star me-1"></i> Business Class
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Offers Grid -->
        @if($offers->isEmpty())
            <div class="text-center py-5 mx-auto" style="max-width: 460px;" data-aos="fade-up">
                <div class="offers-empty-icon mb-4">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h3 class="h4 fw-bold text-navy mb-2">No Active Offers Right Now</h3>
                <p class="text-muted mb-4">Our team is negotiating new unpublished rates. Call now to access private bulk fares not listed online.</p>
                <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}" class="btn btn-gold px-4 py-2 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-phone"></i> Call for Unpublished Deals
                </a>
            </div>
        @else
            <div class="row g-4 mb-5">
                @foreach($offers as $offer)
                    @php
                        $savingsPct = (!empty($offer->original_price) && $offer->original_price > 0)
                            ? round((($offer->original_price - $offer->offer_price) / $offer->original_price) * 100)
                            : null;
                        $isExpiring = !empty($offer->valid_until) && $offer->valid_until->diffInDays(now()) <= 7 && $offer->valid_until->isFuture();
                        $isNew = $offer->created_at && $offer->created_at->diffInDays(now()) <= 7;
                    @endphp
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}">
                        <a href="{{ route('offers.show', $offer->slug) }}" class="offer-premium-card d-flex flex-column h-100 text-decoration-none">

                            <!-- Image Zone -->
                            <div class="offer-card-img-wrap">
                                <img src="{{ $offerImageUrl($offer->image, 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80&auto=format') }}"
                                     alt="{{ $offer->title }}" loading="lazy" class="offer-card-img">
                                <div class="offer-card-img-gradient"></div>

                                <!-- Badges -->
                                <div class="offer-card-top-badges">
                                    @if($savingsPct && $savingsPct > 0)
                                        <span class="offer-badge-save">Save {{ $savingsPct }}%</span>
                                    @elseif(!empty($offer->discount_label))
                                        <span class="offer-badge-save">{{ $offer->discount_label }}</span>
                                    @endif

                                    @if($isExpiring)
                                        <span class="offer-badge-expiring">
                                            <i class="fa-regular fa-clock me-1"></i>Expiring Soon
                                        </span>
                                    @elseif($isNew)
                                        <span class="offer-badge-new">New</span>
                                    @endif
                                </div>

                                <!-- Route Strip on Image -->
                                @if(!empty($offer->from_city) && !empty($offer->to_city))
                                    <div class="offer-card-route-strip">
                                        <span class="fw-bold text-uppercase">{{ $offer->from_city }}</span>
                                        <span class="offer-route-icon"><i class="fa-solid fa-plane"></i></span>
                                        <span class="fw-bold text-uppercase">{{ $offer->to_city }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Body Zone -->
                            <div class="offer-card-body-premium flex-grow-1 d-flex flex-column">
                                <!-- Airline tag -->
                                <div class="offer-airline-tag mb-2">
                                    <i class="fa-solid fa-plane-up text-gold"></i>
                                    <span>{{ $offer->airline ?? 'Contracted Airlines' }}</span>
                                </div>

                                <h3 class="offer-card-title-premium mb-1">{{ $offer->title }}</h3>

                                <p class="offer-card-desc-premium flex-grow-1 mb-3">
                                    {{ Str::limit($offer->short_desc, 90) }}
                                </p>

                                <!-- Price + Validity -->
                                <div class="offer-price-row-premium">
                                    <div>
                                        @if(!empty($offer->original_price))
                                            <span class="offer-original-price-premium">${{ number_format($offer->original_price, 0) }}</span>
                                        @endif
                                        <div class="d-flex align-items-baseline gap-1">
                                            <span class="offer-price-premium">${{ number_format($offer->offer_price, 0) }}</span>
                                            <span class="offer-price-unit">/ one-way</span>
                                        </div>
                                    </div>
                                    @if(!empty($offer->valid_until))
                                        <div class="offer-validity {{ $isExpiring ? 'offer-validity-urgent' : '' }}">
                                            <i class="fa-regular fa-clock me-1"></i>
                                            {{ $offer->valid_until->format('M d, Y') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- CTA row -->
                                <div class="offer-card-cta-row mt-3">
                                    <span class="offer-cta-label">View Deal</span>
                                    <span class="offer-cta-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($offers->hasPages())
                <div class="d-flex justify-content-center" data-aos="fade-up">
                    {{ $offers->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
</section>

<!-- Premium CTA Strip -->
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

@include('partials.frontend.mobile-cta')

@endsection
