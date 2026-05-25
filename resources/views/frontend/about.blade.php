@extends('layouts.frontend')

@section('content')
<!-- Page Header Banner -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">{{ $page->title ?? 'About Destination Fareways' }}</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
            {{ $page->subtitle ?? 'Over 15 years connecting travelers to unbeatable flight deals.' }}
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="150">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Stats counters row -->
@if(!empty($stats))
    <section class="py-5 bg-light border-bottom border-light">
        <div class="container">
            <div class="row g-4 justify-content-center text-center">
                @foreach($stats as $stat)
                    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="p-4 bg-white rounded-3 shadow-sm border border-light">
                            <span class="d-block display-5 fw-bold text-navy mb-1.5 font-monospace">{{ $stat['value'] }}</span>
                            <span class="text-muted small text-uppercase fw-semibold tracking-wider d-block">{{ $stat['label'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Core Story Section -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-9" data-aos="fade-up">
                <div class="prose-content lead-first-p" style="font-size: 1.05rem; line-height: 1.8;">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Grid -->
<section class="py-5 bg-softgray border-top border-bottom border-light">
    <div class="container py-3">
        <div class="row justify-content-center text-center mb-5" data-aos="fade-up">
            <div class="col-lg-6">
                <span class="badge bg-gold text-navy text-uppercase fw-bold px-3 py-1.5 fs-8 mb-2">Our Standard</span>
                <h2 class="h2 fw-bold text-navy">Why Discerning Travelers Choose Us</h2>
                <p class="text-muted">We combine cutting-edge flight comparison engines with personalized human support.</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Box 1 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up">
                <div class="card border border-light rounded-3 bg-white h-100 p-4 text-center">
                    <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-tags text-gold"></i>
                    </div>
                    <h4 class="h5 fw-bold text-navy mb-2">Best Price Guarantee</h4>
                    <p class="text-muted small mb-0">We match and beat any publicly advertised airline ticket quote.</p>
                </div>
            </div>
            <!-- Box 2 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card border border-light rounded-3 bg-white h-100 p-4 text-center">
                    <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-user-shield text-gold"></i>
                    </div>
                    <h4 class="h5 fw-bold text-navy mb-2">Secure & Trusted</h4>
                    <p class="text-muted small mb-0">High-grade server encryption safeguards all personal booking data.</p>
                </div>
            </div>
            <!-- Box 3 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card border border-light rounded-3 bg-white h-100 p-4 text-center">
                    <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-headset text-gold"></i>
                    </div>
                    <h4 class="h5 fw-bold text-navy mb-2">24/7 Expert Support</h4>
                    <p class="text-muted small mb-0">A real, dedicated travel consultant is always ready to answer your call.</p>
                </div>
            </div>
            <!-- Box 4 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card border border-light rounded-3 bg-white h-100 p-4 text-center">
                    <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-circle-dollar-to-slot text-gold"></i>
                    </div>
                    <h4 class="h5 fw-bold text-navy mb-2">No Hidden Fees</h4>
                    <p class="text-muted small mb-0">Our quotes list all service taxes upfront. What you see is what you pay.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call CTA Banner Section -->
<section class="py-5 bg-navy text-white text-center">
    <div class="container py-4" data-aos="fade-up">
        <h3 class="display-6 fw-bold mb-3">Looking for Exclusive Flight Discounts?</h3>
        <p class="lead text-muted-white mb-4 max-w-600 mx-auto" style="font-size: 1.05rem;">
            Our direct wholesale relationships allow us to issue unpublished, phone-only discounts on flights worldwide. Call us now to search private fares.
        </p>
        
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            @if(!empty($callSettings) && $callSettings->status)
                <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold px-4 py-2.5 text-navy fw-bold fs-7 shadow-lg">
                    <i class="fa-solid fa-phone me-2"></i>Call: {{ $callSettings->phone }}
                </a>
            @else
                <a href="tel:+18005550199" class="btn btn-gold px-4 py-2.5 text-navy fw-bold fs-7 shadow-lg">
                    <i class="fa-solid fa-phone me-2"></i>Call: +1 (800) 555-0199
                </a>
            @endif
        </div>
    </div>
</section>
@endsection
