@extends('layouts.frontend')

@section('content')
<!-- Page Header Banner -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">{{ $page->title ?? 'Privacy Policy' }}</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
            {{ $page->subtitle ?? 'How we collect, secure, and manage your personal booking data.' }}
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="150">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Content Block -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-5">
            <!-- Left Column: Sticky Table of Contents (3 cols) -->
            <div class="col-lg-3 d-none d-lg-block" data-aos="fade-up">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card card-flight border-light shadow-sm bg-softgray rounded-3 p-4">
                        <h4 class="h6 fw-bold text-navy text-uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-list-ul me-2 text-gold"></i> Table of Contents
                        </h4>
                        <ul class="nav flex-column gap-2" id="toc-list" style="font-size: 0.85rem;">
                            <!-- Dynamically generated list -->
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Clean Prose content (9 cols) -->
            <div class="col-lg-9" data-aos="fade-up" data-aos-delay="100">
                <div class="max-width-760 mx-auto">
                    <div class="prose-content legal-content-wrapper" id="legal-content">
                        {!! $page->content !!}
                    </div>

                    <div class="mt-5 border-top pt-4 text-muted small border-light text-center text-md-start">
                        Last modified: {{ date('M d, Y') }}. For questions regarding this policy, please reach out to our <a href="{{ route('contact') }}" class="text-navy fw-semibold">privacy compliance desk</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.frontend.mobile-cta')

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var content = document.getElementById("legal-content");
        var headings = content.querySelectorAll("h2");
        var tocList = document.getElementById("toc-list");

        if (headings.length === 0) {
            tocList.innerHTML = '<li class="nav-item text-muted">General Guidelines</li>';
            return;
        }

        headings.forEach(function(heading, index) {
            // Generate clean ID from heading text
            var headingText = heading.innerText;
            var cleanId = "section-" + index;
            heading.setAttribute("id", cleanId);

            // Create sidebar link item
            var listItem = document.createElement("li");
            listItem.className = "nav-item";
            
            var anchor = document.createElement("a");
            anchor.className = "nav-link text-muted p-0 hover-gold-text";
            anchor.setAttribute("href", "#" + cleanId);
            anchor.innerText = headingText;
            
            listItem.appendChild(anchor);
            tocList.appendChild(listItem);
        });

        // Simple scrollspy to highlight active link
        window.addEventListener("scroll", function() {
            var scrollPosition = window.scrollY + 120;
            headings.forEach(function(heading) {
                var top = heading.offsetTop;
                var height = heading.offsetHeight;
                var id = heading.getAttribute("id");
                
                if (scrollPosition >= top && scrollPosition < top + height + 300) {
                    tocList.querySelectorAll("a").forEach(function(link) {
                        link.classList.remove("text-navy", "fw-bold");
                        link.classList.add("text-muted");
                        if (link.getAttribute("href") === "#" + id) {
                            link.classList.remove("text-muted");
                            link.classList.add("text-navy", "fw-bold");
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
