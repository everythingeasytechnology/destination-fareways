@extends('layouts.frontend')

@section('content')
<section class="custom-page-hero text-white py-5 mt-5" @if($page->banner_image) style="background-image: linear-gradient(rgba(7, 17, 31, 0.74), rgba(7, 17, 31, 0.74)), url('{{ asset('storage/' . $page->banner_image) }}');" @endif>
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3 text-white" data-aos="fade-up">{{ $page->title }}</h1>

        @if($page->subtitle)
            <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
                {{ $page->subtitle }}
            </p>
        @elseif(!empty($routeTagline))
            <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
                {{ $routeTagline }}
            </p>
        @endif

        @if($page->show_breadcrumb)
            <div class="d-flex justify-content-center text-center" data-aos="fade-up" data-aos-delay="150">
                @include('partials.frontend.breadcrumb')
            </div>
        @endif

        @if(isset($searchDefaults) && $searchDefaults)
            <div class="row justify-content-center mt-5" data-aos="fade-up" data-aos-delay="200">
                <div class="col-xl-10">
                    @include('partials.frontend.flight-search-form', ['showTripTabs' => false])
                </div>
            </div>
        @endif
    </div>
</section>

@if(!empty($routePreviewFlights))
<section class="py-5 bg-softgray">
    <div class="container py-4">
        <div class="row align-items-center gy-3 mb-4">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark mb-2">Flight deals from {{ $searchDefaults['from'] }} to {{ $searchDefaults['to'] }}</span>
                <h2 class="h3 fw-bold mb-2">Looking for a low fare on this route?</h2>
                <p class="text-muted mb-0">Find selected airline deals and quick book options for your departure city and destination.</p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill">Show all</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill">Return</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill">One way</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill">Direct</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill">Latest deals</button>
                    <span class="btn btn-light btn-sm rounded-pill border text-dark">July flights from ₹ 2,908</span>
                </div>
            </div>
        </div>
        <div class="row g-3">
            @foreach($routePreviewFlights as $flight)
                <div class="col-sm-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100 route-deal-card">
                        <div class="card-body p-3 d-flex flex-column justify-content-between h-100">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="route-deal-logo rounded-3 bg-white border d-flex align-items-center justify-content-center">
                                            <img src="{{ $flight['logo'] }}" alt="{{ $flight['airline_name'] }}" style="max-width:40px;max-height:40px;object-fit:contain;">
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-navy">{{ $flight['airline_name'] }}</div>
                                            <div class="text-muted small">{{ $searchDefaults['from'] }} - {{ $searchDefaults['to'] }} with {{ $flight['airline_name'] }}</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-dark border">{{ $flight['stops'] === 0 ? 'Direct' : ($flight['stops'] . ' stop' . ($flight['stops'] > 1 ? 's' : '')) }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                                <div>
                                    <div class="small text-muted">from</div>
                                    <div class="h5 fw-bold text-primary">${{ $flight['price'] }}</div>
                                </div>
                                <a href="{{ $flight['book_url'] }}" class="text-primary fw-bold text-decoration-none">Book now <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
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
