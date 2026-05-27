<!-- Flight Filters Panel -->
<div class="filter-card text-start">
    <form action="{{ route('flights.results') }}" method="GET" id="filter-sidebar-form">
        <!-- Persistent parameters from search -->
        <input type="hidden" name="from" value="{{ request('from') }}">
        <input type="hidden" name="fromId" value="{{ request('fromId') }}">
        <input type="hidden" name="to" value="{{ request('to') }}">
        <input type="hidden" name="toId" value="{{ request('toId') }}">
        <input type="hidden" name="depart" value="{{ request('depart') }}">
        <input type="hidden" name="return" value="{{ request('return') }}">
        <input type="hidden" name="total_passengers" value="{{ request('total_passengers') }}">
        <input type="hidden" name="adults" value="{{ request('adults', 1) }}">
        <input type="hidden" name="children" value="{{ request('children', 0) }}">
        <input type="hidden" name="infants" value="{{ request('infants', 0) }}">
        <input type="hidden" name="cabin_class" value="{{ request('cabin_class') }}">
        <input type="hidden" name="trip_type" value="{{ request('trip_type') }}">
        <input type="hidden" name="sort" value="{{ request('sort', 'price_asc') }}">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-navy mb-0" style="font-family: 'DM Sans', sans-serif;">Filter Flights</h5>
            <a href="{{ route('flights.results') }}?from={{ request('from') }}&fromId={{ request('fromId') }}&to={{ request('to') }}&toId={{ request('toId') }}&depart={{ request('depart') }}&return={{ request('return') }}&cabin_class={{ request('cabin_class') }}" class="small text-decoration-underline text-secondary">Reset</a>
        </div>

        <!-- 1. Stops -->
        <div class="filter-section">
            <div class="filter-title">Stops</div>
            <div class="d-flex flex-column gap-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stops" id="stops-any" value="any" {{ request('stops', 'any') == 'any' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label text-navy small" for="stops-any">Any Stops</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stops" id="stops-nonstop" value="nonstop" {{ request('stops') == 'nonstop' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label text-navy small" for="stops-nonstop">Nonstop</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stops" id="stops-1" value="1" {{ request('stops') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label text-navy small" for="stops-1">1 Stop</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="stops" id="stops-2" value="2" {{ request('stops') == '2' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label text-navy small" for="stops-2">2+ Stops</label>
                </div>
            </div>
        </div>

        <!-- 2. Price Filter Ceiling Slider -->
        <div class="filter-section">
            <div class="filter-title d-flex justify-content-between">
                <span>Max Price</span>
                <span class="text-gold font-monospace fw-bold" id="price-val">${{ request('price_max', '1500') }}</span>
            </div>
            <input type="range" class="form-range" name="price_max" id="priceRange" min="50" max="1500" step="50" value="{{ request('price_max', '1500') }}" oninput="$('#price-val').text('$' + this.value)" onchange="this.form.submit()">
            <div class="d-flex justify-content-between text-muted small" style="font-size: 0.7rem;">
                <span>$50</span>
                <span>$1,500</span>
            </div>
        </div>

        <!-- 3. Departure Time Period Chips -->
        <div class="filter-section">
            <div class="filter-title">Departure Time</div>
            <div class="time-chips-container">
                @php
                    $periods = [
                        ['val' => 'morning', 'label' => 'Morning', 'desc' => '6a–12p', 'icon' => 'fa-sun'],
                        ['val' => 'afternoon', 'label' => 'Afternoon', 'desc' => '12p–6p', 'icon' => 'fa-cloud-sun'],
                        ['val' => 'evening', 'label' => 'Evening', 'desc' => '6p–12a', 'icon' => 'fa-moon'],
                        ['val' => 'night', 'label' => 'Night', 'desc' => '12a–6a', 'icon' => 'fa-cloud-moon'],
                    ];
                @endphp
                @foreach($periods as $p)
                    <input type="checkbox" name="time_period[]" id="time-{{ $p['val'] }}" value="{{ $p['val'] }}" class="time-chip-input" onchange="this.form.submit()" {{ is_array(request('time_period')) && in_array($p['val'], request('time_period')) ? 'checked' : '' }}>
                    <label for="time-{{ $p['val'] }}" class="time-chip-label">
                        <i class="fa-solid {{ $p['icon'] }}"></i>
                        <span>{{ $p['label'] }}</span>
                        <span class="text-muted" style="font-size: 0.65rem;">{{ $p['desc'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 4. Preferred Airlines Checkboxes -->
        <div class="filter-section">
            <div class="filter-title">Airlines</div>
            <div class="d-flex flex-column gap-2">
                @php
                    $carriers = [
                        ['code' => 'DL', 'name' => 'Delta Air Lines'],
                        ['code' => 'AA', 'name' => 'American Airlines'],
                        ['code' => 'UA', 'name' => 'United Airlines'],
                        ['code' => 'AS', 'name' => 'Alaska Airlines'],
                    ];
                @endphp
                @foreach($carriers as $c)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="airlines[]" id="airline-{{ $c['code'] }}" value="{{ $c['code'] }}" onchange="this.form.submit()" {{ is_array(request('airlines')) && in_array($c['code'], request('airlines')) ? 'checked' : '' }}>
                        <label class="form-check-label text-navy small" for="airline-{{ $c['code'] }}">{{ $c['name'] }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
</div>
