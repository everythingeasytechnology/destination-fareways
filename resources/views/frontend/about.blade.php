@extends('layouts.frontend')

@section('content')

{{-- ── HERO ── --}}
<section class="offers-hero-section bg-navy text-white mt-5 position-relative overflow-hidden">
    <div class="offers-hero-pattern"></div>
    <div class="offers-hero-orb orb-1"></div>
    <div class="offers-hero-orb orb-2"></div>

    <div class="container py-5 text-center position-relative" style="z-index:2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <i class="fa-solid fa-plane-departure text-gold"></i>
            <span>15+ Years of Premium Travel</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
            {{ $page->title ?? 'About Destination Fareways' }}
        </h1>
        <p class="lead mb-4 text-muted-white" data-aos="fade-up" data-aos-delay="120">
            {{ $page->subtitle ?? 'Over 15 years connecting travelers to unbeatable flight deals.' }}
        </p>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="160">
            @include('partials.frontend.breadcrumb')
        </div>
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="220">
            <div class="offers-stat-pill"><i class="fa-solid fa-users text-gold me-2"></i>10M+ Travelers Served</div>
            <div class="offers-stat-pill"><i class="fa-solid fa-plane text-gold me-2"></i>500+ Partner Airlines</div>
            <div class="offers-stat-pill"><i class="fa-solid fa-award text-gold me-2"></i>4.9/5 Star Rated</div>
        </div>
    </div>
</section>

{{-- ── STATS COUNTERS ── --}}
@if(!empty($stats))
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row g-4 justify-content-center text-center">
            @foreach($stats as $stat)
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="about-stat-card">
                        <span class="about-stat-value">{{ $stat['value'] }}</span>
                        <span class="about-stat-label">{{ $stat['label'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── STORY SECTION ── --}}
<section class="py-5 bg-softgray">
    <div class="container py-3">
        <div class="row g-5 align-items-center">

            {{-- Left: Visual card --}}
            <div class="col-lg-4 d-none d-lg-block" data-aos="fade-up">
                <div class="about-story-visual">
                    <div class="about-story-orb"></div>
                    <div class="about-story-icon-grid">
                        <div class="about-story-icon-tile">
                            <i class="fa-solid fa-plane-departure"></i>
                            <span>500+ Airlines</span>
                        </div>
                        <div class="about-story-icon-tile gold">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>100% Secure</span>
                        </div>
                        <div class="about-story-icon-tile gold">
                            <i class="fa-solid fa-headset"></i>
                            <span>24/7 Support</span>
                        </div>
                        <div class="about-story-icon-tile">
                            <i class="fa-solid fa-tags"></i>
                            <span>Best Prices</span>
                        </div>
                    </div>
                    <div class="about-story-badge">
                        <i class="fa-solid fa-star text-gold me-1"></i>
                        Trusted Since 2009
                    </div>
                </div>
            </div>

            {{-- Right: Content --}}
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="80">
                <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" style="background:rgba(7,17,31,.07);border-color:rgba(7,17,31,.12);color:#07111F;">
                    <i class="fa-solid fa-book-open text-gold"></i>
                    <span style="color:#07111F;">Our Story</span>
                </div>
                <div class="prose-content about-prose">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── WHY CHOOSE US ── --}}
<section class="py-5 bg-white border-top border-bottom border-light">
    <div class="container py-3">
        <div class="section-title-wrapper center mb-5" data-aos="fade-up">
            <span class="d-inline-block badge mb-2 px-3 py-2 fw-bold text-uppercase" style="background:rgba(245,158,11,.12);color:#F59E0B;font-size:.7rem;letter-spacing:.8px;border-radius:100px;">Our Standard</span>
            <h2 class="display-font text-navy">Why Discerning Travelers Choose Us</h2>
            <p class="text-muted">We combine cutting-edge flight comparison engines with personalized human support.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-box text-center h-100">
                    <div class="feature-icon-circle"><i class="fa-solid fa-tags"></i></div>
                    <h4>Best Price Guarantee</h4>
                    <p>We match and beat any publicly advertised airline ticket quote.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
                <div class="feature-box text-center h-100">
                    <div class="feature-icon-circle"><i class="fa-solid fa-user-shield"></i></div>
                    <h4>Secure &amp; Trusted</h4>
                    <p>High-grade encryption safeguards all personal booking data.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
                <div class="feature-box text-center h-100">
                    <div class="feature-icon-circle"><i class="fa-solid fa-headset"></i></div>
                    <h4>24/7 Expert Support</h4>
                    <p>A real, dedicated travel consultant is always ready to answer your call.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
                <div class="feature-box text-center h-100">
                    <div class="feature-icon-circle"><i class="fa-solid fa-circle-dollar-to-slot"></i></div>
                    <h4>No Hidden Fees</h4>
                    <p>Our quotes list all service taxes upfront. What you see is what you pay.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── TRUST STRIP ── --}}
<section class="py-4 bg-softgray border-bottom">
    <div class="container">
        <div class="row g-3 justify-content-center text-center" data-aos="fade-up">
            <div class="col-6 col-md-3">
                <div class="about-trust-pill">
                    <i class="fa-solid fa-certificate text-gold"></i>
                    <span>IATA Accredited</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="about-trust-pill">
                    <i class="fa-solid fa-lock text-gold"></i>
                    <span>SSL Encrypted</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="about-trust-pill">
                    <i class="fa-solid fa-star text-gold"></i>
                    <span>4.9 / 5 Rating</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="about-trust-pill">
                    <i class="fa-solid fa-rotate-left text-gold"></i>
                    <span>Flexible Cancellations</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA STRIP (same as offers/destinations) ── --}}
<section class="py-5 bg-white border-top border-light">
    <div class="container">
        <div class="offers-cta-card" data-aos="fade-up">
            <div class="offers-cta-glow"></div>
            <div class="row align-items-center g-4 position-relative" style="z-index:2;">
                <div class="col-12 col-lg-7">
                    <span class="badge bg-gold text-navy px-3 py-1 mb-2 fw-bold" style="font-size:.7rem;letter-spacing:1px;">EXCLUSIVE ACCESS</span>
                    <h3 class="h2 fw-bold text-white mb-2">Looking for Exclusive Flight Discounts?</h3>
                    <p class="mb-0" style="color:rgba(255,255,255,.72);">
                        Our wholesale relationships allow us to issue unpublished, phone-only discounts worldwide. One call is all it takes.
                    </p>
                </div>
                <div class="col-12 col-lg-5 text-center text-lg-end">
                    @if(!empty($callSettings) && $callSettings->status)
                        <a href="tel:{{ $callSettings->phone }}"
                           class="btn btn-gold px-4 py-3 d-inline-flex align-items-center gap-2 fw-bold fs-6">
                            <i class="fa-solid fa-phone"></i> {{ $callSettings->phone }}
                        </a>
                    @else
                        <a href="tel:+18005550199"
                           class="btn btn-gold px-4 py-3 d-inline-flex align-items-center gap-2 fw-bold fs-6">
                            <i class="fa-solid fa-phone"></i> +1 (800) 555-0199
                        </a>
                    @endif
                    <p class="mt-2 mb-0 small" style="color:rgba(255,255,255,.5);">Available 24/7 — No hold music</p>
                </div>
            </div>
        </div>
    </div>
</section>


@include('partials.frontend.mobile-cta')

@endsection

<style>
/* ── Stats ──────────────────────────────────── */
.about-stat-card {
    background: #fff;
    border: 1px solid rgba(7,17,31,.07);
    border-top: 3px solid #F59E0B;
    border-radius: 16px;
    padding: 28px 20px;
    box-shadow: 0 4px 16px rgba(7,17,31,.06);
    transition: transform .3s cubic-bezier(.165,.84,.44,1), box-shadow .3s;
}
.about-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 14px 36px rgba(7,17,31,.1);
}
.about-stat-value {
    display: block;
    font-family: 'JetBrains Mono', monospace;
    font-size: clamp(2rem, 4vw, 2.8rem);
    font-weight: 800;
    color: #07111F;
    line-height: 1;
    margin-bottom: 8px;
}
.about-stat-label {
    display: block;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: #64748B;
}

/* ── Story Visual ───────────────────────────── */
.about-story-visual {
    position: relative;
    background: linear-gradient(135deg, #07111F 0%, #0d1f38 100%);
    border-radius: 24px;
    padding: 36px 28px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(7,17,31,.2);
}
.about-story-orb {
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(245,158,11,.07);
    filter: blur(40px);
    pointer-events: none;
}
.about-story-icon-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 20px;
}
.about-story-icon-tile {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 14px;
    padding: 18px 14px;
    text-align: center;
    transition: background .2s ease;
}
.about-story-icon-tile:hover { background: rgba(255,255,255,.1); }
.about-story-icon-tile i {
    display: block;
    font-size: 1.4rem;
    color: rgba(255,255,255,.7);
    margin-bottom: 8px;
}
.about-story-icon-tile.gold i { color: #F59E0B; }
.about-story-icon-tile span {
    font-size: .72rem;
    font-weight: 700;
    color: rgba(255,255,255,.7);
    text-transform: uppercase;
    letter-spacing: .5px;
}
.about-story-badge {
    background: rgba(245,158,11,.12);
    border: 1px solid rgba(245,158,11,.3);
    color: #F59E0B;
    font-size: .78rem;
    font-weight: 700;
    text-align: center;
    padding: 10px 16px;
    border-radius: 100px;
    letter-spacing: .4px;
}
.about-prose { font-size: 1.02rem; line-height: 1.85; }

/* ── Trust pills ────────────────────────────── */
.about-trust-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 100px;
    padding: 10px 20px;
    font-size: .82rem;
    font-weight: 700;
    color: #07111F;
    width: 100%;
    box-shadow: 0 2px 8px rgba(7,17,31,.05);
    transition: box-shadow .2s ease, transform .2s ease;
}
.about-trust-pill:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(7,17,31,.08);
}

/* ── Mobile ──────────────────────────────────── */
@media (max-width: 767.98px) {
    .about-stat-card { padding: 20px 14px; }
    .about-stat-value { font-size: 1.8rem; }
    .about-trust-pill { padding: 8px 14px; font-size: .75rem; }
}
</style>
