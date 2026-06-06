<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.frontend.seo-meta')

    <!-- DNS prefetch for third-party origins -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- Preconnect to font origins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Critical CSS: Bootstrap (render-blocking intentionally — layout depends on it) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts — loaded async to eliminate font render-blocking -->
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
    </noscript>

    <!-- FontAwesome — icons used in header/nav so loaded normally (above-the-fold) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Non-critical CSS — loaded async (AOS, Swiper, Flatpickr not above the fold) -->
    <link rel="preload" as="style" href="https://unpkg.com/aos@2.3.1/dist/aos.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    </noscript>

    <!-- Custom Style Sheet — preloaded for faster first paint -->
    <link rel="preload" as="style"
          href="{{ asset('assets/frontend/css/style.css') }}?v={{ filemtime(public_path('assets/frontend/css/style.css')) }}"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}?v={{ filemtime(public_path('assets/frontend/css/style.css')) }}">
    </noscript>

    @yield('styles')

    <!-- Global Header Scripts from Admin Panel -->
    @if(!empty($settings->header_scripts))
        {!! $settings->header_scripts !!}
    @endif
</head>
<body>

    <!-- Header Section -->
    @include('partials.frontend.header')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Section -->
    @include('partials.frontend.footer')

    <!-- Floating Call/WhatsApp Triggers -->
    @include('partials.frontend.call-button')

    <!-- Toast Notification Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        @if(session('success'))
            <div class="toast show align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast show align-items-center text-white bg-danger border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Scripts — jQuery must load synchronously; inline scripts in @yield('scripts') depend on $ -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr" defer></script>
    <script src="{{ asset('assets/frontend/js/main.js') }}?v={{ filemtime(public_path('assets/frontend/js/main.js')) }}" defer></script>

    <!-- Initializers run after deferred scripts are ready -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof AOS !== 'undefined') {
                AOS.init({ duration: 800, once: true, easing: 'ease-in-out' });
            }
            setTimeout(function () {
                document.querySelectorAll('.toast').forEach(function (el) {
                    el.style.transition = 'opacity 0.5s';
                    el.style.opacity = '0';
                    setTimeout(function () { el.style.display = 'none'; }, 500);
                });
            }, 6000);
        });
    </script>

    @yield('scripts')

    <!-- Global Footer Scripts from Admin Panel -->
    @if(!empty($settings->footer_scripts))
        {!! $settings->footer_scripts !!}
    @endif
</body>
</html>
