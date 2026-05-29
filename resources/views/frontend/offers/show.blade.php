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
<!-- Detail Hero Header -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <span class="badge bg-gold text-navy text-uppercase fw-bold px-3 py-1.5 fs-8 mb-3" data-aos="fade-up">
            Exclusive Deal
        </span>
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up" data-aos-delay="100">{{ $offer->title }}</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="150">
            {{ $offer->subtitle }}
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Content Body -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-5">
            <!-- Left Side: Banner + Details -->
            <div class="col-lg-8" data-aos="fade-up">
                <!-- Main Image -->
                <div class="position-relative rounded-4 overflow-hidden mb-5 shadow-sm border border-light" style="max-height: 420px;">
                    <img src="{{ $offerImageUrl($offer->banner_image ?? $offer->image, 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200&q=80&auto=format') }}" 
                         alt="{{ $offer->title }}" 
                         class="w-100 h-100 object-fit-cover">
                    @if(!empty($offer->discount_label))
                        <span class="position-absolute top-4 end-4 badge bg-gold text-navy text-uppercase fw-bold px-3 py-2 fs-7 shadow-lg">
                            {{ $offer->discount_label }}
                        </span>
                    @endif
                </div>

                <!-- Quick Route Overview Card -->
                <div class="card card-flight border-light shadow-sm mb-5 p-4 bg-softgray">
                    <div class="row align-items-center g-3 text-center text-md-start">
                        <div class="col-md-5">
                            <span class="text-muted d-block small text-uppercase">Departing From</span>
                            <span class="fs-4 fw-bold text-navy text-uppercase">{{ $offer->from_city ?? 'Any USA Airport' }}</span>
                        </div>
                        <div class="col-md-2 text-center">
                            <i class="fa-solid fa-plane-departure fs-3 text-gold"></i>
                        </div>
                        <div class="col-md-5 text-md-end">
                            <span class="text-muted d-block small text-uppercase">Destination City</span>
                            <span class="fs-4 fw-bold text-navy text-uppercase">{{ $offer->to_city ?? 'Featured Destination' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rich Description Editor HTML -->
                <div class="prose-content mb-5">
                    {!! $offer->description !!}
                </div>

                <!-- Promo Coupon Widget -->
                @if(!empty($offer->promo_code))
                    <div class="card border border-warning rounded-3 mb-5 p-4 text-center bg-light">
                        <span class="text-uppercase small text-muted d-block mb-2">Apply Promo Code at Checkout</span>
                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                            <div class="border border-dashed border-warning rounded px-4 py-2 bg-white fw-bold font-monospace fs-4 text-navy shadow-sm">
                                <span id="promo-code">{{ $offer->promo_code }}</span>
                            </div>
                            <button class="btn btn-gold px-4 py-2.5 d-flex align-items-center gap-2" onclick="copyPromoCode()">
                                <i class="fa-regular fa-copy"></i> <span id="copy-btn-text">Copy Code</span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Deal Meta specs list -->
                <div class="row g-4 mb-5">
                    <div class="col-6 col-md-3">
                        <div class="p-3 border rounded text-center h-100">
                            <i class="fa-solid fa-plane-up text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted fs-8">Carrier Airline</span>
                            <span class="fw-semibold text-navy small">{{ $offer->airline ?? 'Contracted Airlines' }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border rounded text-center h-100">
                            <i class="fa-solid fa-suitcase text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted fs-8">Baggage Limit</span>
                            <span class="fw-semibold text-navy small">Included Options</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border rounded text-center h-100">
                            <i class="fa-solid fa-calendar-days text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted fs-8">Booking Starts</span>
                            <span class="fw-semibold text-navy small">{{ $offer->valid_from ? $offer->valid_from->format('M d, Y') : 'Immediate' }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 border rounded text-center h-100">
                            <i class="fa-solid fa-hourglass-end text-gold fs-4 mb-2"></i>
                            <span class="d-block text-muted fs-8">Offer Valid Till</span>
                            <span class="fw-semibold text-navy small text-danger">{{ $offer->valid_until ? $offer->valid_until->format('M d, Y') : 'Limited Period' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sticky Sidebar: Price card & Booking enquiry -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <!-- Price Card -->
                    <div class="card card-flight border-light shadow-sm mb-4">
                        <div class="card-body p-4">
                            <span class="badge bg-gold text-navy text-uppercase fw-bold rounded-pill px-2.5 py-1 fs-8 mb-3">
                                Guaranteed Lowest Rate
                            </span>
                            
                            <div class="d-flex align-items-baseline gap-2 mb-2">
                                @if(!empty($offer->original_price))
                                    <span class="text-decoration-line-through text-muted font-monospace">
                                        ${{ number_format($offer->original_price, 0) }}
                                    </span>
                                @endif
                                <span class="price fs-1 fw-bold font-monospace">
                                    ${{ number_format($offer->offer_price, 0) }}
                                </span>
                                <span class="text-muted small">/ one-way</span>
                            </div>
                            <p class="text-muted small border-bottom pb-3 mb-4">
                                Taxes and basic security fees included. Call to unlock private bulk fares.
                            </p>

                            <!-- Mini Enquiry Form -->
                            <form action="{{ route('booking.submit') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="from_airport" value="{{ $offer->from_city ?? 'Any USA Airport' }}">
                                <input type="hidden" name="to_airport" value="{{ $offer->to_city ?? '' }}">
                                <input type="hidden" name="cabin_class" value="{{ (Str::contains(strtolower($offer->title), 'business') || Str::contains(strtolower($offer->short_desc), 'business')) ? 'business' : 'economy' }}">
                                <input type="hidden" name="trip_type" value="one_way">
                                <input type="hidden" name="adults" value="1">

                                <h4 class="h6 fw-bold text-navy mb-3 text-uppercase tracking-wider">Quick Booking Enquiry</h4>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Full Name</label>
                                    <input type="text" name="name" class="form-control py-2.5" placeholder="John Doe" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Email Address</label>
                                    <input type="email" name="email" class="form-control py-2.5" placeholder="john@example.com" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control py-2.5" placeholder="+1 (555) 000-0000" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted fs-8 mb-1">Preferred Depart Date</label>
                                    <input type="date" name="departure_date" class="form-control py-2.5" required min="{{ date('Y-m-d') }}">
                                </div>

                                <button type="submit" class="btn btn-gold w-100 py-3 text-navy fw-bold rounded-3 mb-2.5 transition-lift">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Enquiry
                                </button>
                            </form>

                            <!-- Call Option Button -->
                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}" class="btn btn-outline-navy w-100 py-3 fw-semibold font-monospace transition-lift">
                                    <i class="fa-solid fa-phone text-gold me-2"></i> Call Now: {{ $callSettings->phone }}
                                </a>
                            @else
                                <a href="tel:+18005550199" class="btn btn-outline-navy w-100 py-3 fw-semibold font-monospace transition-lift">
                                    <i class="fa-solid fa-phone text-gold me-2"></i> Call: +1 (800) 555-0199
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Offers Section -->
        @if($relatedOffers->isNotEmpty())
            <div class="row border-top pt-5 border-light g-4 mt-5">
                <div class="col-12" data-aos="fade-up">
                    <h3 class="h3 fw-bold text-navy mb-4">Related Flight Offers</h3>
                </div>
                
                @foreach($relatedOffers as $related)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card card-flight h-100 shadow-sm">
                            <div class="position-relative overflow-hidden" style="height: 160px;">
                                <img src="{{ $offerImageUrl($related->image, 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80&auto=format') }}" 
                                     alt="{{ $related->title }}" 
                                     class="w-100 h-100 object-fit-cover transition-transform" 
                                     loading="lazy">
                                @if(!empty($related->discount_label))
                                    <span class="position-absolute top-2.5 end-2.5 badge bg-navy text-gold text-uppercase fw-bold px-2.5 py-1.5 fs-8 shadow">
                                        {{ $related->discount_label }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                <span class="badge text-uppercase text-muted border border-light bg-light rounded-pill px-2.5 py-0.5 align-self-start fs-8 mb-2">
                                    {{ $related->airline ?? 'Featured Carrier' }}
                                </span>
                                <h4 class="h6 fw-bold text-navy mb-2 text-truncate">{{ $related->title }}</h4>
                                <p class="card-text text-muted small flex-grow-1 mb-3 text-truncate-2">
                                    {{ $related->short_desc }}
                                </p>
                                <div class="d-flex align-items-baseline gap-1.5 mb-3 border-top pt-2.5 border-light mt-auto">
                                    <span class="price font-monospace fw-bold fs-5">${{ number_format($related->offer_price, 0) }}</span>
                                    <span class="text-muted small">/ one-way</span>
                                </div>
                                <a href="{{ route('offers.show', $related->slug) }}" class="btn btn-outline-navy w-100 py-2 small">
                                    View Deal <i class="fa-solid fa-arrow-right ms-2 fs-9"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@section('scripts')
<script>
    function copyPromoCode() {
        var text = document.getElementById("promo-code").innerText;
        navigator.clipboard.writeText(text).then(function() {
            var btnText = document.getElementById("copy-btn-text");
            btnText.innerText = "Copied!";
            setTimeout(function() {
                btnText.innerText = "Copy Code";
            }, 3000);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
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
@endsection
