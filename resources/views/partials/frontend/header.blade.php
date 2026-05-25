<!-- Navbar Header Section -->
<nav class="navbar navbar-expand-lg fixed-top {{ request()->routeIs('home') ? 'navbar-transparent' : 'navbar-solid scrolled' }}" id="frontend-navbar">
    <div class="container py-1.5">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center text-white text-decoration-none fw-bold" href="{{ route('home') }}">
            <i class="fa-solid fa-plane-departure text-gold me-2 fs-4"></i>
            <span class="display-font tracking-wide text-uppercase" style="font-size: 1.15rem; font-weight: 700;">
                {{ $settings->site_name ?? 'Fareways' }}
            </span>
        </a>

        <!-- Hamburger Toggle Mobile -->
        <button class="navbar-toggler border-0 text-white p-2" type="button" data-bs-toggle="collapse" data-bs-target="#frontendNavbarCollapse" aria-controls="frontendNavbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars fs-4"></i>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="frontendNavbarCollapse">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 py-3 py-lg-0 gap-1.5 align-items-start align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                
                <!-- Flights Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('flights.*') || request()->routeIs('booking.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Flights
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg py-2 mt-2">
                        <li>
                            <a class="dropdown-item py-2 px-3.5 small" href="{{ route('flights.search') }}">
                                <i class="fa-solid fa-magnifying-glass me-2 text-gold"></i>Search Flights
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3.5 small" href="{{ route('offers.index') }}">
                                <i class="fa-solid fa-tags me-2 text-gold"></i>Flight Deals
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3.5 small" href="{{ route('booking.enquiry') }}">
                                <i class="fa-solid fa-file-invoice me-2 text-gold"></i>Booking Enquiry
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('destinations.*') ? 'active' : '' }}" href="{{ route('destinations.index') }}">Destinations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('offers.*') ? 'active' : '' }}" href="{{ route('offers.index') }}">Offers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('blog') || request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>

            <!-- Contact/Phone number -->
            @if(!empty($callSettings) && $callSettings->status)
                <div class="d-none d-lg-block">
                    <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold px-4 py-2 text-navy d-flex align-items-center gap-2 font-monospace" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fa-solid fa-phone"></i> {{ $callSettings->phone }}
                    </a>
                </div>
                
                <!-- Mobile Phone CTA (Bottom of mobile menu) -->
                <div class="d-block d-lg-none w-100 mt-3 pt-3 border-top border-secondary">
                    <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold w-100 py-2.5 text-navy d-flex align-items-center justify-content-center gap-2 fw-bold">
                        <i class="fa-solid fa-phone"></i> Call Now: {{ $callSettings->phone }}
                    </a>
                </div>
            @else
                <div class="d-none d-lg-block">
                    <a href="tel:+18005550199" class="btn btn-gold px-4 py-2 text-navy d-flex align-items-center gap-2 font-monospace" style="font-size: 0.9rem; font-weight: 700;">
                        <i class="fa-solid fa-phone"></i> +1 (800) 555-0199
                    </a>
                </div>
                <div class="d-block d-lg-none w-100 mt-3 pt-3 border-top border-secondary">
                    <a href="tel:+18005550199" class="btn btn-gold w-100 py-2.5 text-navy d-flex align-items-center justify-content-center gap-2 fw-bold">
                        <i class="fa-solid fa-phone"></i> Call Now: +1 (800) 555-0199
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>
