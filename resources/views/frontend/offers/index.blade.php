@extends('layouts.frontend')

@section('content')
<!-- Page Banner Header -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">Flight Offers & Deals</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
            Exclusive hand-picked domestic and international flight promotions for unbeatable savings.
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Filter Section & Offers Grid -->
<section class="py-5 bg-white">
    <div class="container">
        <!-- Filter Tabs -->
        <div class="row mb-5 justify-content-center" data-aos="fade-up">
            <div class="col-lg-8">
                <ul class="nav nav-tabs border-0 justify-content-center custom-search-tabs">
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2.5 text-uppercase fw-semibold {{ !request('type') ? 'active' : '' }}" 
                           href="{{ route('offers.index') }}">
                            All Deals
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2.5 text-uppercase fw-semibold {{ request('type') === 'domestic' ? 'active' : '' }}" 
                           href="{{ route('offers.index', ['type' => 'domestic']) }}">
                            Domestic
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2.5 text-uppercase fw-semibold {{ request('type') === 'international' ? 'active' : '' }}" 
                           href="{{ route('offers.index', ['type' => 'international']) }}">
                            International
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2.5 text-uppercase fw-semibold {{ request('type') === 'business' ? 'active' : '' }}" 
                           href="{{ route('offers.index', ['type' => 'business']) }}">
                            Business Class
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Offers Grid -->
        @if($offers->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fa-solid fa-tags fs-1 text-muted mb-3"></i>
                <h3>No Active Offers Found</h3>
                <p class="text-muted">We are currently negotiating new unpublished rates. Check back soon or call our experts now.</p>
                <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}" class="btn btn-gold px-4 py-2 mt-2">
                    <i class="fa-solid fa-phone me-2"></i>Call for Unpublished Deals
                </a>
            </div>
        @else
            <div class="row g-4 mb-5">
                @foreach($offers as $offer)
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                        <div class="card card-flight h-100 shadow-sm border-light">
                            <!-- Image container with Zoom hover and discount tag -->
                            <div class="position-relative overflow-hidden" style="height: 200px;">
                                <img src="{{ $offer->image ?? 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80&auto=format' }}" 
                                     alt="{{ $offer->title }}" 
                                     class="w-100 h-100 object-fit-cover transition-transform" 
                                     loading="lazy">
                                @if(!empty($offer->discount_label))
                                    <span class="position-absolute top-3 end-3 badge bg-navy text-gold text-uppercase fw-bold px-3 py-2 fs-7 shadow">
                                        {{ $offer->discount_label }}
                                    </span>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column">
                                <span class="badge text-uppercase text-muted border border-light bg-light rounded-pill px-2.5 py-1 align-self-start fs-8 mb-2">
                                    <i class="fa-solid fa-plane me-1 text-navy"></i> {{ $offer->airline ?? 'Special Route' }}
                                </span>
                                
                                <h3 class="h5 fw-bold text-navy mb-2">{{ $offer->title }}</h3>
                                
                                @if(!empty($offer->from_city) && !empty($offer->to_city))
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="fw-semibold text-navy text-uppercase">{{ $offer->from_city }}</span>
                                        <i class="fa-solid fa-right-long mx-2 text-muted fs-8"></i>
                                        <span class="fw-semibold text-navy text-uppercase">{{ $offer->to_city }}</span>
                                    </div>
                                @endif

                                <p class="card-text text-muted small flex-grow-1 mb-4">
                                    {{ $offer->short_desc }}
                                </p>

                                <!-- Price Row -->
                                <div class="d-flex align-items-baseline gap-2 mb-3 border-top pt-3 border-light">
                                    @if(!empty($offer->original_price))
                                        <span class="text-decoration-line-through text-muted small">
                                            ${{ number_format($offer->original_price, 0) }}
                                        </span>
                                    @endif
                                    <span class="price fs-4 fw-bold">
                                        ${{ number_format($offer->offer_price, 0) }}
                                    </span>
                                    <span class="text-muted small">/ one-way</span>
                                </div>

                                <!-- Valid Until Row -->
                                @if(!empty($offer->valid_until))
                                    <div class="text-muted fs-8 mb-3 d-flex align-items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-gold"></i>
                                        <span>Valid until: <strong>{{ $offer->valid_until->format('M d, Y') }}</strong></span>
                                    </div>
                                @endif

                                <!-- View Deal CTA Button -->
                                <a href="{{ route('offers.show', $offer->slug) }}" class="btn btn-outline-navy w-100 py-2.5 mt-auto">
                                    View Deal <i class="fa-solid fa-arrow-right ms-2 fs-9"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center" data-aos="fade-up">
                {{ $offers->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>

<!-- Mini Call CTA Section -->
<section class="py-5 bg-softgray border-top border-light">
    <div class="container py-3">
        <div class="row align-items-center justify-content-between g-4">
            <div class="col-lg-8" data-aos="fade-up">
                <h3 class="h2 fw-bold text-navy mb-2">Want to save up to 40% more?</h3>
                <p class="text-muted mb-0">
                    Our direct wholesale contracts allow us to offer unpublished rates over the phone. Let our concierges secure your itinerary.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-up" data-aos-delay="100">
                <div class="d-flex flex-wrap gap-3 justify-content-lg-end">
                    <a href="tel:{{ $callSettings->phone ?? '+18005550199' }} " class="btn btn-navy px-4 py-2.5 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-phone text-gold"></i> Call Reservation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
