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

        @if(isset($searchDefaults) && $searchDefaults)
            <div class="row justify-content-center mt-4" data-aos="fade-up" data-aos-delay="200">
                <div class="col-xl-10">
                    @include('partials.frontend.flight-search-form')
                </div>
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

@include('partials.frontend.mobile-cta')

@endsection
