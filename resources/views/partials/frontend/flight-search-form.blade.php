<!-- Flight Search Form Component -->
<div class="search-form-card p-4" data-aos="fade-up">
    <!-- Form Tabs -->
    @php($activeTripType = request('trip_type', 'round-trip'))
    <div class="search-tabs d-flex gap-2 mb-4">
        <button type="button" class="search-tab-btn {{ $activeTripType === 'round-trip' || $activeTripType === 'round_trip' ? 'active' : '' }}" data-type="round-trip">Round Trip</button>
        <button type="button" class="search-tab-btn {{ $activeTripType === 'one-way' || $activeTripType === 'one_way' ? 'active' : '' }}" data-type="one-way">One Way</button>
        <button type="button" class="search-tab-btn {{ $activeTripType === 'multi-city' || $activeTripType === 'multi_city' ? 'active' : '' }}" data-type="multi-city">Multi-City</button>
    </div>

    <!-- Flight Search Form Action -->
    <form action="{{ route('flights.results') }}" method="GET" id="flight-search-main-form">
        <!-- Hidden Inputs for State Tracking -->
        <input type="hidden" name="trip_type" id="trip_type_input" value="{{ request('trip_type', 'round-trip') }}">
        <input type="hidden" name="adults" id="input_adults" value="{{ request('adults', 1) }}">
        <input type="hidden" name="children" id="input_children" value="{{ request('children', 0) }}">
        <input type="hidden" name="infants" id="input_infants" value="{{ request('infants', 0) }}">
        <input type="hidden" name="total_passengers" id="total_passengers_input" value="{{ request('total_passengers', 1) }}">

        <div class="row g-3 align-items-end">
            <!-- From City -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="search-input-group">
                    <label for="from_city_input"><i class="fa-solid fa-plane-departure me-1"></i> From</label>
                    <input type="text" name="from" id="from_city_input" class="flight-location-input" data-target="from_location_id" autocomplete="off" placeholder="Origin Airport or City" required value="{{ request('from', 'JFK') }}">
                    <input type="hidden" name="fromId" id="from_location_id" value="{{ request('fromId') }}">
                </div>
            </div>

            <!-- Swap Button -->
            <div class="col-xl-auto col-lg-12 col-md-12 swap-btn-container py-1 py-xl-0">
                <button type="button" class="swap-btn" title="Swap locations">
                    <i class="fa-solid fa-arrows-left-right d-none d-xl-block"></i>
                    <i class="fa-solid fa-arrows-up-down d-block d-xl-none"></i>
                </button>
            </div>

            <!-- To City -->
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="search-input-group">
                    <label for="to_city_input"><i class="fa-solid fa-plane-arrival me-1"></i> To</label>
                    <input type="text" name="to" id="to_city_input" class="flight-location-input" data-target="to_location_id" autocomplete="off" placeholder="Destination Airport or City" required value="{{ request('to', 'LAX') }}">
                    <input type="hidden" name="toId" id="to_location_id" value="{{ request('toId') }}">
                </div>
            </div>

            <!-- Depart Date -->
            <div class="col-xl-2 col-lg-6 col-md-6">
                <div class="search-input-group">
                    <label for="depart_date_input"><i class="fa-solid fa-calendar me-1"></i> Depart</label>
                    <input type="text" name="depart" id="depart_date_input" class="flatpickr-date" placeholder="Select Date" required value="{{ request('depart', date('Y-m-d', strtotime('+7 days'))) }}">
                </div>
            </div>

            <!-- Return Date -->
            <div class="col-xl-2 col-lg-6 col-md-6" id="return_date_wrapper">
                <div class="search-input-group">
                    <label for="return_date_input"><i class="fa-solid fa-calendar me-1"></i> Return</label>
                    <input type="text" name="return" id="return_date_input" class="flatpickr-date" placeholder="Select Date" value="{{ request('return', date('Y-m-d', strtotime('+14 days'))) }}">
                </div>
            </div>

            <!-- Passengers & Class Trigger -->
            <div class="col-xl-3 col-lg-6 col-md-6" id="passenger_dropdown_container" style="position: relative;">
                <div class="search-input-group" id="passengers_input_wrapper" style="cursor: pointer;">
                    <label><i class="fa-solid fa-users me-1"></i> Passengers & Class</label>
                    <div class="d-flex align-items-center justify-content-between">
                        <span id="passengers_summary_text" class="fw-semibold text-navy" style="font-size: 0.95rem;">
                            {{ request('total_passengers', 1) }} Passenger{{ (int) request('total_passengers', 1) > 1 ? 's' : '' }}, {{ request('cabin_class', 'Economy') }}
                        </span>
                        <i class="fa-solid fa-chevron-down fs-7 text-muted"></i>
                    </div>
                </div>

                <!-- Selector Dropdown -->
                <div class="passenger-dropdown" id="passenger_dropdown_menu">
                    <!-- Adults -->
                    <div class="passenger-row">
                        <div>
                            <div class="fw-bold text-navy" style="font-size: 0.9rem;">Adults</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Ages 12 or above</div>
                        </div>
                        <div class="counter-controls">
                            <button type="button" class="counter-btn" data-type="adults" data-action="minus">-</button>
                            <span class="counter-val" id="count_adults">{{ request('adults', 1) }}</span>
                            <button type="button" class="counter-btn" data-type="adults" data-action="plus">+</button>
                        </div>
                    </div>

                    <!-- Children -->
                    <div class="passenger-row">
                        <div>
                            <div class="fw-bold text-navy" style="font-size: 0.9rem;">Children</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Ages 2 to 11</div>
                        </div>
                        <div class="counter-controls">
                            <button type="button" class="counter-btn" data-type="children" data-action="minus">-</button>
                            <span class="counter-val" id="count_children">{{ request('children', 0) }}</span>
                            <button type="button" class="counter-btn" data-type="children" data-action="plus">+</button>
                        </div>
                    </div>

                    <!-- Infants -->
                    <div class="passenger-row">
                        <div>
                            <div class="fw-bold text-navy" style="font-size: 0.9rem;">Infants</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Ages 0 to 2 (on lap)</div>
                        </div>
                        <div class="counter-controls">
                            <button type="button" class="counter-btn" data-type="infants" data-action="minus">-</button>
                            <span class="counter-val" id="count_infants">{{ request('infants', 0) }}</span>
                            <button type="button" class="counter-btn" data-type="infants" data-action="plus">+</button>
                        </div>
                    </div>

                    <!-- Cabin Class -->
                    <div class="pt-3 border-top mt-3">
                        <label class="form-label fw-bold text-navy mb-1.5" style="font-size: 0.85rem;">Cabin Class</label>
                        <select name="cabin_class" id="cabin_class_select" class="form-select bg-light border-0 py-2" style="font-size: 0.85rem; border-radius: 6px;">
                            <option value="Economy" {{ request('cabin_class', 'Economy') === 'Economy' ? 'selected' : '' }}>Economy</option>
                            <option value="Premium Economy" {{ request('cabin_class') === 'Premium Economy' ? 'selected' : '' }}>Premium Economy</option>
                            <option value="Business" {{ request('cabin_class') === 'Business' ? 'selected' : '' }}>Business</option>
                            <option value="First" {{ request('cabin_class') === 'First' ? 'selected' : '' }}>First Class</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-xl-auto col-lg-6 col-md-6 flex-grow-1">
                <button type="submit" class="btn btn-gold w-100 py-3 text-uppercase font-monospace tracking-wide" style="font-size: 0.95rem; font-weight: 700; height: 58px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Search Flights
                </button>
            </div>
        </div>

        <div class="multi-city-fields mt-3" style="display: none;">
            <div class="row g-3">
                @for($i = 0; $i < 3; $i++)
                    <div class="col-12">
                        <div class="row g-2 align-items-end">
                            <div class="col-lg-1">
                                <span class="badge bg-light text-navy border w-100 py-2">Leg {{ $i + 1 }}</span>
                            </div>
                            <div class="col-lg-4">
                                <div class="search-input-group">
                                    <label><i class="fa-solid fa-plane-departure me-1"></i> From</label>
                                    <input type="text" name="legs[{{ $i }}][from]" placeholder="Airport or City" value="{{ request("legs.$i.from", $i === 0 ? request('from', 'JFK') : '') }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="search-input-group">
                                    <label><i class="fa-solid fa-plane-arrival me-1"></i> To</label>
                                    <input type="text" name="legs[{{ $i }}][to]" placeholder="Airport or City" value="{{ request("legs.$i.to", $i === 0 ? request('to', 'LAX') : '') }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="search-input-group">
                                    <label><i class="fa-solid fa-calendar me-1"></i> Date</label>
                                    <input type="text" name="legs[{{ $i }}][date]" class="flatpickr-date" placeholder="Select Date" value="{{ request("legs.$i.date", $i === 0 ? request('depart', date('Y-m-d', strtotime('+7 days'))) : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </form>
</div>
