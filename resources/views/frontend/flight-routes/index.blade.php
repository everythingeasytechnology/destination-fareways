@extends('layouts.frontend')

@section('content')

@php
    $allRoutes = $domesticRoutes->merge($internationalRoutes);
    $resolveImg = function(?string $img): ?string {
        if (empty($img)) return null;
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) return $img;
        return asset('storage/' . ltrim($img, '/'));
    };
@endphp

<!-- Hero -->
<section class="offers-hero-section bg-navy text-white mt-5 position-relative overflow-hidden">
    <div class="offers-hero-pattern"></div>
    <div class="offers-hero-orb orb-1"></div>
    <div class="offers-hero-orb orb-2"></div>

    <div class="container py-5 text-center position-relative" style="z-index: 2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <span class="live-dot"></span>
            <span>Flight Routes — Updated Daily</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
            Popular Flight Routes
        </h1>
        <p class="lead mb-4 text-muted-white" data-aos="fade-up" data-aos-delay="120">
            Explore the best flight corridors from US cities and worldwide — with real pricing and booking support.
        </p>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="160">
            @include('partials.frontend.breadcrumb')
        </div>
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="220">
            <div class="offers-stat-pill">
                <i class="fa-solid fa-route text-gold me-2"></i>{{ $allRoutes->count() }}+ Routes
            </div>
            <div class="offers-stat-pill">
                <i class="fa-solid fa-plane text-gold me-2"></i>50+ Partner Airlines
            </div>
            <div class="offers-stat-pill">
                <i class="fa-solid fa-headset text-gold me-2"></i>24/7 Booking Support
            </div>
        </div>
    </div>
</section>

<!-- Filter Tabs + Grid -->
<section class="py-5 bg-softgray offers-main-section">
    <div class="container">

        <!-- Filter Tabs -->
        <div class="row mb-5 justify-content-center" data-aos="fade-up">
            <div class="col-12 col-lg-auto">
                <div class="offers-filter-scroll">
                    <ul class="nav border-0 justify-content-center custom-search-tabs offers-filter-tabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all-routes" type="button">
                                <i class="fa-solid fa-border-all me-1"></i> All Routes
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#domestic-routes" type="button">
                                <i class="fa-solid fa-flag-usa me-1"></i> Domestic USA
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#international-routes" type="button">
                                <i class="fa-solid fa-earth-americas me-1"></i> International
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content">

            {{-- ALL --}}
            <div class="tab-pane fade show active" id="all-routes">
                @if($allRoutes->isNotEmpty())
                    <div class="row g-3">
                        @foreach($allRoutes as $r)
                            @include('frontend.flight-routes._card', ['r' => $r, 'resolveImg' => $resolveImg])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-plane-slash fa-2x mb-3"></i>
                        <p>No flight routes available at the moment.</p>
                    </div>
                @endif
            </div>

            {{-- DOMESTIC --}}
            <div class="tab-pane fade" id="domestic-routes">
                @if($domesticRoutes->isNotEmpty())
                    <div class="row g-3">
                        @foreach($domesticRoutes as $r)
                            @include('frontend.flight-routes._card', ['r' => $r, 'resolveImg' => $resolveImg])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-plane-slash fa-2x mb-3"></i>
                        <p>No domestic routes available.</p>
                    </div>
                @endif
            </div>

            {{-- INTERNATIONAL --}}
            <div class="tab-pane fade" id="international-routes">
                @if($internationalRoutes->isNotEmpty())
                    <div class="row g-3">
                        @foreach($internationalRoutes as $r)
                            @include('frontend.flight-routes._card', ['r' => $r, 'resolveImg' => $resolveImg])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-plane-slash fa-2x mb-3"></i>
                        <p>No international routes available.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- CTA Strip -->
        @if($allRoutes->isNotEmpty())
        <div class="text-center mt-5 pt-3" data-aos="fade-up">
            <p class="text-muted mb-3">Can't find your route? Our experts can help.</p>
            <a href="{{ route('contact') }}" class="btn btn-action px-5 py-2 rounded-pill">
                <i class="fa-solid fa-headset me-2"></i>Talk to a Flight Expert
            </a>
        </div>
        @endif
    </div>
</section>
@endsection
