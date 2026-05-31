<!-- Navbar Header Section -->
<style>
/* ── Navbar brand color override ── */
#frontend-navbar .navbar-brand,
#frontend-navbar .navbar-brand span,
.navbar-brand, .navbar-brand span { color: #ffffff !important; }
#frontend-navbar .navbar-brand i, .navbar-brand i { color: #F59E0B !important; }
</style>

<nav class="navbar navbar-expand-lg fixed-top {{ request()->routeIs('home') ? 'navbar-transparent' : 'navbar-solid scrolled' }}" id="frontend-navbar">
    <div class="container py-1.5">

        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center text-white text-decoration-none fw-bold" href="{{ route('home') }}" style="color:#fff !important;">
            <i class="fa-solid fa-plane-departure text-gold me-2 fs-4" style="color:#F59E0B !important;"></i>
            <span class="display-font tracking-wide text-uppercase" style="font-size:1.15rem;font-weight:700;color:#fff !important;">
                {{ $settings->site_name ?? 'Fareways' }}
            </span>
        </a>

        {{-- Mobile hamburger → triggers RIGHT offcanvas drawer --}}
        <button class="d-lg-none border-0 bg-transparent text-white p-2 mob-menu-btn"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileNavDrawer"
                aria-controls="mobileNavDrawer"
                aria-label="Open navigation">
            <i class="fa-solid fa-bars fs-4"></i>
        </button>

        {{-- Desktop nav (auto-shown on lg+ by Bootstrap navbar-expand-lg) --}}
        <div class="collapse navbar-collapse" id="frontendNavbarCollapse">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 py-3 py-lg-0 gap-1.5 align-items-start align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link text-white dropdown-toggle {{ request()->routeIs('flights.*') || request()->routeIs('booking.*') ? 'active' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Flights</a>
                    <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg py-2 mt-2">
                        <li><a class="dropdown-item py-2 px-3.5 small" href="{{ route('flights.search') }}">
                            <i class="fa-solid fa-magnifying-glass me-2 text-gold"></i>Search Flights</a></li>
                        <li><a class="dropdown-item py-2 px-3.5 small" href="{{ route('offers.index') }}">
                            <i class="fa-solid fa-tags me-2 text-gold"></i>Flight Deals</a></li>
                        <li><a class="dropdown-item py-2 px-3.5 small" href="{{ route('booking.enquiry') }}">
                            <i class="fa-solid fa-file-invoice me-2 text-gold"></i>Booking Enquiry</a></li>
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

            {{-- Desktop phone CTA --}}
            @if(!empty($callSettings) && $callSettings->status)
                <div class="d-none d-lg-block">
                    <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold px-4 py-2 text-navy d-flex align-items-center gap-2 font-monospace" style="font-size:0.9rem;font-weight:700;">
                        <i class="fa-solid fa-phone"></i> {{ $callSettings->phone }}
                    </a>
                </div>
            @else
                <div class="d-none d-lg-block">
                    <a href="tel:+18005550199" class="btn btn-gold px-4 py-2 text-navy d-flex align-items-center gap-2 font-monospace" style="font-size:0.9rem;font-weight:700;">
                        <i class="fa-solid fa-phone"></i> +1 (800) 555-0199
                    </a>
                </div>
            @endif
        </div>

    </div>
</nav>

{{-- ── MOBILE RIGHT-SIDE DRAWER ── --}}
<div class="offcanvas offcanvas-end mob-nav-drawer"
     tabindex="-1"
     id="mobileNavDrawer"
     aria-labelledby="mobileNavDrawerLabel">

    {{-- Drawer Header --}}
    <div class="offcanvas-header mob-drawer-header">
        <a class="d-flex align-items-center text-white text-decoration-none fw-bold gap-2" href="{{ route('home') }}">
            <i class="fa-solid fa-plane-departure fs-5" style="color:#F59E0B;"></i>
            <span class="display-font text-uppercase" style="font-size:1.05rem;font-weight:700;color:#fff;">
                {{ $settings->site_name ?? 'Fareways' }}
            </span>
        </a>
        <button type="button" class="mob-drawer-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark fs-5"></i>
        </button>
    </div>

    {{-- Drawer Body --}}
    <div class="offcanvas-body mob-drawer-body d-flex flex-column p-0">

        {{-- Nav links --}}
        <nav class="mob-nav-links flex-grow-1">

            <a href="{{ route('home') }}" class="mob-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Home</span>
            </a>

            {{-- Flights accordion --}}
            <div>
                <button class="mob-nav-item mob-nav-toggle" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mobFlightsCollapse"
                        aria-expanded="false">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-plane"></i>
                        <span>Flights</span>
                    </div>
                    <i class="fa-solid fa-chevron-down mob-chevron"></i>
                </button>
                <div class="collapse" id="mobFlightsCollapse">
                    <div class="mob-nav-sub">
                        <a href="{{ route('flights.search') }}">
                            <i class="fa-solid fa-magnifying-glass text-gold"></i> Search Flights
                        </a>
                        <a href="{{ route('offers.index') }}">
                            <i class="fa-solid fa-tags text-gold"></i> Flight Deals
                        </a>
                        <a href="{{ route('booking.enquiry') }}">
                            <i class="fa-solid fa-file-invoice text-gold"></i> Booking Enquiry
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('destinations.index') }}" class="mob-nav-item {{ request()->routeIs('destinations.*') ? 'active' : '' }}">
                <i class="fa-solid fa-earth-americas"></i>
                <span>Destinations</span>
            </a>

            <a href="{{ route('offers.index') }}" class="mob-nav-item {{ request()->routeIs('offers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket"></i>
                <span>Offers</span>
            </a>

            <a href="{{ route('blog.index') }}" class="mob-nav-item {{ request()->routeIs('blog') || request()->routeIs('blog.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i>
                <span>Blog</span>
            </a>

            <a href="{{ route('about') }}" class="mob-nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-info"></i>
                <span>About</span>
            </a>

            <a href="{{ route('contact') }}" class="mob-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope"></i>
                <span>Contact</span>
            </a>

        </nav>

        {{-- CTA at bottom --}}
        <div class="mob-drawer-footer">
            @if(!empty($callSettings) && $callSettings->status)
                <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold w-100 py-3 fw-bold font-monospace d-flex align-items-center justify-content-center gap-2 mb-2">
                    <i class="fa-solid fa-phone"></i> {{ $callSettings->phone }}
                </a>
            @else
                <a href="tel:+18005550199" class="btn btn-gold w-100 py-3 fw-bold font-monospace d-flex align-items-center justify-content-center gap-2 mb-2">
                    <i class="fa-solid fa-phone"></i> +1 (800) 555-0199
                </a>
            @endif
            <a href="{{ route('booking.enquiry') }}" class="btn btn-outline-light w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-paper-plane"></i> Book a Flight
            </a>
        </div>

    </div>
</div>

<style>
/* ── Mobile hamburger button ───────────────────── */
.mob-menu-btn { outline: none; }
.mob-menu-btn:focus { box-shadow: none; }

/* ── Drawer shell ──────────────────────────────── */
.mob-nav-drawer {
    width: 300px !important;
    max-width: 85vw;
    background: linear-gradient(160deg, #07111F 0%, #0e2040 100%) !important;
    border-left: 1px solid rgba(255,255,255,.08) !important;
}

/* ── Drawer header ─────────────────────────────── */
.mob-drawer-header {
    padding: 18px 20px;
    border-bottom: 1px solid rgba(255,255,255,.08) !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.mob-drawer-close {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background .2s ease;
    flex-shrink: 0;
}
.mob-drawer-close:hover { background: rgba(255,255,255,.15); }

/* ── Nav links ─────────────────────────────────── */
.mob-nav-links { padding: 12px 0; overflow-y: auto; }

.mob-nav-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 13px 22px;
    color: rgba(255,255,255,.78);
    text-decoration: none;
    font-family: 'DM Sans', sans-serif;
    font-size: .95rem;
    font-weight: 600;
    border-left: 3px solid transparent;
    transition: all .18s ease;
    width: 100%;
    background: transparent;
    border-top: none;
    border-right: none;
    border-bottom: none;
    text-align: left;
    cursor: pointer;
}
.mob-nav-item i:first-child {
    width: 20px;
    text-align: center;
    color: rgba(255,255,255,.45);
    font-size: .9rem;
    flex-shrink: 0;
    transition: color .18s ease;
}
.mob-nav-item:hover {
    color: #fff;
    background: rgba(255,255,255,.05);
    border-left-color: rgba(245,158,11,.4);
}
.mob-nav-item:hover i:first-child { color: #F59E0B; }
.mob-nav-item.active {
    color: #F59E0B;
    background: rgba(245,158,11,.08);
    border-left-color: #F59E0B;
}
.mob-nav-item.active i:first-child { color: #F59E0B; }

/* Toggle arrow */
.mob-nav-toggle { justify-content: space-between; }
.mob-chevron {
    font-size: .75rem;
    color: rgba(255,255,255,.4);
    transition: transform .25s ease;
}
.mob-nav-toggle[aria-expanded="true"] .mob-chevron { transform: rotate(180deg); color: #F59E0B; }

/* Sub-menu */
.mob-nav-sub {
    background: rgba(0,0,0,.2);
    border-left: 3px solid rgba(245,158,11,.25);
    margin: 0 0 4px 22px;
    border-radius: 0 8px 8px 0;
    padding: 6px 0;
}
.mob-nav-sub a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    color: rgba(255,255,255,.65);
    text-decoration: none;
    font-size: .875rem;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    transition: color .18s ease;
}
.mob-nav-sub a:hover { color: #F59E0B; }
.mob-nav-sub a i { font-size: .8rem; }

/* ── Footer CTA ────────────────────────────────── */
.mob-drawer-footer {
    padding: 16px 20px 24px;
    border-top: 1px solid rgba(255,255,255,.08);
}
.mob-drawer-footer .btn-outline-light {
    border-color: rgba(255,255,255,.25);
    color: rgba(255,255,255,.85);
    font-size: .875rem;
}
.mob-drawer-footer .btn-outline-light:hover {
    background: rgba(255,255,255,.08);
    border-color: rgba(255,255,255,.4);
    color: #fff;
}

/* ── Backdrop tint ─────────────────────────────── */
.offcanvas-backdrop.show { opacity: .65 !important; }
</style>
