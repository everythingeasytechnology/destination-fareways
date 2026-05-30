@extends('layouts.frontend')

@section('content')

{{-- ── HERO ── --}}
<section class="offers-hero-section bg-navy text-white mt-5 position-relative overflow-hidden">
    <div class="offers-hero-pattern"></div>
    <div class="offers-hero-orb orb-1"></div>
    <div class="offers-hero-orb orb-2"></div>

    <div class="container py-5 text-center position-relative" style="z-index:2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <i class="fa-solid fa-circle-question text-gold"></i>
            <span>Quick Answers — No Wait Time</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
            Frequently Asked Questions
        </h1>
        <p class="lead mb-4 text-muted-white" data-aos="fade-up" data-aos-delay="120">
            Find immediate answers about booking policies, ticket changes, luggage limits, and cancellations.
        </p>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="160">
            @include('partials.frontend.breadcrumb')
        </div>
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="220">
            <div class="offers-stat-pill"><i class="fa-solid fa-list-check text-gold me-2"></i>{{ $categories->count() * 3 }}+ Questions Answered</div>
            <div class="offers-stat-pill"><i class="fa-solid fa-headset text-gold me-2"></i>24/7 Live Support</div>
            <div class="offers-stat-pill"><i class="fa-solid fa-clock text-gold me-2"></i>Instant Answers</div>
        </div>
    </div>
</section>

{{-- ── CONTENT ── --}}
<section class="py-5 bg-softgray">
    <div class="container py-2">

        @if($categories->isEmpty())
            {{-- Empty state --}}
            <div class="text-center py-5 mx-auto" style="max-width:460px;" data-aos="fade-up">
                <div class="offers-empty-icon mb-4"><i class="fa-solid fa-circle-question"></i></div>
                <h3 class="h4 fw-bold text-navy mb-2">No FAQ Content Yet</h3>
                <p class="text-muted mb-4">We are updating our FAQ. For immediate help, call our 24/7 helpdesk.</p>
                <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}" class="btn btn-gold px-4 py-2 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-phone"></i> Call Now
                </a>
            </div>
        @else
            <div class="row g-5 align-items-start">

                {{-- ── LEFT: Info Sidebar ── --}}
                <div class="col-lg-4 d-none d-lg-block" data-aos="fade-up">
                    <div class="faq-sidebar">

                        {{-- Section title card --}}
                        <div class="faq-info-card mb-4">
                            <div class="faq-info-icon-wrap mb-3">
                                <i class="fa-solid fa-circle-question"></i>
                            </div>
                            <h3 class="display-font text-navy fw-bold mb-2" style="font-size:1.5rem;">
                                Got Questions?
                            </h3>
                            <p class="text-muted mb-0" style="font-size:.9rem;line-height:1.7;">
                                Browse categories to find instant answers. Can't find what you need? Our agents are available around the clock.
                            </p>

                            {{-- Category count pills --}}
                            <div class="mt-4 d-flex flex-wrap gap-2">
                                @foreach($categories as $cat)
                                    <span class="faq-cat-chip">{{ $cat }}</span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Call card --}}
                        <div class="offer-sidebar-card">
                            <div class="offer-sidebar-header">
                                <span class="offer-sidebar-badge">Still Need Help?</span>
                                <h4 class="text-white fw-bold mt-2 mb-1" style="font-family:'DM Sans',sans-serif;font-size:1rem;">
                                    Speak to a Travel Expert
                                </h4>
                                <p class="mb-0 small" style="color:rgba(255,255,255,.6);">
                                    Skip the queue — our agents answer in seconds.
                                </p>
                            </div>
                            <div class="offer-sidebar-body pt-3 pb-3">
                                @if(!empty($callSettings) && $callSettings->phone)
                                    <a href="tel:{{ $callSettings->phone }}"
                                       class="btn btn-gold w-100 py-3 fw-bold font-monospace d-flex align-items-center justify-content-center gap-2 mb-3">
                                        <i class="fa-solid fa-phone"></i> {{ $callSettings->phone }}
                                    </a>
                                @else
                                    <a href="tel:+18005550199"
                                       class="btn btn-gold w-100 py-3 fw-bold font-monospace d-flex align-items-center justify-content-center gap-2 mb-3">
                                        <i class="fa-solid fa-phone"></i> +1 (800) 555-0199
                                    </a>
                                @endif
                                <a href="{{ route('contact') }}"
                                   class="btn btn-outline-navy w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-envelope text-gold"></i> Send a Message
                                </a>
                            </div>
                            <div class="offer-sidebar-trust">
                                <div class="offer-sidebar-trust-item"><i class="fa-solid fa-headset text-gold"></i><span>24/7</span></div>
                                <div class="offer-sidebar-trust-item"><i class="fa-solid fa-clock text-gold"></i><span>Instant</span></div>
                                <div class="offer-sidebar-trust-item"><i class="fa-solid fa-shield-halved text-gold"></i><span>Secure</span></div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── RIGHT: Category Tabs + Accordion ── --}}
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="80">

                    {{-- Category filter tabs --}}
                    <div class="offers-filter-scroll mb-4">
                        <div class="d-flex gap-2 flex-nowrap faq-tab-pills" style="min-width:max-content;">
                            @foreach($categories as $category)
                                <button class="faq-tab-pill {{ $loop->first ? 'active' : '' }}"
                                        id="pill-{{ Str::slug($category) }}-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pane-{{ Str::slug($category) }}"
                                        type="button" role="tab"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        onclick="toggleCategoryBtn(this)">
                                    {{ $category }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Accordion Panels --}}
                    <div class="tab-content" id="faqTabContent">
                        @foreach($groupedFaqs as $cat => $faqs)
                            <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}"
                                 id="pane-{{ Str::slug($cat) }}"
                                 role="tabpanel"
                                 aria-labelledby="pill-{{ Str::slug($cat) }}-tab">

                                <div class="accordion custom-accordion" id="accordion-{{ Str::slug($cat) }}">
                                    @foreach($faqs as $faq)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-{{ $faq->id }}">
                                                <button class="accordion-button collapsed"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $faq->id }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse-{{ $faq->id }}">
                                                    {{ $faq->question }}
                                                </button>
                                            </h2>
                                            <div id="collapse-{{ $faq->id }}"
                                                 class="accordion-collapse collapse"
                                                 aria-labelledby="heading-{{ $faq->id }}"
                                                 data-bs-parent="#accordion-{{ Str::slug($cat) }}">
                                                <div class="accordion-body">
                                                    {!! $faq->answer !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach
                    </div>

                    {{-- Mobile call card (below accordion on mobile) --}}
                    <div class="d-lg-none mt-5" data-aos="fade-up">
                        <div class="ct-call-cta">
                            <div>
                                <span class="d-block fw-bold text-navy" style="font-size:.9rem;">Still Have Questions?</span>
                                <span class="text-muted" style="font-size:.8rem;">Our agents are available 24/7.</span>
                            </div>
                            <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
                               class="btn btn-gold px-4 py-2 fw-bold font-monospace d-flex align-items-center gap-2 flex-shrink-0">
                                <i class="fa-solid fa-phone"></i> Call Now
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
</section>

{{-- ── CTA Strip ── --}}
<section class="py-5 bg-white border-top border-light">
    <div class="container">
        <div class="offers-cta-card" data-aos="fade-up">
            <div class="offers-cta-glow"></div>
            <div class="row align-items-center g-4 position-relative" style="z-index:2;">
                <div class="col-12 col-lg-7">
                    <span class="badge bg-gold text-navy px-3 py-1 mb-2 fw-bold" style="font-size:.7rem;letter-spacing:1px;">EXCLUSIVE ACCESS</span>
                    <h3 class="h2 fw-bold text-white mb-2">Couldn't Find Your Answer?</h3>
                    <p class="mb-0" style="color:rgba(255,255,255,.72);">
                        Our wholesale travel experts can answer any question and unlock private fares not available online.
                    </p>
                </div>
                <div class="col-12 col-lg-5 text-center text-lg-end">
                    <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
                       class="btn btn-gold px-4 py-3 d-inline-flex align-items-center gap-2 fw-bold fs-6">
                        <i class="fa-solid fa-phone"></i> Call Reservations Now
                    </a>
                    <p class="mt-2 mb-0 small" style="color:rgba(255,255,255,.5);">Available 24/7 — No hold music</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    function toggleCategoryBtn(btn) {
        const container = btn.closest('.faq-tab-pills');
        container.querySelectorAll('button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
</script>

<style>
/* ── Sidebar ─────────────────────────────────────── */
.faq-sidebar { position: sticky; top: 100px; }

.faq-info-card {
    background: #fff;
    border: 1px solid rgba(7,17,31,.07);
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(7,17,31,.06);
}
.faq-info-icon-wrap {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #07111F, #0d1f38);
    color: #F59E0B;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    box-shadow: 0 4px 14px rgba(7,17,31,.2);
}
.faq-cat-chip {
    display: inline-block;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 100px;
    padding: 4px 12px;
    font-size: .72rem;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: .4px;
}

/* ── Category Tab Pills ──────────────────────────── */
.offers-filter-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 4px;
}
.faq-tab-pills { display: flex; gap: 8px; padding: 2px 0; }
.faq-tab-pill {
    display: inline-flex; align-items: center;
    background: #fff;
    border: 1.5px solid #E2E8F0;
    border-radius: 100px;
    padding: 9px 20px;
    font-size: .82rem;
    font-weight: 700;
    color: #64748B;
    cursor: pointer;
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: .4px;
    transition: all .22s ease;
    box-shadow: 0 2px 8px rgba(7,17,31,.05);
}
.faq-tab-pill:hover { color: #07111F; border-color: rgba(7,17,31,.2); background: #F8FAFC; }
.faq-tab-pill.active {
    background: #07111F;
    border-color: #07111F;
    color: #fff;
    box-shadow: 0 4px 16px rgba(7,17,31,.2);
}

/* ── ct-call-cta (reuse from contact page) ──────── */
.ct-call-cta {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    box-shadow: 0 4px 16px rgba(7,17,31,.06);
    flex-wrap: wrap;
}

/* ── Mobile ──────────────────────────────────────── */
@media (max-width: 767.98px) {
    .faq-tab-pill { padding: 8px 14px; font-size: .75rem; }
    .ct-call-cta { flex-direction: column; text-align: center; }
    .ct-call-cta .btn { width: 100%; justify-content: center; }
}
</style>
@endsection
