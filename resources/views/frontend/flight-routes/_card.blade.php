<div class="col-12 col-sm-6 col-lg-4 col-xl-3" data-aos="fade-up">
    <a href="{{ route('flight-routes.show', $r->slug) }}" class="text-decoration-none">
        <div class="card border-0 shadow-sm h-100 route-card rounded-3 overflow-hidden" style="transition: transform 0.2s, box-shadow 0.2s;">
            {{-- Cover image or gradient placeholder --}}
            <div class="route-card-img position-relative" style="height: 140px; overflow: hidden; background: linear-gradient(135deg, #07111F 0%, #1a3a5c 100%);">
                @if($resolveImg($r->featured_image))
                    <img src="{{ $resolveImg($r->featured_image) }}" alt="{{ $r->title }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                    <div class="position-absolute inset-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 40%, rgba(7,17,31,0.7) 100%);"></div>
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-plane text-white opacity-25" style="font-size: 3rem;"></i>
                    </div>
                @endif

                {{-- Price badge --}}
                @if($r->starting_price > 0)
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-gold text-navy fw-bold px-2 py-1 rounded-pill" style="background-color: #F59E0B !important; font-size: 0.75rem;">
                        from ${{ number_format($r->starting_price, 0) }}
                    </span>
                </div>
                @endif

                {{-- Type badge --}}
                <div class="position-absolute top-0 start-0 m-2">
                    @if($r->is_domestic)
                        <span class="badge bg-white bg-opacity-20 text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem; backdrop-filter: blur(4px);">
                            <i class="fa-solid fa-flag-usa me-1"></i>Domestic
                        </span>
                    @else
                        <span class="badge bg-white bg-opacity-20 text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem; backdrop-filter: blur(4px);">
                            <i class="fa-solid fa-earth-americas me-1"></i>International
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card body --}}
            <div class="card-body p-3">
                {{-- Route arrow --}}
                <div class="d-flex align-items-center gap-1 mb-2">
                    <span class="fw-bold text-navy" style="font-size: 0.92rem;">{{ $r->origin_city }}</span>
                    <i class="fa-solid fa-arrow-right text-gold mx-1" style="font-size: 0.7rem;"></i>
                    <span class="fw-bold text-navy" style="font-size: 0.92rem;">{{ $r->destination_city }}</span>
                </div>

                {{-- Airport codes --}}
                @if($r->origin_airport_code && $r->destination_airport_code)
                <div class="d-flex align-items-center gap-1 mb-2">
                    <span class="badge bg-navy text-white font-monospace px-2" style="font-size: 0.7rem;">{{ $r->origin_airport_code }}</span>
                    <i class="fa-solid fa-plane text-muted mx-1" style="font-size: 0.6rem;"></i>
                    <span class="badge bg-navy text-white font-monospace px-2" style="font-size: 0.7rem;">{{ $r->destination_airport_code }}</span>
                </div>
                @endif

                {{-- Duration + airlines --}}
                <div class="d-flex flex-wrap gap-2 mt-auto">
                    @if($r->flight_duration)
                    <span class="text-muted" style="font-size: 0.75rem;">
                        <i class="fa-regular fa-clock me-1"></i>{{ $r->flight_duration }}
                    </span>
                    @endif
                    @if($r->airlines)
                    <span class="text-muted" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-plane-departure me-1"></i>{{ Str::limit($r->airlines, 24) }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="card-footer bg-softgray border-0 px-3 py-2 d-flex justify-content-between align-items-center">
                <span class="small text-gold fw-semibold" style="font-size: 0.75rem;">View Route Details</span>
                <i class="fa-solid fa-arrow-right text-gold" style="font-size: 0.75rem;"></i>
            </div>
        </div>
    </a>
</div>

<style>
.route-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12) !important;
}
</style>
