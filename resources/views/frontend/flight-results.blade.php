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
                    if ($name === $iata && isset($parts[1])) {
                        $name = trim($parts[1]);
                    }
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

<!-- Header Banner -->
<div style="background-color: #07111F; padding-top: 110px; padding-bottom: 25px;" class="text-white border-bottom border-secondary">
    <div class="container">
        @include('partials.frontend.breadcrumb')
        
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="h3 display-font text-white mb-1">
                    {{ $filters['from'] }} <i class="fa-solid fa-arrow-right-long text-gold mx-2" style="font-size: 1.1rem;"></i> {{ $filters['to'] }}
                </h1>
                <div class="small text-white-50" style="font-family: 'DM Sans', sans-serif;">
                    <span class="me-2"><i class="fa-solid fa-calendar me-1 text-gold"></i> {{ $filters['depart'] }}</span>
                    @if(!empty($filters['return']))
                        <span class="me-2"><i class="fa-solid fa-calendar me-1 text-gold"></i> {{ $filters['return'] }}</span>
                    @endif
                    <span class="me-2"><i class="fa-solid fa-user me-1 text-gold"></i> {{ $filters['passengers'] }} Passenger{{ $filters['passengers'] > 1 ? 's' : '' }}</span>
                    <span class="badge bg-gold text-navy text-capitalize">{{ str_replace('_', ' ', $filters['cabin_class']) }}</span>
                </div>
            </div>
            
            <!-- Modify Search Trigger -->
            <button class="btn btn-gold btn-sm d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#modifySearchCollapse">
                <i class="fa-solid fa-sliders"></i> Modify Search
            </button>
        </div>

        <!-- Modify Search Form (Collapsible) -->
        <div class="collapse mt-4" id="modifySearchCollapse">
            <div class="bg-dark p-4 rounded-3 border border-secondary text-start">
                @include('partials.frontend.flight-search-form')
            </div>
        </div>
    </div>
</div>

<!-- Main Results Body -->
<section class="section-alt py-5">
    <div class="container">
        
        <!-- Skeleton Loader (simulates actual query) -->
        <div id="skeleton-loader-container" class="row">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="filter-card skeleton-loader h-100" style="height: 500px;"></div>
            </div>
            <div class="col-lg-9">
                <div class="sort-bar skeleton-loader mb-4" style="height: 58px;"></div>
                <div class="card-flight skeleton-loader p-5 mb-3" style="height: 140px;"></div>
                <div class="card-flight skeleton-loader p-5 mb-3" style="height: 140px;"></div>
                <div class="card-flight skeleton-loader p-5" style="height: 140px;"></div>
            </div>
        </div>

        <!-- Real Content (Initially hidden, shown after timeout via JS) -->
        <div id="real-results-container" class="row g-4" style="display: none;">
            <!-- Left Sidebar Filters (Desktop) -->
            <div class="col-lg-3 d-none d-lg-block">
                @include('frontend.partials.flight-filters')
            </div>

            <!-- Results Column -->
            <div class="col-lg-9">
                <!-- Sort Bar -->
                <div class="sort-bar mb-3">
                    <div class="small text-muted fw-bold d-none d-sm-block">
                        Found {{ count($mockFlights) }} Flight{{ count($mockFlights) > 1 ? 's' : '' }}
                        @if(($apiResult['source'] ?? '') === 'booking_com15')
                            <span class="badge bg-success ms-2">Live Booking.com15</span>
                        @else
                            <span class="badge bg-warning text-navy ms-2">Fallback Results</span>
                        @endif
                    </div>
                    
                    <div class="sort-options">
                        <span class="text-muted small me-2 d-none d-sm-inline">Sort by:</span>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="sort-link {{ request('sort', 'price_asc') == 'price_asc' ? 'active text-navy' : '' }}">Price <i class="fa-solid fa-arrow-up"></i></a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'duration']) }}" class="sort-link {{ request('sort') == 'duration' ? 'active text-navy' : '' }}">Duration</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'departure']) }}" class="sort-link {{ request('sort') == 'departure' ? 'active text-navy' : '' }}">Departure</a>
                    </div>

                    <!-- Mobile Filter Trigger -->
                    <button class="btn btn-outline-navy btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileFiltersOffcanvas">
                        <i class="fa-solid fa-filter me-1"></i> Filters
                    </button>
                </div>

                @if(!empty($apiResult['error']))
                    <div class="alert alert-warning small border-0 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Live API fallback active: {{ $apiResult['error'] }}
                    </div>
                @endif

                <!-- Flight Result Cards -->
                @forelse($mockFlights as $flight)
                    @php
                        $fromAirport = parseAirport($flight['from']);
                        $toAirport = parseAirport($flight['to']);
                    @endphp
                    <div class="card-flight mb-4 bg-white border border-light-subtle rounded-4 shadow-sm p-4 hover-shadow-lg transition-transform" style="transition: all 0.3s ease;">
                        <div class="row align-items-center g-3">
                            <!-- Logo and Carrier -->
                            <div class="col-lg-3 col-md-4 col-12 d-flex align-items-center gap-3">
                                <div class="airline-logo-wrapper d-flex align-items-center justify-content-center p-2 rounded-circle" style="background-color: #f8fafc; border: 1.5px solid rgba(7, 17, 31, 0.05); width: 54px; height: 54px; flex-shrink: 0;">
                                    <img src="{{ $flight['airline_logo'] }}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;" alt="{{ $flight['airline_name'] }}">
                                </div>
                                <div class="text-start">
                                    <div class="fw-bold text-navy mb-0.5" style="font-size: 0.95rem; font-family: 'DM Sans', sans-serif;">{{ $flight['airline_name'] }}</div>
                                    <span class="badge bg-light text-navy border font-monospace text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.5px; padding: 2px 6px; color: #07111F !important;">{{ $flight['airline_code'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Times & Route -->
                            <div class="col-lg-5 col-md-8 col-12">
                                <div class="d-flex justify-content-between align-items-center text-center">
                                    <!-- Departure Airport -->
                                    <div class="text-start" style="width: 33%;">
                                        <div class="fw-extrabold text-navy fs-4 mb-0.5" style="letter-spacing: -0.5px;">{{ $flight['departure_time'] }}</div>
                                        <div class="fw-bold text-gold font-monospace text-uppercase" style="font-size: 0.9rem; color: #F59E0B !important;">{{ $fromAirport['iata'] }}</div>
                                        @if(!empty($fromAirport['name']))
                                            <div class="text-muted text-truncate small" style="font-size: 0.7rem; max-width: 120px;" title="{{ $fromAirport['name'] }}">{{ $fromAirport['name'] }}</div>
                                        @endif
                                    </div>
                                    
                                    <!-- Timeline graphics -->
                                    <div class="flex-grow-1 px-2 position-relative d-flex flex-column align-items-center">
                                        <span class="small text-muted fw-semibold font-monospace mb-1.5" style="font-size: 0.72rem; letter-spacing: 0.3px;">{{ $flight['duration'] }}</span>
                                        <div class="w-100 d-flex align-items-center position-relative mb-2" style="height: 2px;">
                                            <div class="w-100 bg-secondary-subtle" style="height: 1.5px; border-radius: 10px;"></div>
                                            <div class="position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%);">
                                                <i class="fa-solid fa-plane text-gold" style="font-size: 0.75rem; transform: rotate(90deg); color: #F59E0B !important;"></i>
                                            </div>
                                        </div>
                                        <span class="badge bg-navy text-white rounded-pill px-3 py-1 fw-bold tracking-wide text-uppercase" style="font-size: 0.64rem; letter-spacing: 0.5px; background-color: #07111F !important;">
                                            @if($flight['stops'] == 0)
                                                Nonstop
                                            @else
                                                {{ $flight['stops'] }} Stop{{ $flight['stops'] > 1 ? 's' : '' }}
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <!-- Arrival Airport -->
                                    <div class="text-end" style="width: 33%;">
                                        <div class="fw-extrabold text-navy fs-4 mb-0.5" style="letter-spacing: -0.5px;">{{ $flight['arrival_time'] }}</div>
                                        <div class="fw-bold text-gold font-monospace text-uppercase" style="font-size: 0.9rem; color: #F59E0B !important;">{{ $toAirport['iata'] }}</div>
                                        @if(!empty($toAirport['name']))
                                            <div class="text-muted text-truncate small ms-auto" style="font-size: 0.7rem; max-width: 120px;" title="{{ $toAirport['name'] }}">{{ $toAirport['name'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Baggage & Support tags -->
                            <div class="col-lg-2 col-md-6 col-12 text-center text-lg-start ps-lg-4 border-start-lg">
                                <div class="d-flex flex-column gap-2.5">
                                    <div class="small text-navy d-flex align-items-center gap-2 justify-content-center justify-content-lg-start" style="font-family: 'DM Sans', sans-serif; font-weight: 500;">
                                        <i class="fa-solid fa-suitcase-rolling text-gold" style="font-size: 0.9rem; color: #F59E0B !important;"></i>
                                        <span>{{ $flight['baggage_allowance'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-center justify-content-lg-start">
                                        <span class="badge px-2.5 py-1.5 rounded text-success fw-bold border border-success-subtle bg-success-subtle" style="font-size: 0.72rem; letter-spacing: 0.2px; background-color: rgba(25, 135, 84, 0.1) !important; color: #198754 !important;">
                                            <i class="fa-solid fa-circle-check me-1"></i> {{ $flight['refund_policy'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing and CTA -->
                            <div class="col-lg-2 col-md-6 col-12 text-center text-lg-end border-start-lg">
                                <div class="d-flex flex-column gap-2">
                                    <div class="text-lg-end text-center">
                                        <span class="text-muted small d-block mb-0.5" style="font-size: 0.75rem; letter-spacing: 0.3px; text-transform: uppercase;">Total Fare</span>
                                        <span class="price text-gold fw-extrabold font-monospace" style="font-size: 1.45rem; color: #F59E0B !important;">{{ $flight['currency'] ?? 'USD' }} {{ number_format($flight['price']) }}</span>
                                    </div>
                                    <a href="{{ route('flights.details', $flight['id']) }}?from={{ $flight['from'] }}&to={{ $flight['to'] }}&depart={{ request('depart') }}&return={{ request('return') }}&cabin_class={{ $flight['cabin_class'] }}@if(!empty($flight['token']))&token={{ urlencode($flight['token']) }}@endif" class="btn btn-navy w-100 py-2.5 d-flex align-items-center justify-content-center gap-2 rounded-3 shadow transition-lift fw-bold" style="background-color: #07111F !important; color: #ffffff !important; border: none; font-size: 0.85rem;">
                                        Select Flight <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center bg-white border border-light rounded-4 shadow-sm">
                        <i class="fa-solid fa-plane-slash text-muted fs-1 mb-3"></i>
                        <h4 class="text-navy fw-bold">No Flights Found</h4>
                        <p class="text-secondary max-width-500 mx-auto mb-4">No flights matched your filter parameters. Try expanding your price limit or clearing stop choices.</p>
                        <a href="{{ route('flights.search') }}" class="btn btn-gold btn-sm px-4">Reset Search</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- Mobile Filters Drawer (Offcanvas) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileFiltersOffcanvas">
    <div class="offcanvas-header bg-navy text-white">
        <h5 class="offcanvas-title display-font">Filters</h5>
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
    // Delay and fade out skeleton loader, then show real results to simulate flight API calls
    setTimeout(function() {
        $('#skeleton-loader-container').fadeOut(300, function() {
            $('#real-results-container').fadeIn(300);
        });
    }, 1200);
});
</script>
@endsection
