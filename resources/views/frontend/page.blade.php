@extends('layouts.frontend')

@section('content')
<section class="custom-page-hero text-white py-5 mt-5" @if($page->banner_image) style="background-image: linear-gradient(rgba(7, 17, 31, 0.74), rgba(7, 17, 31, 0.74)), url('{{ asset('storage/' . $page->banner_image) }}');" @endif>
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">{{ $page->title }}</h1>

        @if($page->subtitle)
            <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
                {{ $page->subtitle }}
            </p>
        @endif

        @if($page->show_breadcrumb)
            <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="150">
                @include('partials.frontend.breadcrumb')
            </div>
        @endif
    </div>
</section>

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

{{-- ── Mobile Sticky CTA ── --}}
<div class="offer-mobile-cta d-lg-none">
    <div class="d-flex gap-2 align-items-center">
        <div class="flex-grow-1">
            <span class="d-block text-muted-white small fw-semibold text-uppercase" style="font-size:.65rem;letter-spacing:.5px;">Best Price Guarantee</span>
            <span class="text-white fw-bold" style="font-size:.88rem;">Book Cheap Flights</span>
        </div>
        <a href="tel:{{ $callSettings->phone ?? '+18005550199' }}"
           class="btn btn-gold px-3 py-2 fw-bold text-navy d-flex align-items-center gap-1 flex-shrink-0" style="font-size:.82rem;">
            <i class="fa-solid fa-phone"></i> Call Now
        </a>
        <a href="{{ route('booking.enquiry') }}"
           class="btn btn-outline-light px-3 py-2 fw-semibold d-flex align-items-center gap-1 flex-shrink-0 offer-enquire-btn" style="font-size:.82rem;">
            Book Now
        </a>
    </div>
</div>

@endsection
