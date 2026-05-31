{{-- ── Mobile Sticky CTA Bar ── --}}
<div class="offer-mobile-cta d-lg-none">
    <div class="offer-mobile-cta-inner">
        <div class="offer-mobile-cta-text">
            <span class="offer-cta-chip"><i class="fa-solid fa-tag"></i> Best Price Guarantee</span>
            <span class="offer-cta-title">Book Cheap Flights</span>
        </div>
        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
           class="btn btn-gold fw-bold text-navy d-flex align-items-center gap-1 offer-cta-btn">
            <i class="fa-solid fa-phone"></i> Call Now
        </a>
        <a href="{{ route('booking.enquiry') }}"
           class="btn btn-outline-light fw-semibold d-flex align-items-center gap-1 offer-enquire-btn offer-cta-btn">
            Book Now
        </a>
    </div>
</div>
