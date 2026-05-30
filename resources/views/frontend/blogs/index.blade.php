@extends('layouts.frontend')

@section('content')
@php
    $blogImageUrl = function ($path, $fallback) {
        if (empty($path)) return $fallback;
        return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    };
    $showFeaturedBlog = !empty($featuredBlog)
        && !request('q')
        && !request('category')
        && (!request('page') || request('page') == 1)
        && $blogs->total() > 3;
@endphp

{{-- ── HERO (mirrors offers/destinations hero) ── --}}
<section class="offers-hero-section bg-navy text-white mt-5 position-relative overflow-hidden">
    <div class="offers-hero-pattern"></div>
    <div class="offers-hero-orb orb-1"></div>
    <div class="offers-hero-orb orb-2"></div>

    <div class="container py-5 text-center position-relative" style="z-index:2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <span class="live-dot"></span>
            <span>Fresh Articles — Updated Weekly</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
            Travel Tips &amp; Guides
        </h1>
        <p class="lead mb-4 text-muted-white" data-aos="fade-up" data-aos-delay="120">
            Discover cheap flight secrets, expert itineraries, and smart travel packing tips.
        </p>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="160">
            @include('partials.frontend.breadcrumb')
        </div>
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="220">
            <div class="offers-stat-pill">
                <i class="fa-solid fa-newspaper text-gold me-2"></i>{{ $blogs->total() }}+ Articles
            </div>
            <div class="offers-stat-pill">
                <i class="fa-solid fa-pen-nib text-gold me-2"></i>Expert Travel Writers
            </div>
            <div class="offers-stat-pill">
                <i class="fa-solid fa-compass text-gold me-2"></i>Tips, Guides &amp; Reviews
            </div>
        </div>
    </div>
</section>

{{-- ── FILTER + GRID ── --}}
<section class="py-5 bg-softgray offers-main-section">
    <div class="container">

        {{-- Filter tabs + Search ──────────────────────────────── --}}
        <div class="row mb-5 justify-content-center align-items-center g-3" data-aos="fade-up">
            {{-- Category tabs --}}
            <div class="col-12 col-lg-auto">
                <div class="offers-filter-scroll">
                    <ul class="nav border-0 justify-content-center custom-search-tabs offers-filter-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('category') ? 'active' : '' }}"
                               href="{{ route('blog.index', request('q') ? ['q' => request('q')] : []) }}">
                                <i class="fa-solid fa-border-all me-1"></i> All Posts
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li class="nav-item">
                                <a class="nav-link {{ request('category') === $category ? 'active' : '' }}"
                                   href="{{ route('blog.index', array_filter(['category' => $category, 'q' => request('q')])) }}">
                                    {{ $category }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Search --}}
            <div class="col-12 col-lg-auto">
                <form action="{{ route('blog.index') }}" method="GET" class="blog-search-form">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="blog-search-wrap">
                        <i class="fa-solid fa-magnifying-glass blog-search-icon"></i>
                        <input type="text" name="q" class="blog-search-input"
                               placeholder="Search articles..." value="{{ request('q') }}">
                        <button type="submit" class="blog-search-btn">Search</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Featured Blog ──────────────────────────────────────── --}}
        @if($showFeaturedBlog)
            <div class="mb-5" data-aos="fade-up">
                <div class="blog-featured-card">
                    <div class="row g-0">
                        {{-- Image --}}
                        <div class="col-lg-6 blog-featured-img-wrap">
                            <img src="{{ $blogImageUrl($featuredBlog->featured_image, 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1000&q=80&auto=format') }}"
                                 alt="{{ $featuredBlog->title }}" loading="lazy">
                            <span class="blog-featured-badge">
                                <i class="fa-solid fa-star me-1"></i>Featured
                            </span>
                        </div>
                        {{-- Content --}}
                        <div class="col-lg-6 blog-featured-body">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="offer-airline-tag">
                                    <i class="fa-solid fa-tag text-gold"></i>
                                    {{ $featuredBlog->category ?? 'Travel Guide' }}
                                </span>
                                @if(!empty($featuredBlog->read_time))
                                    <span class="text-muted small">
                                        <i class="fa-regular fa-clock text-gold me-1"></i>{{ $featuredBlog->read_time }} mins
                                    </span>
                                @endif
                            </div>
                            <h2 class="display-font text-navy fw-bold mb-3" style="font-size:clamp(1.4rem,3vw,1.9rem);line-height:1.25;">
                                <a href="{{ route('blog.show', $featuredBlog->slug) }}" class="text-navy text-decoration-none blog-title-link">
                                    {{ $featuredBlog->title }}
                                </a>
                            </h2>
                            <p class="text-muted mb-4" style="font-size:.95rem;line-height:1.7;">
                                {{ Str::limit($featuredBlog->excerpt, 180) }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-auto border-top pt-4">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $blogImageUrl($featuredBlog->author_image ?? null, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop') }}"
                                         alt="{{ $featuredBlog->author_name }}"
                                         class="rounded-circle" style="width:38px;height:38px;object-fit:cover;border:2px solid #E2E8F0;">
                                    <div>
                                        <span class="d-block fw-bold text-navy" style="font-size:.85rem;">{{ $featuredBlog->author_name ?? 'Editorial Staff' }}</span>
                                        <span class="text-muted" style="font-size:.75rem;">{{ $featuredBlog->published_at ? $featuredBlog->published_at->format('M d, Y') : 'Recent' }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $featuredBlog->slug) }}" class="btn btn-navy px-4 py-2 d-flex align-items-center gap-2">
                                    Read Article <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mt-5 mb-2">
                    <h3 class="display-font text-navy fw-bold mb-0" style="font-size:1.5rem;">Latest Articles</h3>
                    <div class="flex-grow-1" style="height:1px;background:#E2E8F0;"></div>
                </div>
            </div>
        @endif

        {{-- Blog Grid ──────────────────────────────────────────── --}}
        @if($blogs->isEmpty())
            <div class="text-center py-5 mx-auto" style="max-width:460px;" data-aos="fade-up">
                <div class="offers-empty-icon mb-4"><i class="fa-regular fa-newspaper"></i></div>
                <h3 class="h4 fw-bold text-navy mb-2">No Articles Found</h3>
                <p class="text-muted mb-4">We couldn't find articles matching your search. Try clearing the filters.</p>
                <a href="{{ route('blog.index') }}" class="btn btn-gold px-4 py-2 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Clear Filters
                </a>
            </div>
        @else
            <div class="row g-4 mb-5">
                @foreach($blogs as $blog)
                    @if(!$showFeaturedBlog || $blog->id !== $featuredBlog->id || request('q') || request('category') || (request('page') && request('page') > 1))
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="offer-premium-card d-flex flex-column h-100 text-decoration-none">

                                {{-- Image Zone --}}
                                <div class="offer-card-img-wrap">
                                    <img src="{{ $blogImageUrl($blog->featured_image, 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&q=80&auto=format') }}"
                                         alt="{{ $blog->title }}" loading="lazy" class="offer-card-img">
                                    <div class="offer-card-img-gradient"></div>

                                    {{-- Category badge --}}
                                    <div class="offer-card-top-badges">
                                        <span class="offer-badge-save">{{ $blog->category ?? 'Travel' }}</span>
                                    </div>

                                    {{-- Read time strip --}}
                                    @if(!empty($blog->read_time))
                                        <div class="offer-card-route-strip">
                                            <i class="fa-regular fa-clock text-gold me-1"></i>
                                            <span class="fw-bold">{{ $blog->read_time }} mins read</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Body Zone --}}
                                <div class="offer-card-body-premium flex-grow-1 d-flex flex-column">
                                    {{-- Author tag --}}
                                    <div class="offer-airline-tag mb-2">
                                        <i class="fa-solid fa-pen-nib text-gold"></i>
                                        <span>{{ $blog->author_name ?? 'Editorial Staff' }}</span>
                                    </div>

                                    <h3 class="offer-card-title-premium mb-1">
                                        {{ Str::limit($blog->title, 65) }}
                                    </h3>

                                    <p class="offer-card-desc-premium flex-grow-1 mb-3">
                                        {{ Str::limit($blog->excerpt, 100) }}
                                    </p>

                                    {{-- Date row --}}
                                    <div class="offer-price-row-premium">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $blogImageUrl($blog->author_image ?? null, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop') }}"
                                                 alt="{{ $blog->author_name }}"
                                                 class="rounded-circle" style="width:28px;height:28px;object-fit:cover;border:1.5px solid #E2E8F0;">
                                            <span class="text-muted" style="font-size:.78rem;">
                                                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recent' }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- CTA row --}}
                                    <div class="offer-card-cta-row mt-3">
                                        <span class="offer-cta-label">Read Article</span>
                                        <span class="offer-cta-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($blogs->hasPages())
                <div class="d-flex justify-content-center" data-aos="fade-up">
                    {{ $blogs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif

    </div>
</section>

{{-- ── CTA Strip (identical to offers/destinations) ── --}}
<section class="py-5 bg-white border-top border-light">
    <div class="container">
        <div class="offers-cta-card" data-aos="fade-up">
            <div class="offers-cta-glow"></div>
            <div class="row align-items-center g-4 position-relative" style="z-index:2;">
                <div class="col-12 col-lg-7">
                    <span class="badge bg-gold text-navy px-3 py-1 mb-2 fw-bold" style="font-size:.7rem;letter-spacing:1px;">EXCLUSIVE ACCESS</span>
                    <h3 class="h2 fw-bold text-white mb-2">Ready to Book Your Next Trip?</h3>
                    <p class="mb-0" style="color:rgba(255,255,255,.72);">
                        Our wholesale contracts unlock private fares up to 40% below online prices. One call is all it takes.
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

<style>
/* ── Search Bar ─────────────────────────────────── */
.blog-search-wrap {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1.5px solid #E2E8F0;
    border-radius: 100px;
    overflow: hidden;
    padding: 4px 4px 4px 16px;
    box-shadow: 0 2px 12px rgba(7,17,31,.06);
    transition: border-color .2s ease, box-shadow .2s ease;
    min-width: 260px;
}
.blog-search-wrap:focus-within {
    border-color: #F59E0B;
    box-shadow: 0 0 0 3px rgba(245,158,11,.1);
}
.blog-search-icon { color: #94a3b8; font-size: .9rem; margin-right: 8px; flex-shrink: 0; }
.blog-search-input {
    flex: 1; border: none; outline: none; background: transparent;
    font-family: 'DM Sans', sans-serif; font-size: .88rem; color: #07111F;
}
.blog-search-input::placeholder { color: #94a3b8; }
.blog-search-btn {
    background: #07111F; color: #fff;
    border: none; border-radius: 100px;
    padding: 8px 18px; font-size: .8rem;
    font-weight: 700; cursor: pointer;
    transition: background .2s ease;
    white-space: nowrap;
}
.blog-search-btn:hover { background: #F59E0B; color: #07111F; }

/* ── Featured Card ──────────────────────────────── */
.blog-featured-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(7,17,31,.07);
    box-shadow: 0 8px 32px rgba(7,17,31,.09);
    overflow: hidden;
}
.blog-featured-img-wrap {
    position: relative;
    min-height: 360px;
    overflow: hidden;
}
.blog-featured-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: center;
    transition: transform .6s cubic-bezier(.165,.84,.44,1);
}
.blog-featured-card:hover .blog-featured-img-wrap img { transform: scale(1.04); }
.blog-featured-badge {
    position: absolute; top: 16px; left: 16px;
    background: #F59E0B; color: #07111F;
    font-size: .72rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .6px;
    padding: 5px 14px; border-radius: 8px;
    box-shadow: 0 4px 12px rgba(245,158,11,.35);
}
.blog-featured-body {
    padding: 36px 40px;
    display: flex; flex-direction: column; justify-content: center;
    background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
}
.blog-title-link:hover { color: #F59E0B !important; }

/* ── Mobile ──────────────────────────────────────── */
@media (max-width: 991.98px) {
    .blog-featured-img-wrap { min-height: 280px; }
    .blog-featured-body { padding: 28px; }
}
@media (max-width: 767.98px) {
    .blog-featured-img-wrap { min-height: 220px; }
    .blog-featured-body { padding: 20px; }
    .blog-search-wrap { min-width: 100%; }
}
@media (max-width: 575.98px) {
    .offers-filter-scroll { justify-content: flex-start; }
    .offers-filter-tabs .nav-link { padding: 8px 14px !important; font-size: .78rem !important; }
}
</style>
