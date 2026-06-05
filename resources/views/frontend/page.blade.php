@extends('layouts.frontend')

@section('content')
@php
    $heroImageUrl = $page->banner_image
        ? (\Illuminate\Support\Str::startsWith($page->banner_image, ['http://', 'https://'])
            ? $page->banner_image
            : asset('storage/' . ltrim($page->banner_image, '/')))
        : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80';
@endphp
<section class="page-hero-pro text-white p-0 position-relative overflow-hidden" style="background-image: url('{{ $heroImageUrl }}'); background-size: cover; background-position: center;">

    {{-- Dark gradient overlay --}}
    <div class="page-hero-overlay"></div>

    {{-- Decorative orbs --}}
    <div class="page-hero-orb orb-gold"></div>
    <div class="page-hero-orb orb-blue"></div>

    <div class="container position-relative" style="z-index:3;">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11 text-center">

                {{-- Breadcrumb (top) --}}
                @if($page->show_breadcrumb)
                <div class="d-flex justify-content-center mb-4 pt-2" data-aos="fade-down">
                    @include('partials.frontend.breadcrumb')
                </div>
                @endif

                {{-- Route chip --}}
                @if(isset($searchDefaults) && $searchDefaults)
                <div class="page-hero-route-chip mb-4" data-aos="fade-up">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>{{ $searchDefaults['from'] }}</span>
                    <span class="chip-plane"><i class="fa-solid fa-plane"></i></span>
                    <span>{{ $searchDefaults['to'] }}</span>
                </div>
                @endif

                {{-- Main title --}}
                <h1 class="page-hero-title mb-0" data-aos="fade-up" data-aos-delay="80">
                    {{ $page->title }}
                </h1>

                {{-- Gold divider --}}
                <div class="page-hero-divider mx-auto my-4" data-aos="fade-up" data-aos-delay="140"></div>

                {{-- Subtitle --}}
                @if($page->subtitle)
                <p class="page-hero-subtitle mb-5" data-aos="fade-up" data-aos-delay="160">
                    {{ $page->subtitle }}
                </p>
                @elseif(!empty($routeTagline))
                <p class="page-hero-subtitle mb-5" data-aos="fade-up" data-aos-delay="160">
                    {{ $routeTagline }}
                </p>
                @else
                <div class="mb-5"></div>
                @endif

                {{-- Search form --}}
                @if(isset($searchDefaults) && $searchDefaults)
                <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="220">
                    <div class="col-xl-11 col-lg-12">
                        @include('partials.frontend.flight-search-form', ['showTripTabs' => false])
                    </div>
                </div>

                {{-- Trust badges --}}
                <div class="page-hero-trust-row mt-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="page-hero-trust-item">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Secure Booking</span>
                    </div>
                    <div class="page-hero-trust-sep"></div>
                    <div class="page-hero-trust-item">
                        <i class="fa-solid fa-headset"></i>
                        <span>24/7 Support</span>
                    </div>
                    <div class="page-hero-trust-sep"></div>
                    <div class="page-hero-trust-item">
                        <i class="fa-solid fa-tag"></i>
                        <span>Best Price Guarantee</span>
                    </div>
                    <div class="page-hero-trust-sep"></div>
                    <div class="page-hero-trust-item">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Flexible Changes</span>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

@if(!empty($routePreviewFlights))
<section class="py-5 bg-softgray">
    <div class="container py-4">
        <div class="row align-items-center gy-3 mb-4">
            <div class="col-lg-7">
                <span class="route-deals-badge mb-2 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-bolt-lightning"></i>
                    Flight deals from {{ $searchDefaults['from'] }} to {{ $searchDefaults['to'] }}
                </span>
                <h2 class="h3 fw-bold mb-2">Looking for a low fare on this route?</h2>
                <p class="text-muted mb-0">Find selected airline deals and quick book options for your departure city and destination.</p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                    <button type="button" class="route-filter-pill active">Show all</button>
                    <button type="button" class="route-filter-pill">Return</button>
                    <button type="button" class="route-filter-pill">One way</button>
                    <button type="button" class="route-filter-pill">Direct</button>
                    <button type="button" class="route-filter-pill">Latest deals</button>
                </div>
            </div>
        </div>
        <div class="row g-4">
            @foreach($routePreviewFlights as $flight)
                <div class="col-sm-6 col-xl-4">
                    <div class="route-deal-card-premium h-100">
                        <div class="d-flex align-items-start justify-content-between mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="route-deal-logo-premium d-flex align-items-center justify-content-center">
                                    <img src="{{ $flight['logo'] }}" alt="{{ $flight['airline_name'] }}" style="max-width:38px;max-height:38px;object-fit:contain;">
                                </div>
                                <div>
                                    <div class="fw-bold text-navy" style="font-size:.95rem;">{{ $flight['airline_name'] }}</div>
                                    <div class="text-muted" style="font-size:.78rem;">{{ $flight['stops'] === 0 ? 'Non-stop flight' : ($flight['stops'] . ' stop' . ($flight['stops'] > 1 ? 's' : '')) }}</div>
                                </div>
                            </div>
                            <span class="route-stops-badge {{ $flight['stops'] === 0 ? 'direct' : '' }}">
                                {{ $flight['stops'] === 0 ? 'Direct' : ($flight['stops'] . ' stop' . ($flight['stops'] > 1 ? 's' : '')) }}
                            </span>
                        </div>

                        <div class="route-path-visual mb-4">
                            <div class="route-point">
                                <div class="route-code">{{ strtoupper(substr($searchDefaults['from'], 0, 3)) }}</div>
                                <div class="route-city">{{ $searchDefaults['from'] }}</div>
                            </div>
                            <div class="route-line-wrapper">
                                <div class="route-line">
                                    <i class="fa-solid fa-plane route-plane-icon"></i>
                                </div>
                            </div>
                            <div class="route-point text-end">
                                <div class="route-code">{{ strtoupper(substr($searchDefaults['to'], 0, 3)) }}</div>
                                <div class="route-city">{{ $searchDefaults['to'] }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-end justify-content-between pt-3 border-top">
                            <div>
                                <div class="text-muted mb-1" style="font-size:.75rem;letter-spacing:.5px;text-transform:uppercase;">Starting from</div>
                                <div class="route-deal-price">${{ $flight['price'] }}</div>
                                <div class="text-muted" style="font-size:.75rem;">per person</div>
                            </div>
                            <a href="{{ $flight['book_url'] }}" class="btn-book-now">
                                Book now <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9" data-aos="fade-up">
                <div class="prose-content">
                    {!! $page->content !!}
                </div>

                @if($page->seo_content)
                    <div class="prose-content mt-5 pt-4 border-top border-light text-muted">
                        {!! $page->seo_content !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@php
    $pageFaqItems = [];
    if (!empty($page->faq_schema)) {
        $jsonText = preg_replace('/<script\b[^>]*>([\s\S]*?)<\/script>/i', '$1', $page->faq_schema);
        $parsed = json_decode($jsonText, true);
        if (is_array($parsed) && isset($parsed['mainEntity']) && is_array($parsed['mainEntity'])) {
            foreach ($parsed['mainEntity'] as $entity) {
                if (($entity['@type'] ?? '') === 'Question') {
                    $pageFaqItems[] = [
                        'question' => $entity['name'] ?? '',
                        'answer' => $entity['acceptedAnswer']['text'] ?? '',
                    ];
                }
            }
        }
    }
@endphp

@if(count($pageFaqItems))
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9" data-aos="fade-up">
                <h2 class="h3 fw-bold text-navy mb-4">Frequently Asked Questions</h2>
                <div class="accordion" id="pageFaqAccordion">
                    @foreach($pageFaqItems as $index => $faq)
                        <div class="accordion-item border-0 rounded-3 mb-3 shadow-sm overflow-hidden">
                            <h2 class="accordion-header" id="faqHeading{{ $index }}">
                                <button class="accordion-button collapsed bg-white text-dark rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}" aria-expanded="false" aria-controls="faqCollapse{{ $index }}">
                                    {{ $faq['question'] }}
                                </button>
                            </h2>
                            <div id="faqCollapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="faqHeading{{ $index }}" data-bs-parent="#pageFaqAccordion">
                                <div class="accordion-body bg-white text-muted">
                                    {!! nl2br(e($faq['answer'])) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@include('partials.frontend.mobile-cta')

@endsection
 