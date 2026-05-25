@extends('layouts.frontend')

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

                <!-- Flight Result Cards -->
                @forelse($mockFlights as $flight)
                    <div class="card-flight mb-3 bg-white border rounded shadow-sm p-3.5">
                        <div class="row align-items-center g-3">
                            <!-- Logo and Carrier -->
                            <div class="col-md-3 d-flex align-items-center gap-3">
                                <img src="{{ $flight['airline_logo'] }}" class="rounded-circle" style="width: 42px; height: 42px; object-fit: cover;" alt="Airline logo">
                                <div>
                                    <div class="fw-bold text-navy" style="font-size: 0.95rem;">{{ $flight['airline_name'] }}</div>
                                    <div class="text-muted small">{{ $flight['airline_code'] }}</div>
                                </div>
                            </div>
                            
                            <!-- Times & Route -->
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center text-center">
                                    <div class="text-start">
                                        <div class="fw-bold text-navy fs-5">{{ $flight['departure_time'] }}</div>
                                        <span class="text-muted font-monospace" style="font-size: 0.8rem;">{{ $flight['from'] }}</span>
                                    </div>
                                    <div class="flex-grow-1 px-3 position-relative d-flex flex-column align-items-center">
                                        <span class="small text-muted" style="font-size: 0.75rem;">{{ $flight['duration'] }}</span>
                                        <hr class="w-100 my-1 bg-secondary">
                                        <span class="badge border border-secondary text-secondary bg-light font-sans" style="font-size: 0.7rem; padding: 2px 8px;">
                                            @if($flight['stops'] == 0)
                                                Nonstop
                                            @else
                                                {{ $flight['stops'] }} Stop{{ $flight['stops'] > 1 ? 's' : '' }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-navy fs-5">{{ $flight['arrival_time'] }}</div>
                                        <span class="text-muted font-monospace" style="font-size: 0.8rem;">{{ $flight['to'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Baggage & Support tags -->
                            <div class="col-md-2 text-center text-md-start ps-md-4 border-start">
                                <div class="small text-secondary mb-1">
                                    <i class="fa-solid fa-suitcase text-gold me-1" style="font-size: 0.8rem;"></i> {{ $flight['baggage_allowance'] }}
                                </div>
                                <span class="badge bg-light text-success border border-success" style="font-size: 0.75rem;">
                                    {{ $flight['refund_policy'] }}
                                </span>
                            </div>

                            <!-- Pricing and CTA -->
                            <div class="col-md-3 text-center text-md-end border-start">
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Total fare</span>
                                    <span class="price fs-3 fw-bold font-monospace">${{ number_format($flight['price']) }}</span>
                                </div>
                                <a href="{{ route('flights.details', $flight['id']) }}?from={{ $flight['from'] }}&to={{ $flight['to'] }}&cabin_class={{ $flight['cabin_class'] }}" class="btn btn-gold btn-sm px-4">
                                    Select <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.8rem;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center bg-white border border-light rounded">
                        <i class="fa-solid fa-plane-slash text-muted fs-1 mb-3"></i>
                        <h4 class="text-navy fw-bold">No Flights Found</h4>
                        <p class="text-secondary max-width-500 mx-auto">No mock routes matched your filter. Try adjusting stop values or selecting a higher price filter ceiling.</p>
                        <a href="{{ route('flights.search') }}" class="btn btn-gold btn-sm mt-3">Reset Search</a>
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
