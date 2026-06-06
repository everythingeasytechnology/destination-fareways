@extends('layouts.frontend')

@section('content')
@php
    $blogImageUrl = function ($path, $fallback) {
        if (empty($path)) return $fallback;
        return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    };
    $heroImg = $blogImageUrl($blog->banner_image ?? $blog->featured_image, 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1600&q=80&auto=format');
@endphp

{{-- ── HERO (mirrors offer-detail-hero) ── --}}
<section class="offer-detail-hero mt-5 position-relative overflow-hidden" style="min-height:360px;">
    <div class="offer-detail-hero-bg" style="background-image:url('{{ $heroImg }}');"></div>
    <div class="offer-detail-hero-overlay"></div>

    <div class="container position-relative py-5" style="z-index:2; padding-top:80px !important;">
        <div class="row justify-content-center text-center pt-3">
            <div class="col-lg-9">
                <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
                    <i class="fa-solid fa-pen-nib text-gold"></i>
                    <span>{{ $blog->category ?? 'Travel Guide' }}</span>
                </div>
                <h1 class="display-font fw-bold text-white mb-3" style="font-size:clamp(1.6rem,4vw,2.5rem);line-height:1.2;" data-aos="fade-up" data-aos-delay="60">
                    {{ $blog->title }}
                </h1>
                @if(!empty($blog->subtitle))
                    <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
                        {{ $blog->subtitle }}
                    </p>
                @endif
                <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="140">
                    @include('partials.frontend.breadcrumb')
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CONTENT BODY ── --}}
<section class="py-5 bg-softgray">
    <div class="container py-2">
        <div class="row g-5">

            {{-- ── LEFT: Article ── --}}
            <div class="col-lg-8">

                {{-- Article Meta Card --}}
                <div class="blog-meta-card mb-4" data-aos="fade-up">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $blogImageUrl($blog->author_image ?? null, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop') }}"
                                 alt="{{ $blog->author_name }}"
                                 class="rounded-circle" style="width:48px;height:48px;object-fit:cover;border:2px solid rgba(245,158,11,.3);">
                            <div>
                                <span class="d-block fw-bold text-navy" style="font-size:.9rem;">{{ $blog->author_name ?? 'Editorial Staff' }}</span>
                                <span class="text-muted" style="font-size:.75rem;">Travel Concierge</span>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <span class="blog-meta-pill">
                                <i class="fa-regular fa-calendar text-gold"></i>
                                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recent' }}
                            </span>
                            @if(!empty($blog->read_time))
                                <span class="blog-meta-pill">
                                    <i class="fa-regular fa-clock text-gold"></i>
                                    {{ $blog->read_time }} min read
                                </span>
                            @endif
                            <span class="blog-meta-pill">
                                <i class="fa-regular fa-eye text-gold"></i>
                                {{ number_format($blog->views ?? 0) }} views
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Banner Image --}}
                <div class="blog-banner-wrap mb-5" data-aos="fade-up">
                    <img src="{{ $heroImg }}" alt="{{ $blog->title }}" loading="eager">
                </div>

                {{-- Prose Content --}}
                <div class="prose-content mb-5" data-aos="fade-up">
                    {!! $blog->content !!}
                </div>

                {{-- Tags + Share --}}
                <div class="blog-tags-share" data-aos="fade-up">
                    {{-- Tags --}}
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing:.5px;">Tags:</span>
                        @if(!empty($blog->tags))
                            @foreach(explode(',', $blog->tags) as $tag)
                                <span class="blog-tag">#{{ trim($tag) }}</span>
                            @endforeach
                        @else
                            <span class="blog-tag">#CheapFlights</span>
                            <span class="blog-tag">#TravelHacks</span>
                        @endif
                    </div>

                    {{-- Share --}}
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing:.5px;">Share:</span>
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($blog->title) }}"
                           class="blog-share-btn" target="_blank" title="Share on X">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                           class="blog-share-btn" target="_blank" title="Share on Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . url()->current()) }}"
                           class="blog-share-btn" target="_blank" title="Share on WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ urlencode($blog->title) }}"
                           class="blog-share-btn" target="_blank" title="Share on LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Sticky Sidebar (exact offer-sidebar-card) ── --}}
            <div class="col-lg-4">
                <div class="offer-sidebar-sticky">

                    <div class="offer-sidebar-card mb-4" data-aos="fade-up">

                        {{-- Header --}}
                        <div class="offer-sidebar-header">
                            <span class="offer-sidebar-badge">Exclusive Unpublished Deals</span>
                            <h4 class="text-white fw-bold mb-1 mt-2" style="font-family:'DM Sans',sans-serif;font-size:1.05rem;">
                                Book Cheap Flights
                            </h4>
                            <p class="mb-0 small" style="color:rgba(255,255,255,.6);">
                                Let our team build the perfect itinerary at wholesale fares.
                            </p>
                        </div>

                        {{-- Form --}}
                        <div class="offer-sidebar-body">
                            <form action="{{ route('booking.submit') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="trip_type" value="one_way">
                                <input type="hidden" name="cabin_class" value="economy">
                                <input type="hidden" name="adults" value="1">

                                <h4 class="h6 fw-bold text-navy mb-3 text-uppercase" style="letter-spacing:.6px;">Quick Booking Enquiry</h4>

                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Departure Airport</label>
                                    <input type="text" name="from_airport" class="form-control offer-form-input" placeholder="e.g. JFK or New York" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Destination Airport</label>
                                    <input type="text" name="to_airport" class="form-control offer-form-input" placeholder="e.g. LAX or Los Angeles" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Full Name</label>
                                    <input type="text" name="name" class="form-control offer-form-input" placeholder="John Doe" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Email Address</label>
                                    <input type="email" name="email" class="form-control offer-form-input" placeholder="john@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control offer-form-input" placeholder="+1 (555) 000-0000" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small text-muted mb-1">Preferred Travel Date</label>
                                    <input type="date" name="departure_date" class="form-control offer-form-input" required min="{{ date('Y-m-d') }}">
                                </div>

                                <button type="submit" class="btn btn-gold w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <i class="fa-solid fa-paper-plane"></i> Submit Enquiry
                                </button>
                            </form>

                            <div class="offer-sidebar-divider"><span>or call us directly</span></div>

                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}"
                                   class="btn btn-outline-navy w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2 font-monospace mt-3">
                                    <i class="fa-solid fa-phone text-gold"></i> {{ $callSettings->phone }}
                                </a>
                            @else
                                <a href="tel:+18005550199"
                                   class="btn btn-outline-navy w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2 font-monospace mt-3">
                                    <i class="fa-solid fa-phone text-gold"></i> +1 (800) 555-0199
                                </a>
                            @endif
                        </div>

                        {{-- Trust badges --}}
                        <div class="offer-sidebar-trust">
                            <div class="offer-sidebar-trust-item"><i class="fa-solid fa-shield-halved text-gold"></i><span>Secure</span></div>
                            <div class="offer-sidebar-trust-item"><i class="fa-solid fa-lock text-gold"></i><span>Price Match</span></div>
                            <div class="offer-sidebar-trust-item"><i class="fa-solid fa-headset text-gold"></i><span>24/7</span></div>
                        </div>
                    </div>

                </div>

                {{-- Popular Destinations widget --}}
                @if(!empty($popularDestinations) && $popularDestinations->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden mt-4" data-aos="fade-up">
                    <div class="card-header bg-navy text-white py-3 px-4" style="background:#07111F !important;">
                        <h5 class="mb-0 fw-bold" style="font-size:.95rem;letter-spacing:.4px;">
                            <i class="fa-solid fa-location-dot text-gold me-2"></i> Popular Destinations
                        </h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($popularDestinations as $dest)
                        <li class="list-group-item px-4 py-3">
                            <a href="{{ route('destinations.show', $dest->slug) }}"
                               class="d-flex justify-content-between align-items-center text-decoration-none text-navy">
                                <span class="fw-semibold" style="font-size:.88rem;">{{ $dest->name }}</span>
                                <span class="text-gold fw-bold font-monospace" style="font-size:.82rem;">
                                    from ${{ number_format($dest->starting_price, 0) }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="card-footer bg-white border-top px-4 py-3">
                        <a href="{{ route('destinations.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            View All Destinations <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- ── Related Articles ── --}}
        @if($relatedBlogs->isNotEmpty())
            <div class="mt-5 pt-5 border-top border-light">
                <div class="d-flex align-items-center gap-3 mb-4" data-aos="fade-up">
                    <h3 class="display-font text-navy fw-bold mb-0" style="font-size:1.5rem;">Related Travel Guides</h3>
                    <div class="flex-grow-1" style="height:1px;background:#E2E8F0;"></div>
                </div>

                <div class="row g-4">
                    @foreach($relatedBlogs as $related)
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <a href="{{ route('blog.show', $related->slug) }}" class="offer-premium-card d-flex flex-column h-100 text-decoration-none">
                                <div class="offer-card-img-wrap">
                                    <img src="{{ $blogImageUrl($related->featured_image, 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&q=80&auto=format') }}"
                                         alt="{{ $related->title }}" class="offer-card-img" loading="lazy">
                                    <div class="offer-card-img-gradient"></div>
                                    <div class="offer-card-top-badges">
                                        <span class="offer-badge-save">{{ $related->category ?? 'Travel' }}</span>
                                    </div>
                                    @if(!empty($related->read_time))
                                        <div class="offer-card-route-strip">
                                            <i class="fa-regular fa-clock text-gold me-1"></i>
                                            <span class="fw-bold">{{ $related->read_time }} mins read</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="offer-card-body-premium flex-grow-1 d-flex flex-column">
                                    <div class="offer-airline-tag mb-2">
                                        <i class="fa-solid fa-pen-nib text-gold"></i>
                                        <span>{{ $related->author_name ?? 'Editorial Staff' }}</span>
                                    </div>
                                    <h4 class="offer-card-title-premium mb-1">{{ Str::limit($related->title, 60) }}</h4>
                                    <p class="offer-card-desc-premium flex-grow-1 mb-3">{{ Str::limit($related->excerpt, 90) }}</p>
                                    <div class="offer-card-cta-row mt-3">
                                        <span class="offer-cta-label">Read Article</span>
                                        <span class="offer-cta-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

{{-- ── Mobile Sticky CTA ── --}}
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
        <a href="#"
           onclick="document.querySelector('.offer-sidebar-card').scrollIntoView({behavior:'smooth'});return false;"
           class="btn btn-outline-light fw-semibold d-flex align-items-center gap-1 offer-enquire-btn offer-cta-btn">
            Enquire
        </a>
    </div>
</div>

@endsection

@section('scripts')
<script>
    (() => {
        'use strict';
        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', e => {
                if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<style>
/* ── Meta Card ─────────────────────────────────── */
.blog-meta-card {
    background: #fff;
    border: 1px solid rgba(7,17,31,.07);
    border-radius: 14px;
    padding: 16px 20px;
    box-shadow: 0 2px 12px rgba(7,17,31,.05);
}
.blog-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 100px;
    padding: 4px 12px;
    font-size: .78rem;
    font-weight: 600;
    color: #64748B;
}

/* ── Banner Image ──────────────────────────────── */
.blog-banner-wrap {
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(7,17,31,.12);
}
.blog-banner-wrap img {
    width: 100%;
    max-height: 460px;
    object-fit: cover;
    display: block;
}

/* ── Tags + Share ──────────────────────────────── */
.blog-tags-share {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 0;
    border-top: 1px solid #E2E8F0;
    border-bottom: 1px solid #E2E8F0;
    margin-bottom: 8px;
}
.blog-tag {
    display: inline-block;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 100px;
    padding: 4px 14px;
    font-size: .78rem;
    font-weight: 700;
    color: #07111F;
    transition: all .2s ease;
}
.blog-tag:hover {
    background: #07111F;
    border-color: #07111F;
    color: #fff;
}
.blog-share-btn {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: #07111F;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    text-decoration: none;
    transition: background .2s ease, transform .2s ease;
    border: none;
}
.blog-share-btn:hover {
    background: #F59E0B;
    color: #07111F;
    transform: translateY(-2px);
}

/* ── Sidebar sticky ────────────────────────────── */
@media (min-width:992px) {
    .offer-sidebar-sticky { position: sticky; top: 100px; }
}
@media (max-width:991.98px) {
    .offer-sidebar-sticky { position: static !important; }
    body:has(.offer-mobile-cta) main { padding-bottom: 80px; }
}

/* ── Mobile ────────────────────────────────────── */
@media (max-width:767.98px) {
    .blog-meta-card { padding: 14px; }
    .blog-meta-pill { font-size: .72rem; padding: 3px 10px; }
    .blog-banner-wrap img { max-height: 260px; }
    .blog-tags-share { gap: 12px; }
}
@media (max-width:575.98px) {
    .blog-banner-wrap img { max-height: 220px; }
}
</style>
@endsection
