@extends('layouts.frontend')

@section('content')
@php
    $blogImageUrl = function ($path, $fallback) {
        if (empty($path)) {
            return $fallback;
        }

        return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    };
@endphp
<!-- Title Section -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <span class="badge bg-gold text-navy text-uppercase fw-bold px-3 py-1.5 fs-8 mb-3" data-aos="fade-up">
            {{ $blog->category ?? 'Travel Guide' }}
        </span>
        <h1 class="display-5 fw-bold mb-3 font-serif" data-aos="fade-up" data-aos-delay="100">{{ $blog->title }}</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="150">
            {{ $blog->subtitle }}
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Content Workspace -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-5">
            <!-- Left Side: Article Content (8 cols) -->
            <div class="col-lg-8" data-aos="fade-up">
                <!-- Large Article Banner -->
                <div class="rounded-4 overflow-hidden mb-4 shadow-sm border border-light" style="max-height: 440px;">
                    <img src="{{ $blogImageUrl($blog->banner_image ?? $blog->featured_image, 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1200&q=80&auto=format') }}" 
                         alt="{{ $blog->title }}" 
                         class="w-100 h-100 object-fit-cover">
                </div>

                <!-- Article Metadata Header -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom pb-4 mb-4 border-light">
                    <!-- Author Box -->
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $blogImageUrl($blog->author_image, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop') }}" 
                             alt="{{ $blog->author_name }}" 
                             class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                        <div>
                            <span class="d-block fw-semibold text-navy fs-7">{{ $blog->author_name ?? 'Editorial Staff' }}</span>
                            <span class="text-muted fs-8">Travel Concierge</span>
                        </div>
                    </div>
                    
                    <!-- Date / Read Time / Views info -->
                    <div class="d-flex align-items-center gap-3 text-muted fs-8">
                        <span><i class="fa-regular fa-calendar-days text-gold me-1"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recent' }}</span>
                        @if(!empty($blog->read_time))
                            <span><i class="fa-regular fa-clock text-gold me-1"></i> {{ $blog->read_time }} min read</span>
                        @endif
                        <span><i class="fa-regular fa-eye text-gold me-1"></i> {{ number_format($blog->views ?? 0) }} views</span>
                    </div>
                </div>

                <!-- Editorial Content (rendered safely from DB) -->
                <div class="prose-content mb-5">
                    {!! $blog->content !!}
                </div>

                <!-- Tags / Social Share Row -->
                <div class="border-top border-bottom py-4 mb-5 border-light d-flex flex-wrap align-items-center justify-content-between gap-4">
                    <!-- Tags -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-muted small fw-semibold text-uppercase me-1">Tags:</span>
                        @if(!empty($blog->tags))
                            @foreach(explode(',', $blog->tags) as $tag)
                                <span class="badge text-navy border border-light bg-light rounded-pill px-3 py-2 fs-9">
                                    #{{ trim($tag) }}
                                </span>
                            @endforeach
                        @else
                            <span class="badge text-navy border border-light bg-light rounded-pill px-3 py-2 fs-9">#CheapFlights</span>
                            <span class="badge text-navy border border-light bg-light rounded-pill px-3 py-2 fs-9">#TravelHacks</span>
                        @endif
                    </div>

                    <!-- Social share icons -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small fw-semibold text-uppercase me-1">Share:</span>
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($blog->title) }}" 
                           class="btn btn-navy rounded-circle p-0 text-white d-flex align-items-center justify-content-center shadow-sm hover-gold" 
                           target="_blank" style="width: 36px; height: 36px;">
                            <i class="fa-brands fa-twitter fs-8"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                           class="btn btn-navy rounded-circle p-0 text-white d-flex align-items-center justify-content-center shadow-sm hover-gold" 
                           target="_blank" style="width: 36px; height: 36px;">
                            <i class="fa-brands fa-facebook-f fs-8"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . url()->current()) }}" 
                           class="btn btn-navy rounded-circle p-0 text-white d-flex align-items-center justify-content-center shadow-sm hover-gold" 
                           target="_blank" style="width: 36px; height: 36px;">
                            <i class="fa-brands fa-whatsapp fs-7"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ urlencode($blog->title) }}" 
                           class="btn btn-navy rounded-circle p-0 text-white d-flex align-items-center justify-content-center shadow-sm hover-gold" 
                           target="_blank" style="width: 36px; height: 36px;">
                            <i class="fa-brands fa-linkedin-in fs-8"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Sticky Sidebar: Widget Panel (4 cols) -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <!-- Mini Booking Enquiry Form -->
                    <div class="card card-flight border-light shadow-sm mb-4">
                        <div class="card-body p-4 bg-softgray rounded-3">
                            <span class="badge bg-navy text-gold text-uppercase fw-bold px-2.5 py-1 fs-9 mb-3">
                                Exclusive unpublished deals
                            </span>
                            
                            <h4 class="h5 fw-bold text-navy mb-1.5 text-uppercase tracking-wider">Book cheap flights</h4>
                            <p class="text-muted small mb-4">Let our team build the perfect itinerary for you at wholesale fares.</p>

                            <form action="{{ route('booking.submit') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="trip_type" value="one_way">
                                <input type="hidden" name="cabin_class" value="economy">
                                <input type="hidden" name="adults" value="1">

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Departure Airport</label>
                                    <input type="text" name="from_airport" class="form-control bg-white py-2.5" placeholder="e.g. JFK or New York" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Destination Airport</label>
                                    <input type="text" name="to_airport" class="form-control bg-white py-2.5" placeholder="e.g. LAX or Los Angeles" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Full Name</label>
                                    <input type="text" name="name" class="form-control bg-white py-2.5" placeholder="John Doe" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Email</label>
                                    <input type="email" name="email" class="form-control bg-white py-2.5" placeholder="john@example.com" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted fs-8 mb-1">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control bg-white py-2.5" placeholder="+1 (555) 000-0000" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted fs-8 mb-1">Preferred Travel Date</label>
                                    <input type="date" name="departure_date" class="form-control bg-white py-2.5" required min="{{ date('Y-m-d') }}">
                                </div>

                                <button type="submit" class="btn btn-gold w-100 py-3 text-navy fw-bold rounded-3 mb-3 transition-lift">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Booking Enquiry
                                </button>
                            </form>

                            <!-- Reservation Call trigger -->
                            @if(!empty($callSettings) && $callSettings->status)
                                <a href="tel:{{ $callSettings->phone }}" class="btn btn-outline-navy w-100 py-3 fw-semibold font-monospace transition-lift bg-white">
                                    <i class="fa-solid fa-phone text-gold me-2"></i> Call: {{ $callSettings->phone }}
                                </a>
                            @else
                                <a href="tel:+18005550199" class="btn btn-outline-navy w-100 py-3 fw-semibold font-monospace transition-lift bg-white">
                                    <i class="fa-solid fa-phone text-gold me-2"></i> Call: +1 (800) 555-0199
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Blog Articles Section -->
        @if($relatedBlogs->isNotEmpty())
            <div class="row border-top pt-5 border-light g-4 mt-5">
                <div class="col-12" data-aos="fade-up">
                    <h3 class="h3 fw-bold text-navy mb-4">Related Travel Guides</h3>
                </div>
                
                @foreach($relatedBlogs as $related)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card card-flight h-100 shadow-sm border-light">
                            <div class="position-relative overflow-hidden" style="height: 160px;">
                                <img src="{{ $blogImageUrl($related->featured_image, 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&q=80&auto=format') }}" 
                                     alt="{{ $related->title }}" 
                                     class="w-100 h-100 object-fit-cover transition-transform" 
                                     loading="lazy">
                                <span class="position-absolute top-2.5 start-2.5 badge bg-navy text-gold text-uppercase fw-bold px-2.5 py-1.5 fs-8 shadow">
                                    {{ $related->category ?? 'Travel' }}
                                </span>
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                @if(!empty($related->read_time))
                                    <div class="text-muted fs-8 mb-2 d-flex align-items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-gold"></i>
                                        <span>{{ $related->read_time }} mins read</span>
                                    </div>
                                @endif
                                
                                <h4 class="h6 fw-bold text-navy mb-2 line-clamp-2">{{ $related->title }}</h4>
                                <p class="card-text text-muted small flex-grow-1 mb-3 line-clamp-3">
                                    {{ $related->excerpt }}
                                </p>
                                
                                <a href="{{ route('blog.show', $related->slug) }}" class="btn btn-outline-navy w-100 py-2 small mt-auto">
                                    Read Article <i class="fa-solid fa-arrow-right ms-2 fs-9"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Bootstrap validation trigger
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endsection
