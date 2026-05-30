@extends('layouts.frontend')

@section('content')
<!-- SECTION 1 & 2: Hero Section & Search Form -->
<section class="hero-section text-white p-0 position-relative">
    <div class="hero-overlay"></div>
    <div class="container hero-content py-5">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-12 text-center text-lg-start mb-5" data-aos="fade-up">
                <!-- Headings -->
                <h1 class="display-font text-white mb-3" style="font-size: clamp(2.5rem, 5vw, 3.75rem); letter-spacing: -0.5px;">
                    Find the Cheapest Flights <br class="d-none d-lg-inline"> Across USA & Beyond
                </h1>
                <p class="lead text-white-50 mb-4" style="font-family: 'DM Sans', sans-serif; font-size: 1.15rem; max-width: 680px;">
                    Compare 500+ airlines. Unbeatable deals on domestic & international flights. Speak to our reservation experts for phone-only private fares.
                </p>

                <!-- Badges -->
                <div class="hero-badges-row justify-content-center justify-content-lg-start mb-5">
                    <span class="hero-badge-pill"><i class="fa-solid fa-plane-departure text-gold"></i> 10M+ Travelers</span>
                    <span class="hero-badge-pill"><i class="fa-solid fa-star text-gold"></i> 4.9/5 Rated</span>
                    <span class="hero-badge-pill"><i class="fa-solid fa-shield-halved text-gold"></i> Secure Booking</span>
                    <span class="hero-badge-pill"><i class="fa-solid fa-hand-holding-dollar text-gold"></i> Best Price Guarantee</span>
                </div>

                <!-- Flight Search Form Component -->
                <div class="mt-4 text-start">
                    @include('partials.frontend.flight-search-form')
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 3: Popular Domestic Routes -->
<section class="bg-white border-bottom">
    <div class="container">
        <div class="section-title-wrapper left mb-4" data-aos="fade-up">
            <h2 class="display-font text-navy">Popular Domestic Routes</h2>
            <p class="text-muted mb-0">Search trending, non-stop domestic routes across the USA with top carriers.</p>
        </div>

        <!-- 4-col grid / Horizontal scroll mobile -->
        <div class="row g-3 d-flex flex-nowrap overflow-auto pb-3 d-md-grid d-md-flex d-md-nowrap-0 scrollbar-thin" style="scrollbar-width: thin;" data-aos="fade-up">
            @php
                $routes = [
                    ['from' => 'JFK', 'to' => 'LAX', 'from_city' => 'New York', 'to_city' => 'Los Angeles', 'price' => 129],
                    ['from' => 'ORD', 'to' => 'MIA', 'from_city' => 'Chicago', 'to_city' => 'Miami', 'price' => 89],
                    ['from' => 'DFW', 'to' => 'LAS', 'from_city' => 'Dallas', 'to_city' => 'Las Vegas', 'price' => 79],
                    ['from' => 'MIA', 'to' => 'JFK', 'from_city' => 'Miami', 'to_city' => 'New York', 'price' => 99],
                    ['from' => 'LAX', 'to' => 'SEA', 'from_city' => 'Los Angeles', 'to_city' => 'Seattle', 'price' => 69],
                    ['from' => 'IAH', 'to' => 'MCO', 'from_city' => 'Houston', 'to_city' => 'Orlando', 'price' => 85],
                    ['from' => 'SFO', 'to' => 'ORD', 'from_city' => 'San Francisco', 'to_city' => 'Chicago', 'price' => 109],
                    ['from' => 'BOS', 'to' => 'DCA', 'from_city' => 'Boston', 'to_city' => 'Washington DC', 'price' => 59],
                ];
            @endphp

            @foreach($routes as $route)
                <div class="col-10 col-sm-6 col-md-4 col-lg-3 flex-shrink-0 flex-md-shrink-1">
                    <div class="popular-route-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-navy fs-5 font-monospace">{{ $route['from'] }}</span>
                            <i class="fa-solid fa-right-long text-secondary"></i>
                            <span class="fw-bold text-navy fs-5 font-monospace">{{ $route['to'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted text-truncate" style="max-width: 140px;">
                                {{ $route['from_city'] }} to {{ $route['to_city'] }}
                            </div>
                            <span class="price fw-semibold" style="font-size: 0.95rem;">from ${{ $route['price'] }}</span>
                        </div>
                        <div class="border-top mt-3 pt-3">
                            <a href="{{ route('flights.results') }}?from={{ $route['from'] }}&to={{ $route['to'] }}" class="small text-navy fw-bold d-flex align-items-center justify-content-between text-decoration-none">
                                Search Flights <i class="fa-solid fa-arrow-right-long text-gold" style="font-size: 0.8rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- SECTION 4: Trending Flight Deals -->
<section class="section-alt border-bottom">
    <div class="container">
        <div class="section-title-wrapper center" data-aos="fade-up">
            <h2 class="display-font text-navy">Trending Flight Deals</h2>
            <p class="text-muted">Handpicked promotional fares fetched directly from our live inventories.</p>
        </div>

        <div class="row g-4" data-aos="fade-up">
            @forelse($featuredOffers as $offer)
                <div class="col-lg-4 col-md-6">
                    <div class="card-flight position-relative h-100">
                        @if($offer->discount_label)
                            <div class="discount-badge shadow-sm">{{ $offer->discount_label }}</div>
                        @endif
                        
                        <!-- Image -->
                        <div style="height: 200px; overflow: hidden;">
                            <img src="{{ !empty($offer->image) ? (\Illuminate\Support\Str::startsWith($offer->image, ['http://', 'https://']) ? $offer->image : asset('storage/' . ltrim($offer->image, '/'))) : 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=600&q=80&auto=format' }}" class="w-100 h-100 object-fit-cover" alt="{{ $offer->title }}">
                        </div>

                        <!-- Card Body -->
                        <div class="p-4">
                            <div class="small text-muted mb-1 text-uppercase tracking-wider fw-semibold" style="font-size: 0.75rem;">
                                {{ $offer->airline }}
                            </div>
                            <h4 class="h5 mb-2 font-family-base text-navy fw-bold" style="font-family: 'DM Sans', sans-serif;">
                                {{ $offer->from_city }} to {{ $offer->to_city }}
                            </h4>
                            <p class="text-secondary small mb-3.5">{{ Str::limit(strip_tags($offer->short_desc), 110) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                <div>
                                    @if($offer->original_price)
                                        <span class="text-muted text-decoration-line-through small me-1.5">${{ number_format($offer->original_price) }}</span>
                                    @endif
                                    <span class="price fs-4 fw-bold font-monospace">${{ number_format($offer->offer_price) }}</span>
                                </div>
                                <a href="{{ route('offers.show', $offer->slug) }}" class="btn btn-outline-navy btn-sm">
                                    View Deal <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Static Deals -->
                @php
                    $fallbackOffers = [
                        ['from' => 'New York', 'to' => 'London', 'price' => 399, 'orig' => 599, 'airline' => 'British Airways', 'tag' => '33% OFF', 'img' => 'https://images.unsplash.com/photo-1513635269975-59663e0ca1ad?w=600&q=80&auto=format'],
                        ['from' => 'Los Angeles', 'to' => 'Tokyo', 'price' => 699, 'orig' => 999, 'airline' => 'Japan Airlines', 'tag' => '30% OFF', 'img' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=600&q=80&auto=format'],
                        ['from' => 'Miami', 'to' => 'Paris', 'price' => 459, 'orig' => 749, 'airline' => 'Air France', 'tag' => '40% OFF', 'img' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=600&q=80&auto=format'],
                    ];
                @endphp
                @foreach($fallbackOffers as $fOff)
                    <div class="col-lg-4 col-md-6">
                        <div class="card-flight position-relative h-100">
                            <div class="discount-badge shadow-sm">{{ $fOff['tag'] }}</div>
                            <div style="height: 200px; overflow: hidden;">
                                <img src="{{ $fOff['img'] }}" class="w-100 h-100 object-fit-cover" alt="Flight Deal">
                            </div>
                            <div class="p-4">
                                <div class="small text-muted mb-1 text-uppercase tracking-wider fw-semibold" style="font-size: 0.75rem;">
                                    {{ $fOff['airline'] }}
                                </div>
                                <h4 class="h5 mb-2 font-family-base text-navy fw-bold" style="font-family: 'DM Sans', sans-serif;">
                                    {{ $fOff['from'] }} to {{ $fOff['to'] }}
                                </h4>
                                <p class="text-secondary small mb-3.5">Exclusive handpicked airline fares with flexible booking rules. Book now and save.</p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                    <div>
                                        <span class="text-muted text-decoration-line-through small me-1.5">${{ $fOff['orig'] }}</span>
                                        <span class="price fs-4 fw-bold font-monospace">${{ $fOff['price'] }}</span>
                                    </div>
                                    <a href="{{ route('offers.index') }}" class="btn btn-outline-navy btn-sm">
                                        View Deal <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('offers.index') }}" class="text-navy fw-bold text-decoration-none border-bottom border-navy pb-1">
                View All Deals <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- SECTION 5: Top Destinations from USA -->
<section class="bg-white border-bottom">
    <div class="container">
        <div class="section-title-wrapper center" data-aos="fade-up">
            <h2 class="display-font text-navy">Top Destinations from USA</h2>
            <p class="text-muted">Explore hot getaways with average starting flight fares.</p>
        </div>

        <div class="row g-4" data-aos="fade-up">
            @forelse($featuredDestinations as $dest)
                <div class="col-lg-4 col-md-6">
                    <div class="dest-card" onclick="window.location.href='{{ route('destinations.show', $dest->slug) }}'">
                        @if($dest->featured_image)
                            <img src="{{ asset('storage/' . $dest->featured_image) }}" alt="{{ $dest->name }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=800&q=80&auto=format" alt="Destination Photo">
                        @endif
                        <div class="overlay">
                            <h3 class="dest-name">{{ $dest->name }}</h3>
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <span class="text-white-50 font-sans" style="font-family: 'DM Sans', sans-serif; font-size: 0.85rem;">{{ $dest->airport_code }} Airport</span>
                                <span class="price font-monospace text-gold fw-bold dest-price">from ${{ number_format($dest->starting_price) }}</span>
                            </div>
                            <span class="explore-btn">Explore <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Destinations -->
                @php
                    $fallbackDests = [
                        ['name' => 'New York City', 'code' => 'JFK', 'price' => 199, 'img' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=600&q=80&auto=format'],
                        ['name' => 'Los Angeles', 'code' => 'LAX', 'price' => 249, 'img' => 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=600&q=80&auto=format'],
                        ['name' => 'Miami', 'code' => 'MIA', 'price' => 189, 'img' => 'https://images.unsplash.com/photo-1535498730771-e735b998cd64?w=600&q=80&auto=format'],
                    ];
                @endphp
                @foreach($fallbackDests as $fD)
                    <div class="col-lg-4 col-md-6">
                        <div class="dest-card" onclick="window.location.href='{{ route('destinations.index') }}'">
                            <img src="{{ $fD['img'] }}" alt="{{ $fD['name'] }}">
                            <div class="overlay">
                                <h3 class="dest-name">{{ $fD['name'] }}</h3>
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <span class="text-white-50 font-sans" style="font-family: 'DM Sans', sans-serif; font-size: 0.85rem;">{{ $fD['code'] }} Airport</span>
                                    <span class="price font-monospace text-gold fw-bold dest-price">from ${{ $fD['price'] }}</span>
                                </div>
                                <span class="explore-btn">Explore <i class="fa-solid fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

<!-- SECTION 6: Why Travelers Choose Us -->
<section class="section-alt border-bottom">
    <div class="container">
        <div class="section-title-wrapper center" data-aos="fade-up">
            <h2 class="display-font text-navy">Why Travelers Choose Us</h2>
            <p class="text-muted">Uncompromising premium support and elite fare inventory access.</p>
        </div>

        <div class="row g-4" data-aos="fade-up">
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-tags"></i></div>
                    <h4>Best Price Guarantee</h4>
                    <p>Access contracted and phone-exclusive airline tickets up to 30% cheaper.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-user-shield"></i></div>
                    <h4>Secure & Trusted</h4>
                    <p>Encrypted payment portals protecting your financial information.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-headset"></i></div>
                    <h4>24/7 Support</h4>
                    <p>Talk to our dedicated travel concierges directly. Zero wait times.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-ban"></i></div>
                    <h4>No Hidden Fees</h4>
                    <p>Transparent flat pricing with clear fare and taxes breakdowns.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 7: Call CTA Banner -->
@if(!empty($callSettings) && $callSettings->status)
<section style="background-color: #07111F;" class="text-white py-5">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-7 text-center text-lg-start mb-4 mb-lg-0" data-aos="fade-up">
                <h3 class="display-font text-white mb-2" style="font-size: 2rem;">{{ $callSettings->cta_text ?? 'Need Help Booking? Our Experts Are Ready.' }}</h3>
                <p class="lead mb-0 text-white-50" style="font-family: 'DM Sans', sans-serif; font-size: 1rem;">
                    {{ $callSettings->cta_subtext ?? 'Call us for exclusive unpublished deals.' }}
                </p>
            </div>
            <div class="col-lg-5 text-center text-lg-end" data-aos="fade-up">
                <h2 class="price text-gold fw-bold mb-3.5 font-monospace" style="font-size: 2.25rem;">{{ $callSettings->phone }}</h2>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-end">
                    <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold py-2.5 px-4 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-phone"></i> Call Now
                    </a>
                    @if($callSettings->toggle_whatsapp && !empty($callSettings->whatsapp))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $callSettings->whatsapp) }}" class="btn btn-navy py-2.5 px-4 border border-secondary d-flex align-items-center gap-2" target="_blank" style="background-color: #25D366; border-color: #25D366 !important;">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- SECTION 8: Travel Tips & Guides (Blogs) -->
<section class="bg-white border-bottom">
    <div class="container">
        <div class="section-title-wrapper center" data-aos="fade-up">
            <h2 class="display-font text-navy">Travel Tips & Guides</h2>
            <p class="text-muted">Expert flight advice and premium guides compiled by our editors.</p>
        </div>

        <div class="row g-4" data-aos="fade-up">
            @forelse($featuredBlogs as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="card-flight h-100 d-flex flex-column">
                        <div style="height: 200px; overflow: hidden;">
                            @if($blog->featured_image)
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $blog->title }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=600&q=80&auto=format" class="w-100 h-100 object-fit-cover" alt="Blog Post">
                            @endif
                        </div>
                        <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <span class="badge border border-secondary text-secondary mb-2 bg-transparent fw-semibold" style="font-family: 'DM Sans', sans-serif; font-size: 0.75rem;">
                                    {{ $blog->category }}
                                </span>
                                <h4 class="h5 text-navy fw-bold mb-2 font-family-base" style="font-family: 'DM Sans', sans-serif; line-height: 1.4;">
                                    {{ Str::limit($blog->title, 60) }}
                                </h4>
                                <p class="text-muted small mb-4">{{ Str::limit($blog->excerpt, 120) }}</p>
                            </div>
                            
                            <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                <span class="small text-muted font-sans" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-clock text-gold me-1"></i> {{ $blog->read_time ?? '5 mins' }}
                                </span>
                                <a href="{{ route('blog.show', $blog->slug) }}" class="small text-navy fw-bold text-decoration-none">
                                    Read More <i class="fa-solid fa-chevron-right text-gold ms-1" style="font-size: 0.75rem;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Blogs -->
                @php
                    $fallbackBlogs = [
                        ['title' => 'Top 10 Essential Travel Tips for Premium Cabin Flying', 'cat' => 'Travel Guides', 'read' => '5 mins', 'img' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=600&q=80&auto=format'],
                        ['title' => 'Exploring the Scenic Hidden Gems of Miami South Beach', 'cat' => 'Destinations', 'read' => '8 mins', 'img' => 'https://images.unsplash.com/photo-1535498730771-e735b998cd64?w=600&q=80&auto=format'],
                        ['title' => 'The Evolution of Modern Business Class Cabin Configurations', 'cat' => 'Airlines', 'read' => '12 mins', 'img' => 'https://images.unsplash.com/photo-1483450388369-9ed95738483c?w=600&q=80&auto=format'],
                    ];
                @endphp
                @foreach($fallbackBlogs as $fB)
                    <div class="col-lg-4 col-md-6">
                        <div class="card-flight h-100 d-flex flex-column">
                            <div style="height: 200px; overflow: hidden;">
                                <img src="{{ $fB['img'] }}" class="w-100 h-100 object-fit-cover" alt="Blog Post">
                            </div>
                            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="badge border border-secondary text-secondary mb-2 bg-transparent fw-semibold" style="font-family: 'DM Sans', sans-serif; font-size: 0.75rem;">
                                        {{ $fB['cat'] }}
                                    </span>
                                    <h4 class="h5 text-navy fw-bold mb-2 font-family-base" style="font-family: 'DM Sans', sans-serif; line-height: 1.4;">
                                        {{ $fB['title'] }}
                                    </h4>
                                    <p class="text-muted small mb-4">Read expert tips, recommendations, and reviews from luxury travel analysts.</p>
                                </div>
                                <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                    <span class="small text-muted font-sans" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-clock text-gold me-1"></i> {{ $fB['read'] }}
                                    </span>
                                    <a href="{{ route('blog.index') }}" class="small text-navy fw-bold text-decoration-none">
                                        Read More <i class="fa-solid fa-chevron-right text-gold ms-1" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('blog.index') }}" class="text-navy fw-bold text-decoration-none border-bottom border-navy pb-1">
                View All Posts <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- SECTION 9: What Our Travelers Say (Testimonials) -->
<section class="section-alt border-bottom overflow-hidden">
    <div class="container">
        <div class="section-title-wrapper center" data-aos="fade-up">
            <h2 class="display-font text-navy">What Our Travelers Say</h2>
            <p class="text-muted">Unbiased customer reviews validating our ticket booking efficiency.</p>
        </div>

        <!-- Swiper testimonial slider -->
        <div class="swiper swiper-testimonials pb-5" data-aos="fade-up">
            <div class="swiper-wrapper">
                @forelse($testimonials as $t)
                    <div class="swiper-slide h-auto">
                        <div class="card-flight p-4 h-100 bg-white d-flex flex-column justify-content-between" style="position: relative;">
                            <!-- Large quote mark background -->
                            <div style="position: absolute; top: 15px; right: 25px; font-family: Georgia, serif; font-size: 5rem; color: #f1f5f9; line-height: 1; pointer-events: none; z-index: 1;">“</div>
                            
                            <div style="position: relative; z-index: 2;">
                                <!-- Star Rating -->
                                <div class="mb-3 text-gold">
                                    @for($i = 0; $i < $t->rating; $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                    @for($i = $t->rating; $i < 5; $i++)
                                        <i class="fa-regular fa-star"></i>
                                    @endfor
                                </div>
                                <p class="fst-italic text-navy mb-4" style="font-size: 0.95rem; line-height: 1.7;">
                                    "{{ $t->review }}"
                                </p>
                            </div>
                            
                            <div class="d-flex align-items-center gap-3 border-top pt-3 mt-auto">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-navy" style="width: 44px; height: 44px; font-size: 0.9rem;">
                                    {{ substr($t->name, 0, 2) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 text-navy fw-bold" style="font-family: 'DM Sans', sans-serif;">{{ $t->name }}</h6>
                                    <div class="text-muted small" style="font-size: 0.75rem;">
                                        {{ $t->location }} @if($t->flight_route) | Route: {{ $t->flight_route }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback Testimonials -->
                    @php
                        $fallbackTests = [
                            ['name' => 'Jonathan Vance', 'loc' => 'Boston, MA', 'route' => 'BOS to LAX', 'rev' => 'Destination Fareways saved me over $1,200 on my premium business class flights to Los Angeles. The seat booking process was seamless, and the flight concierges helped me secure a premium window seat.'],
                            ['name' => 'Emily Sterling', 'loc' => 'Chicago, IL', 'route' => 'ORD to MIA', 'rev' => 'Excellent service from start to finish! The client care team answered my phone call immediately and helped resolve a scheduling change on short notice. Highly recommend their exclusive flight deals.'],
                            ['name' => 'Marcus Aurelius', 'loc' => 'Seattle, WA', 'route' => 'SEA to LAS', 'rev' => 'Incredible round-trip prices. I booked a first class escape to Las Vegas, and the flights were fantastic. The luxury lounge access alone was worth the promotional rate.'],
                        ];
                    @endphp
                    @foreach($fallbackTests as $fT)
                        <div class="swiper-slide h-auto">
                            <div class="card-flight p-4 h-100 bg-white d-flex flex-column justify-content-between" style="position: relative;">
                                <div style="position: absolute; top: 15px; right: 25px; font-family: Georgia, serif; font-size: 5rem; color: #f1f5f9; line-height: 1; pointer-events: none; z-index: 1;">“</div>
                                <div style="position: relative; z-index: 2;">
                                    <div class="mb-3 text-gold">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    </div>
                                    <p class="fst-italic text-navy mb-4" style="font-size: 0.95rem; line-height: 1.7;">
                                        "{{ $fT['rev'] }}"
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-3 border-top pt-3 mt-auto">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-navy" style="width: 44px; height: 44px; font-size: 0.9rem;">
                                        {{ substr($fT['name'], 0, 2) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-navy fw-bold" style="font-family: 'DM Sans', sans-serif;">{{ $fT['name'] }}</h6>
                                        <div class="text-muted small" style="font-size: 0.75rem;">
                                            {{ $fT['loc'] }} | Route: {{ $fT['route'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
            
            <!-- Add Pagination & Nav -->
            <div class="swiper-pagination mt-4"></div>
        </div>
    </div>
</section>

<!-- SECTION 10: FAQ Accordion -->
<section class="bg-white border-bottom">
    <div class="container">
        <div class="row g-5">
            <!-- Left Col Title -->
            <div class="col-lg-4" data-aos="fade-up">
                <div class="section-title-wrapper left mb-4">
                    <h2 class="display-font text-navy">Frequently Asked Questions</h2>
                    <p class="text-muted mb-4">Everything you need to know about booking, flight modifications, and phone-only ticket promotions.</p>
                    <a href="{{ route('contact') }}" class="btn btn-navy">
                        Contact Us <i class="fa-solid fa-arrow-right-long ms-2"></i>
                    </a>
                </div>
            </div>

            <!-- Right Col Accordion -->
            <div class="col-lg-8" data-aos="fade-up">
                <div class="accordion custom-accordion" id="faqAccordion">
                    @forelse($homeFaqs as $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faq-collapse-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Fallback Static FAQs -->
                        @php
                            $fallbackFaqs = [
                                ['id' => 991, 'q' => 'How can I find the cheapest flight deals on Destination Fareways?', 'a' => 'To secure the best rates, search in advance, stay flexible with dates, select off-peak travel times, and call our experts directly. Many premium and business class deals are phone-exclusive and not published online.'],
                                ['id' => 992, 'q' => 'Are my tickets eligible for refunds or cancellations?', 'a' => 'Refund and cancellation eligibility depends on the specific airline fare rules purchased. Economy flight tickets are often non-refundable but can be exchanged for flight credits, while Business and First class reservations typically support flexible cancellation options.'],
                                ['id' => 993, 'q' => 'What is the maximum baggage allowance permitted?', 'a' => 'Baggage policies vary per airline, route, and cabin class. Business and First class flights usually include two checked bags up to 70 lbs (32kg) each, while Economy flights usually support one checked bag up to 50 lbs (23kg). Check your specific ticket details for confirmation.'],
                            ];
                        @endphp
                        @foreach($fallbackFaqs as $fFaq)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapse-{{ $fFaq['id'] }}">
                                        {{ $fFaq['q'] }}
                                    </button>
                                </h2>
                                <div id="faq-collapse-{{ $fFaq['id'] }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ $fFaq['a'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 11: Get Exclusive Flight Deals (Newsletter) -->
<section style="background-color: #07111F;" class="text-white py-5 border-bottom border-secondary text-center">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10" data-aos="fade-up">
                <h2 class="display-font text-white mb-2" style="font-size: 2.25rem;">Get Exclusive Flight Deals</h2>
                <p class="lead text-white-50 mb-4" style="font-family: 'DM Sans', sans-serif; font-size: 1.05rem;">
                    Sign up for our luxury newsletter and receive private promo fare clearance notifications straight to your inbox.
                </p>

                <!-- Subscription Input -->
                <form id="home-newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-2 max-width-600 mx-auto">
                    @csrf
                    <input type="email" name="email" class="form-control py-3 px-4 bg-dark border-secondary text-white text-center text-sm-start rounded-pill" placeholder="Enter your email address" required style="font-size: 0.95rem; border-color: rgba(255,255,255,0.2) !important;">
                    <button type="submit" class="btn btn-gold text-uppercase font-monospace py-2 px-3 tracking-wide text-navy mx-auto mx-sm-0" style="font-weight: 600;">Subscribe <i class="fa-solid fa-arrow-right ms-1"></i></button>
                </form>
                <div class="text-white-50 small mt-2" style="font-size: 0.75rem;">No spam. Unsubscribe anytime.</div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 12: SEO Content Block -->
@if(!empty($seoData) && !empty($seoData->seo_content_block))
<section class="bg-white py-5">
    <div class="container py-4">
        <div class="mx-auto prose-content" style="max-width: 860px;" data-aos="fade-up">
            {!! $seoData->seo_content_block !!}
        </div>
    </div>
</section>
@endif

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Custom form handler for homepage newsletter to integrate smoothly with standard toast notifications
    $('#home-newsletter-form').on('submit', function (e) {
        e.preventDefault();
        
        var form = $(this);
        var emailInput = form.find('input[type="email"]');
        var submitBtn = form.find('button');
        var email = emailInput.val();
        
        if (!email) return;

        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Processing');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                email: email
            },
            success: function (response) {
                submitBtn.prop('disabled', false).text('Subscribed');
                emailInput.val('');
                showFormToast('success', response.message || 'Successfully subscribed!');
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html('Subscribe <i class="fa-solid fa-arrow-right ms-1"></i>');
                var errMsg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.email) {
                    errMsg = xhr.responseJSON.errors.email[0];
                }
                showFormToast('danger', errMsg);
            }
        });
    });

    function showFormToast(type, message) {
        var toastContainer = $('.toast-container');
        var icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
        var bgClass = type === 'success' ? 'bg-success' : 'bg-danger';

        var toastHtml = `
            <div class="toast align-items-center text-white ${bgClass} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fa-solid ${icon} fs-5 me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        var toastElement = $(toastHtml).appendTo(toastContainer);
        var bsToast = new bootstrap.Toast(toastElement[0], { delay: 6000 });
        bsToast.show();

        toastElement.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }
});
</script>
@endsection
