@extends('layouts.frontend')

@php
if (!function_exists('parseAirport')) {
    function parseAirport($airportStr) {
        $airportStr = trim($airportStr);
        $parts = explode(' - ', $airportStr);
        if (count($parts) >= 2) {
            foreach ($parts as $part) {
                if (preg_match('/^[A-Z]{3}$/', trim($part))) {
                    $iata = trim($part);
                    $name = trim($parts[0]);
                    if ($name === $iata && isset($parts[1])) { $name = trim($parts[1]); }
                    return ['iata' => $iata, 'name' => $name];
                }
            }
        }
        if (preg_match('/\(([A-Z]{3})\)/i', $airportStr, $matches)) {
            return ['iata' => strtoupper($matches[1]), 'name' => trim(str_replace($matches[0], '', $airportStr))];
        }
        if (preg_match('/^[A-Z]{3}$/i', $airportStr)) {
            return ['iata' => strtoupper($airportStr), 'name' => ''];
        }
        return ['iata' => strtoupper(substr($airportStr, 0, 3)), 'name' => $airportStr];
    }
}
@endphp

@section('content')

{{-- ── PREMIUM HEADER BANNER ── --}}
<div class="fr-header position-relative overflow-hidden">
    <div class="fr-header-pattern"></div>
    <div class="fr-header-orb orb-1"></div>
    <div class="fr-header-orb orb-2"></div>

    <div class="container position-relative" style="z-index:2; padding-top:110px; padding-bottom:28px;">
        @include('partials.frontend.breadcrumb')

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
            <div>
                {{-- Route display --}}
                <div class="fr-route-display">
                    <span class="fr-iata">{{ $filters['from'] }}</span>
                    <span class="fr-route-arrow">
                        <div class="fr-route-line"></div>
                        <i class="fa-solid fa-plane text-gold fr-plane-icon"></i>
                    </span>
                    <span class="fr-iata">{{ $filters['to'] }}</span>
                </div>
                <div class="fr-meta-pills mt-2">
                    <span class="fr-meta-pill">
                        <i class="fa-solid fa-calendar text-gold"></i>{{ $filters['depart'] }}
                    </span>
                    @if(!empty($filters['return']))
                        <span class="fr-meta-pill">
                            <i class="fa-solid fa-calendar-check text-gold"></i>{{ $filters['return'] }}
                        </span>
                    @endif
                    <span class="fr-meta-pill">
                        <i class="fa-solid fa-user text-gold"></i>{{ $filters['passengers'] }} Pax
                    </span>
                    <span class="fr-meta-pill fr-cabin-pill">
                        {{ ucwords(str_replace('_', ' ', $filters['cabin_class'])) }}
                    </span>
                </div>
            </div>

            <button class="btn btn-gold d-flex align-items-center gap-2 fw-bold" type="button"
                    data-bs-toggle="collapse" data-bs-target="#modifySearchCollapse">
                <i class="fa-solid fa-sliders"></i> Modify Search
            </button>
        </div>

        {{-- Modify search collapsible --}}
        <div class="collapse mt-4" id="modifySearchCollapse">
            <div class="fr-modify-wrap">
                @include('partials.frontend.flight-search-form')
            </div>
        </div>
    </div>
</div>

{{-- ── MAIN RESULTS ── --}}
<section class="py-5 bg-softgray">
    <div class="container">

        {{-- Skeleton loader --}}
        <div id="skeleton-loader-container" class="row g-4">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="filter-card skeleton-loader" style="height:480px;"></div>
            </div>
            <div class="col-lg-9">
                <div class="fr-sort-bar skeleton-loader mb-4" style="height:52px;border-radius:12px;"></div>
                @for($s=0;$s<3;$s++)
                    <div class="fr-flight-card skeleton-loader mb-3" style="height:130px;border-radius:16px;"></div>
                @endfor
            </div>
        </div>

        {{-- Real content --}}
        <div id="real-results-container" class="row g-4" style="display:none;">

            {{-- Sidebar Filters --}}
            <div class="col-lg-3 d-none d-lg-block">
                @include('frontend.partials.flight-filters')
            </div>

            {{-- Results Column --}}
            <div class="col-lg-9">

                {{-- Sort Bar --}}
                <div class="fr-sort-bar mb-4">
                    <div class="fr-results-count">
                        <i class="fa-solid fa-plane-departure text-gold me-2"></i>
                        <span class="fw-bold text-navy">{{ count($mockFlights) }} Flight{{ count($mockFlights) > 1 ? 's' : '' }} Found</span>
                        @if(($apiResult['source'] ?? '') === 'booking_com15')
                            <span class="badge ms-2 px-2 py-1 fw-bold" style="background:rgba(34,197,94,.12);color:#16a34a;font-size:.7rem;">
                                <i class="fa-solid fa-circle me-1" style="font-size:.5rem;"></i>Live
                            </span>
                        @else
                            <span class="badge ms-2 px-2 py-1 fw-bold" style="background:rgba(245,158,11,.12);color:#d97706;font-size:.7rem;">
                                <i class="fa-solid fa-database me-1" style="font-size:.6rem;"></i>Sample Fares
                            </span>
                        @endif
                    </div>
                    <div class="fr-sort-links">
                        <span class="text-muted small fw-semibold me-1">Sort:</span>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}"
                           class="fr-sort-link {{ request('sort', 'price_asc') == 'price_asc' ? 'active' : '' }}">
                            Price <i class="fa-solid fa-arrow-up" style="font-size:.65rem;"></i>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'duration']) }}"
                           class="fr-sort-link {{ request('sort') == 'duration' ? 'active' : '' }}">Duration</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'departure']) }}"
                           class="fr-sort-link {{ request('sort') == 'departure' ? 'active' : '' }}">Departure</a>
                    </div>
                    <button class="btn btn-outline-navy btn-sm d-lg-none d-flex align-items-center gap-1"
                            type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileFiltersOffcanvas">
                        <i class="fa-solid fa-sliders"></i> Filters
                    </button>
                </div>

                {{-- API Error --}}
                @if(!empty($apiResult['error']))
                    <div class="alert border-0 mb-4 small d-flex align-items-start gap-2"
                         style="background:rgba(245,158,11,.08);border-left:3px solid #F59E0B !important;border-radius:10px;color:#92400e;">
                        <i class="fa-solid fa-triangle-exclamation text-gold mt-0.5 flex-shrink-0"></i>
                        <span>{{ $apiResult['error'] }}</span>
                    </div>
                @endif

                {{-- Flight Cards --}}
                @forelse($mockFlights as $flight)
                    @php
                        $fromAirport = parseAirport($flight['from']);
                        $toAirport   = parseAirport($flight['to']);
                        $isNonstop   = $flight['stops'] == 0;
                        $detailUrl   = route('flights.details', $flight['id'])
                            . '?from=' . $flight['from']
                            . '&to=' . $flight['to']
                            . '&depart=' . request('depart')
                            . '&return=' . request('return')
                            . '&cabin_class=' . $flight['cabin_class']
                            . (!empty($flight['token']) ? '&token=' . urlencode($flight['token']) : '');
                    @endphp
                    <div class="fr-flight-card mb-3">
                        <div class="row align-items-center g-0">

                            {{-- Airline --}}
                            <div class="col-lg-3 col-12 fr-card-airline">
                                <div class="fr-airline-logo">
                                    <img src="{{ $flight['airline_logo'] }}" alt="{{ $flight['airline_name'] }}" loading="lazy">
                                </div>
                                <div>
                                    <div class="fr-airline-name">{{ $flight['airline_name'] }}</div>
                                    <span class="fr-airline-code">{{ $flight['airline_code'] }}</span>
                                </div>
                            </div>

                            {{-- Route timeline --}}
                            <div class="col-lg-5 col-12 fr-card-route">
                                <div class="fr-time-block text-start">
                                    <div class="fr-time">{{ $flight['departure_time'] }}</div>
                                    <div class="fr-airport-code">{{ $fromAirport['iata'] }}</div>
                                    @if(!empty($fromAirport['name']))
                                        <div class="fr-airport-name">{{ $fromAirport['name'] }}</div>
                                    @endif
                                </div>

                                <div class="fr-timeline">
                                    <div class="fr-duration">{{ $flight['duration'] }}</div>
                                    <div class="fr-line-wrap">
                                        <div class="fr-line-dot"></div>
                                        <div class="fr-line"></div>
                                        <i class="fa-solid fa-plane fr-plane-fly"></i>
                                        <div class="fr-line"></div>
                                        <div class="fr-line-dot"></div>
                                    </div>
                                    <span class="fr-stops-badge {{ $isNonstop ? 'nonstop' : 'stops' }}">
                                        @if($isNonstop) Nonstop
                                        @else {{ $flight['stops'] }} Stop{{ $flight['stops'] > 1 ? 's' : '' }}
                                        @endif
                                    </span>
                                </div>

                                <div class="fr-time-block text-end">
                                    <div class="fr-time">{{ $flight['arrival_time'] }}</div>
                                    <div class="fr-airport-code">{{ $toAirport['iata'] }}</div>
                                    @if(!empty($toAirport['name']))
                                        <div class="fr-airport-name">{{ $toAirport['name'] }}</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Baggage & policy --}}
                            <div class="col-lg-2 col-12 fr-card-info">
                                <div class="fr-info-item">
                                    <i class="fa-solid fa-suitcase-rolling text-gold"></i>
                                    <span>{{ $flight['baggage_allowance'] }}</span>
                                </div>
                                <span class="fr-refund-badge">
                                    <i class="fa-solid fa-circle-check"></i>
                                    {{ $flight['refund_policy'] }}
                                </span>
                            </div>

                            {{-- Price + CTA --}}
                            <div class="col-lg-2 col-12 fr-card-price">
                                <div class="fr-price-label">Total Fare</div>
                                <div class="fr-price">
                                    <span class="fr-currency">{{ $flight['currency'] ?? 'USD' }}</span>
                                    <span class="fr-amount">{{ number_format($flight['price']) }}</span>
                                </div>
                                <a href="{{ $detailUrl }}" class="btn btn-gold w-100 fw-bold d-flex align-items-center justify-content-center gap-2 fr-select-btn">
                                    Select <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="fr-empty-state text-center py-5">
                        <div class="fr-empty-icon mb-4"><i class="fa-solid fa-plane-slash"></i></div>
                        <h4 class="fw-bold text-navy mb-2">No Flights Found</h4>
                        <p class="text-muted mb-4">No flights matched your filters. Try expanding your price range or clearing stop preferences.</p>
                        <a href="{{ route('flights.search') }}" class="btn btn-gold px-4 py-2">Reset Search</a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</section>

{{-- Mobile Sticky CTA Bar --}}
<div class="offer-mobile-cta d-lg-none">
    <div class="d-flex gap-2 align-items-center">
        <div class="flex-grow-1">
            <span class="d-block text-muted-white small fw-semibold text-uppercase" style="font-size:.68rem;letter-spacing:.5px;">Need Help?</span>
            <span class="text-white fw-bold" style="font-size:.9rem;">Talk to an Expert</span>
        </div>
        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
           class="btn btn-gold px-4 py-2 fw-bold text-navy d-flex align-items-center gap-2 flex-shrink-0">
            <i class="fa-solid fa-phone"></i> Call Now
        </a>
        <a href="{{ route('booking.enquiry') }}"
           class="btn btn-outline-light px-3 py-2 fw-semibold d-flex align-items-center gap-2 flex-shrink-0 offer-enquire-btn">
            Enquire
        </a>
    </div>
</div>

{{-- Mobile Filters Offcanvas --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileFiltersOffcanvas">
    <div class="offcanvas-header bg-navy text-white">
        <h5 class="offcanvas-title display-font">Filter Flights</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        @include('frontend.partials.flight-filters')
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#skeleton-loader-container').fadeOut(300, function() {
            $('#real-results-container').fadeIn(400);
        });
    }, 1200);
});
</script>

<style>
/* ── Header ──────────────────────────────────────────── */
.fr-header {
    background: linear-gradient(160deg, #07111F 0%, #0e2040 50%, #07111F 100%);
}
.fr-header-pattern {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none; z-index: 1;
}
.fr-header-orb { position: absolute; border-radius: 50%; filter: blur(70px); pointer-events: none; z-index: 0; }
.fr-header-orb.orb-1 { width:320px;height:320px;background:rgba(245,158,11,.07);top:-80px;right:-60px; }
.fr-header-orb.orb-2 { width:240px;height:240px;background:rgba(29,78,216,.08);bottom:-60px;left:-40px; }

/* Route display */
.fr-route-display { display:flex; align-items:center; gap:12px; }
.fr-iata {
    font-family:'JetBrains Mono',monospace;
    font-size:clamp(1.8rem,4vw,2.5rem);
    font-weight:800; color:#fff; line-height:1;
}
.fr-route-arrow { display:flex; align-items:center; gap:6px; flex-direction:column; }
.fr-route-line { width:60px; height:2px; background:linear-gradient(90deg,rgba(245,158,11,.3),#F59E0B,rgba(245,158,11,.3)); border-radius:2px; }
.fr-plane-icon { font-size:.9rem; color:#F59E0B !important; }

/* Meta pills */
.fr-meta-pills { display:flex; flex-wrap:wrap; gap:8px; }
.fr-meta-pill {
    display:inline-flex; align-items:center; gap:5px;
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12);
    color:rgba(255,255,255,.8); font-size:.78rem; font-weight:600;
    padding:4px 12px; border-radius:100px; backdrop-filter:blur(4px);
}
.fr-cabin-pill { background:rgba(245,158,11,.15); border-color:rgba(245,158,11,.3); color:#F59E0B; }

.fr-modify-wrap {
    background:rgba(255,255,255,.05);
    border:1px solid rgba(255,255,255,.1);
    border-radius:16px; padding:20px;
    backdrop-filter:blur(10px);
}

/* ── Sort Bar ────────────────────────────────────────── */
.fr-sort-bar {
    display:flex; align-items:center; justify-content:space-between;
    gap:12px; flex-wrap:wrap;
    background:#fff;
    border:1px solid rgba(7,17,31,.07);
    border-radius:14px;
    padding:12px 20px;
    box-shadow:0 2px 12px rgba(7,17,31,.06);
}
.fr-results-count { display:flex; align-items:center; font-size:.9rem; }
.fr-sort-links { display:flex; align-items:center; gap:4px; }
.fr-sort-link {
    font-size:.82rem; font-weight:600; color:#64748B;
    text-decoration:none; padding:5px 12px; border-radius:100px;
    transition:all .2s ease; border:1px solid transparent;
}
.fr-sort-link:hover { color:#07111F; background:#F8FAFC; }
.fr-sort-link.active {
    color:#07111F; background:rgba(245,158,11,.12);
    border-color:rgba(245,158,11,.3); font-weight:700;
}

/* ── Flight Card ─────────────────────────────────────── */
.fr-flight-card {
    background:#fff;
    border:1px solid rgba(7,17,31,.07);
    border-radius:18px;
    box-shadow:0 4px 16px rgba(7,17,31,.06);
    overflow:hidden;
    transition:transform .3s cubic-bezier(.165,.84,.44,1),
               box-shadow .3s cubic-bezier(.165,.84,.44,1),
               border-color .3s ease;
}
.fr-flight-card:hover {
    transform:translateY(-4px);
    box-shadow:0 16px 40px rgba(7,17,31,.12);
    border-color:rgba(245,158,11,.25);
}
.fr-flight-card > .row { min-height:110px; }

/* Airline col */
.fr-card-airline {
    display:flex; align-items:center; gap:14px;
    padding:20px 16px 20px 22px;
    border-right:1px solid rgba(7,17,31,.05);
}
.fr-airline-logo {
    width:50px; height:50px; flex-shrink:0;
    border-radius:12px;
    border:1.5px solid rgba(7,17,31,.07);
    background:#F8FAFC;
    display:flex; align-items:center; justify-content:center;
    overflow:hidden;
}
.fr-airline-logo img { width:34px; height:34px; object-fit:contain; }
.fr-airline-name { font-weight:700; font-size:.9rem; color:#07111F; margin-bottom:4px; }
.fr-airline-code {
    display:inline-block;
    background:#F8FAFC; border:1px solid #E2E8F0;
    border-radius:6px; padding:2px 8px;
    font-family:'JetBrains Mono',monospace;
    font-size:.68rem; font-weight:700; color:#64748B;
    text-transform:uppercase;
}

/* Route col */
.fr-card-route {
    display:flex; align-items:center; justify-content:space-between;
    padding:20px 16px;
    border-right:1px solid rgba(7,17,31,.05);
}
.fr-time-block { flex-shrink:0; min-width:64px; }
.fr-time {
    font-family:'JetBrains Mono',monospace;
    font-size:1.5rem; font-weight:800; color:#07111F; line-height:1;
}
.fr-airport-code {
    font-family:'JetBrains Mono',monospace;
    font-size:.82rem; font-weight:700; color:#F59E0B;
    text-transform:uppercase; margin-top:3px;
}
.fr-airport-name { font-size:.68rem; color:#94a3b8; margin-top:2px; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

/* Timeline */
.fr-timeline {
    flex:1; display:flex; flex-direction:column;
    align-items:center; gap:5px; padding:0 12px;
}
.fr-duration { font-size:.72rem; font-weight:700; color:#64748B; letter-spacing:.3px; font-family:'JetBrains Mono',monospace; }
.fr-line-wrap { display:flex; align-items:center; width:100%; gap:0; }
.fr-line { flex:1; height:1.5px; background:linear-gradient(90deg,rgba(7,17,31,.08),rgba(7,17,31,.15)); }
.fr-line-dot { width:7px; height:7px; border-radius:50%; background:#F59E0B; flex-shrink:0; box-shadow:0 0 0 2px rgba(245,158,11,.2); }
.fr-plane-fly { font-size:.8rem; color:#F59E0B !important; margin:0 4px; flex-shrink:0; }
.fr-stops-badge {
    font-size:.65rem; font-weight:800; text-transform:uppercase;
    letter-spacing:.5px; padding:3px 10px; border-radius:100px;
}
.fr-stops-badge.nonstop { background:rgba(34,197,94,.1); color:#16a34a; border:1px solid rgba(34,197,94,.25); }
.fr-stops-badge.stops { background:#07111F; color:#fff; }

/* Info col */
.fr-card-info {
    display:flex; flex-direction:column; align-items:center;
    justify-content:center; gap:8px; padding:20px 12px;
    border-right:1px solid rgba(7,17,31,.05);
    text-align:center;
}
.fr-info-item { display:flex; align-items:center; gap:6px; font-size:.8rem; font-weight:500; color:#374151; }
.fr-info-item i { font-size:.85rem; }
.fr-refund-badge {
    display:inline-flex; align-items:center; gap:4px;
    background:rgba(34,197,94,.08); border:1px solid rgba(34,197,94,.2);
    color:#16a34a; font-size:.68rem; font-weight:700;
    padding:3px 10px; border-radius:100px;
}

/* Price col */
.fr-card-price {
    display:flex; flex-direction:column; align-items:center;
    justify-content:center; gap:6px; padding:20px 18px;
    text-align:center;
}
.fr-price-label { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.7px; color:#94a3b8; }
.fr-price { display:flex; align-items:baseline; gap:3px; }
.fr-currency { font-size:.8rem; font-weight:700; color:#F59E0B; font-family:'JetBrains Mono',monospace; }
.fr-amount { font-size:1.6rem; font-weight:800; color:#F59E0B; font-family:'JetBrains Mono',monospace; line-height:1; }
.fr-select-btn { font-size:.82rem; border-radius:10px; padding:8px 0; }

/* Empty state */
.fr-empty-state { background:#fff; border-radius:18px; border:1px solid rgba(7,17,31,.07); padding:48px 24px; }
.fr-empty-icon { width:72px;height:72px;border-radius:50%;background:rgba(245,158,11,.08);border:2px solid rgba(245,158,11,.2);display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:2rem;color:#F59E0B; }

/* ── Mobile responsive ────────────────────────────── */
@media (max-width: 991.98px) {
    .fr-card-airline, .fr-card-route, .fr-card-info { border-right:none; border-bottom:1px solid rgba(7,17,31,.05); }
    .fr-card-price { border-top:none; }
    .fr-card-info { flex-direction:row; justify-content:center; gap:16px; padding:12px 16px; }
    .fr-card-price { padding:16px; }
    .fr-flight-card > .row { flex-direction:column; }
}

@media (max-width: 767.98px) {
    .fr-iata { font-size:1.75rem; }
    .fr-route-line { width:40px; }
    .fr-time { font-size:1.25rem; }
    .fr-amount { font-size:1.35rem; }
    .fr-sort-bar { padding:10px 14px; }
    .fr-card-airline { padding:14px 16px; }
    .fr-card-route { padding:14px 16px; }
    .fr-timeline { padding:0 8px; }
    .fr-airport-name { display:none; }
    .fr-meta-pills { gap:6px; }
    .fr-meta-pill { font-size:.72rem; padding:3px 10px; }
}

@media (max-width: 480px) {
    .fr-card-route { flex-direction:column; gap:10px; align-items:stretch; }
    .fr-time-block.text-end { text-align:left !important; }
    .fr-timeline { flex-direction:row; padding:0; gap:8px; }
    .fr-duration { font-size:.68rem; }
}

/* Padding so last card isn't hidden behind mobile sticky bar */
@media (max-width: 991.98px) {
    body:has(.offer-mobile-cta) main { padding-bottom: 80px; }
}
</style>
@endsection
