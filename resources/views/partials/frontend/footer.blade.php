<!-- Footer Section -->
<footer style="background-color: #07111F; color: #94a3b8; font-size: 0.9rem;" class="pt-5 pb-4 border-top border-secondary">
    <div class="container pt-3">
        <div class="row g-4">
            <!-- Column 1: Logo & Tagline -->
            <div class="col-lg-3 col-md-6 mb-4">
                <a class="d-flex align-items-center text-white text-decoration-none fw-bold mb-3" href="{{ route('home') }}">
                    <i class="fa-solid fa-plane-departure text-gold me-2 fs-4"></i>
                    <span class="display-font tracking-wide text-uppercase" style="font-size: 1.25rem; font-weight: 700; letter-spacing: 1px;">
                        {{ $settings->site_name ?? 'Fareways' }}
                    </span>
                </a>
                <p class="mb-3 text-muted" style="line-height: 1.6;">
                    {{ $settings->tagline ?? 'Luxury Travel & Premium Flight Deals across USA & beyond.' }}
                </p>
                <div class="d-flex gap-3">
                    @if(!empty($settings->social_facebook))
                        <a href="{{ $settings->social_facebook }}" class="text-secondary hover-white fs-5" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                    @endif
                    @if(!empty($settings->social_twitter))
                        <a href="{{ $settings->social_twitter }}" class="text-secondary hover-white fs-5" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                    @endif
                    @if(!empty($settings->social_instagram))
                        <a href="{{ $settings->social_instagram }}" class="text-secondary hover-white fs-5" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    @endif
                    @if(!empty($settings->social_linkedin))
                        <a href="{{ $settings->social_linkedin }}" class="text-secondary hover-white fs-5" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                    @endif
                    @if(!empty($settings->social_youtube))
                        <a href="{{ $settings->social_youtube }}" class="text-secondary hover-white fs-5" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                    @endif
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4 ps-lg-4">
                <h5 class="text-white mb-3" style="font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; font-family: 'DM Sans', sans-serif; font-weight: 700;">Quick Links</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}" class="text-secondary hover-white text-decoration-none">Home</a></li>
                    <li><a href="{{ route('flights.search') }}" class="text-secondary hover-white text-decoration-none">Flights</a></li>
                    <li><a href="{{ route('destinations.index') }}" class="text-secondary hover-white text-decoration-none">Destinations</a></li>
                    <li><a href="{{ route('offers.index') }}" class="text-secondary hover-white text-decoration-none">Offers</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-secondary hover-white text-decoration-none">Blog</a></li>
                    <li><a href="{{ route('about') }}" class="text-secondary hover-white text-decoration-none">About</a></li>
                    <li><a href="{{ route('contact') }}" class="text-secondary hover-white text-decoration-none">Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="text-secondary hover-white text-decoration-none">FAQ</a></li>
                </ul>
            </div>

            <!-- Column 3: Popular Routes -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-white mb-3" style="font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; font-family: 'DM Sans', sans-serif; font-weight: 700;">Popular Routes</h5>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li>
                        <a href="{{ route('flights.search') }}?from=JFK&to=LAX" class="text-secondary hover-white text-decoration-none d-flex justify-content-between">
                            <span>New York <i class="fa-solid fa-arrow-right mx-1.5" style="font-size: 0.75rem;"></i> Los Angeles</span>
                            <span class="text-gold font-monospace">from $129</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('flights.search') }}?from=ORD&to=MIA" class="text-secondary hover-white text-decoration-none d-flex justify-content-between">
                            <span>Chicago <i class="fa-solid fa-arrow-right mx-1.5" style="font-size: 0.75rem;"></i> Miami</span>
                            <span class="text-gold font-monospace">from $89</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('flights.search') }}?from=DFW&to=LAS" class="text-secondary hover-white text-decoration-none d-flex justify-content-between">
                            <span>Dallas <i class="fa-solid fa-arrow-right mx-1.5" style="font-size: 0.75rem;"></i> Vegas</span>
                            <span class="text-gold font-monospace">from $79</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('flights.search') }}?from=MIA&to=JFK" class="text-secondary hover-white text-decoration-none d-flex justify-content-between">
                            <span>Miami <i class="fa-solid fa-arrow-right mx-1.5" style="font-size: 0.75rem;"></i> New York</span>
                            <span class="text-gold font-monospace">from $99</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('flights.search') }}?from=LAX&to=SEA" class="text-secondary hover-white text-decoration-none d-flex justify-content-between">
                            <span>Los Angeles <i class="fa-solid fa-arrow-right mx-1.5" style="font-size: 0.75rem;"></i> Seattle</span>
                            <span class="text-gold font-monospace">from $69</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Contact & Newsletter -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-white mb-3" style="font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; font-family: 'DM Sans', sans-serif; font-weight: 700;">Contact & Support</h5>
                <ul class="list-unstyled d-flex flex-column gap-2.5 mb-4">
                    <li class="d-flex align-items-start gap-2.5">
                        <i class="fa-solid fa-phone mt-1 text-gold"></i>
                        <div>
                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}" class="text-white fw-bold font-monospace text-decoration-none">{{ $callSettings->phone }}</a>
                            @else
                                <a href="tel:+18005550199" class="text-white fw-bold font-monospace text-decoration-none">+1 (800) 555-0199</a>
                            @endif
                            <div class="text-muted small">24/7 Booking Support Line</div>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-2.5 text-secondary">
                        <i class="fa-solid fa-envelope mt-1 text-gold"></i>
                        <span>{{ $settings->primary_email ?? 'info@destinationfareways.com' }}</span>
                    </li>
                    <li class="d-flex align-items-start gap-2.5 text-secondary">
                        <i class="fa-solid fa-location-dot mt-1 text-gold"></i>
                        <span>{{ $settings->address ?? '100 Premium Fareways Blvd' }}, {{ $settings->city ?? 'New York' }}, {{ $settings->state ?? 'NY' }} {{ $settings->zip ?? '10001' }}</span>
                    </li>
                </ul>

                <!-- Mini Newsletter Input -->
                <h6 class="text-white mb-2.5" style="font-size: 0.9rem; font-family: 'DM Sans', sans-serif; font-weight: 700;">Get Exclusive Flight Deals</h6>
                <form id="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="email" name="email" class="form-control bg-dark border-secondary text-white placeholder-secondary" placeholder="Enter your email" required style="font-size: 0.85rem; border-top-left-radius: 50px; border-bottom-left-radius: 50px; padding-left: 18px;">
                        <button type="submit" class="btn btn-gold text-uppercase" style="font-size: 0.8rem; border-top-right-radius: 50px; border-bottom-right-radius: 50px; padding: 0 20px;">Subscribe <i class="fa-solid fa-arrow-right ms-1"></i></button>
                    </div>
                    <div class="text-muted small mt-1.5" style="font-size: 0.75rem;">No spam. Unsubscribe anytime.</div>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright Bar -->
        <div class="mt-4 pt-4 border-top" style="border-color: #1a2a40 !important;">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2.5 mb-md-0">
                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">
                        {{ $settings->copyright ?? '© ' . date('Y') . ' Destination Fareways. All Rights Reserved.' }}
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">
                        Designed & Developed by <a href="https://everythingeasy.in" target="_blank" class="text-gold text-decoration-none">EverythingEasy Technology</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.hover-white {
    transition: color 0.2s;
}
.hover-white:hover {
    color: #fff !important;
}
.placeholder-secondary::placeholder {
    color: #6c757d;
}
</style>
