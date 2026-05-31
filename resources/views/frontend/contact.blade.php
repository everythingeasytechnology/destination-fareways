@extends('layouts.frontend')

@section('content')

{{-- ── HERO ── --}}
<section class="offers-hero-section bg-navy text-white mt-5 position-relative overflow-hidden">
    <div class="offers-hero-pattern"></div>
    <div class="offers-hero-orb orb-1"></div>
    <div class="offers-hero-orb orb-2"></div>

    <div class="container py-5 text-center position-relative" style="z-index:2;">
        <div class="d-inline-flex align-items-center gap-2 offers-live-badge mb-3" data-aos="fade-up">
            <span class="live-dot"></span>
            <span>24/7 Available — Response in 2 Hours</span>
        </div>
        <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="60">
            {{ $page->title ?? 'Contact Our Travel Experts' }}
        </h1>
        <p class="lead mb-4 text-muted-white" data-aos="fade-up" data-aos-delay="120">
            {{ $page->subtitle ?? 'We are available 24/7 to assist with flight bookings, changes, and inquiries.' }}
        </p>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="160">
            @include('partials.frontend.breadcrumb')
        </div>
        <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="220">
            <div class="offers-stat-pill"><i class="fa-solid fa-headset text-gold me-2"></i>24/7 Expert Support</div>
            <div class="offers-stat-pill"><i class="fa-solid fa-shield-halved text-gold me-2"></i>Secure &amp; Encrypted</div>
            <div class="offers-stat-pill"><i class="fa-solid fa-clock text-gold me-2"></i>2-Hour Response Time</div>
        </div>
    </div>
</section>

{{-- ── MAIN CONTENT ── --}}
<section class="py-5 bg-softgray">
    <div class="container py-2">
        <div class="row g-5 align-items-start">

            {{-- ── LEFT: Contact Form ── --}}
            <div class="col-lg-7" data-aos="fade-up">
                <div class="ct-form-card">

                    {{-- Card Header --}}
                    <div class="ct-form-header">
                        <div class="ct-form-header-icon">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                        <div>
                            <h2 class="text-navy fw-bold mb-1" style="font-family:'DM Sans',sans-serif;font-size:1.35rem;">Send Us a Message</h2>
                            <p class="text-muted mb-0" style="font-size:.88rem;">
                                Our concierges will review your message and respond in under 2 hours.
                            </p>
                        </div>
                    </div>

                    {{-- Validation errors --}}
                    @if($errors->any())
                        <div class="mx-4 mt-4">
                            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-0">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li style="font-size:.875rem;">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Form --}}
                    <div class="ct-form-body">
                        <form action="{{ route('contact.submit') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="bq-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-user"></i></span>
                                        <input type="text" name="name" class="bq-input" placeholder="John Doe" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="bq-label">Email Address <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-envelope"></i></span>
                                        <input type="email" name="email" class="bq-input" placeholder="john@example.com" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="bq-label">Phone Number <span class="text-muted fw-normal">(Optional)</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-phone"></i></span>
                                        <input type="tel" name="phone" class="bq-input" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="bq-label">Subject <span class="text-danger">*</span></label>
                                    <div class="bq-input-wrap">
                                        <span class="bq-input-icon"><i class="fa-solid fa-tag"></i></span>
                                        <select name="subject" class="bq-input" required>
                                            <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select a topic</option>
                                            <option value="New Booking Inquiry" {{ old('subject') === 'New Booking Inquiry' ? 'selected' : '' }}>New Booking Inquiry</option>
                                            <option value="Change / Reschedule" {{ old('subject') === 'Change / Reschedule' ? 'selected' : '' }}>Change / Reschedule</option>
                                            <option value="Flight Cancellation" {{ old('subject') === 'Flight Cancellation' ? 'selected' : '' }}>Flight Cancellation</option>
                                            <option value="General Feedback" {{ old('subject') === 'General Feedback' ? 'selected' : '' }}>General Feedback</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="bq-label">Your Message <span class="text-danger">*</span></label>
                                    <textarea name="message" class="bq-textarea" rows="5"
                                              placeholder="Please outline your flight details, airline preference, or enquiry..." required>{{ old('message') }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-gold w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 mt-4">
                                <i class="fa-solid fa-paper-plane"></i> Send Secure Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Contact Info Sidebar ── --}}
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                <div class="ct-sidebar">

                    {{-- Call Card (offer-sidebar-card style) --}}
                    <div class="offer-sidebar-card mb-4">
                        <div class="offer-sidebar-header">
                            <span class="offer-sidebar-badge">Our Reservation Office</span>
                            <h4 class="text-white fw-bold mb-1 mt-2" style="font-family:'DM Sans',sans-serif;font-size:1.05rem;">
                                Speak to a Travel Expert
                            </h4>
                            <p class="mb-0 small" style="color:rgba(255,255,255,.6);">
                                {!! $page->content ?? 'Call us or drop a message. We respond in under 2 hours.' !!}
                            </p>
                        </div>
                        <div class="offer-sidebar-body pt-3 pb-2">

                            {{-- Contact Info Tiles --}}
                            <div class="ct-info-list">

                                {{-- Phone --}}
                                <div class="ct-info-tile">
                                    <div class="ct-info-icon">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div class="ct-info-content">
                                        <span class="ct-info-label">Helpline</span>
                                        @if(!empty($callSettings) && $callSettings->phone)
                                            <a href="tel:{{ $callSettings->phone }}" class="ct-info-value font-monospace text-decoration-none">
                                                {{ $callSettings->phone }}
                                            </a>
                                        @else
                                            <a href="tel:+18005550199" class="ct-info-value font-monospace text-decoration-none">
                                                +1 (800) 555-0199
                                            </a>
                                        @endif
                                        <span class="ct-info-sub">Toll-free USA &amp; Canada</span>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="ct-info-tile">
                                    <div class="ct-info-icon">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="ct-info-content">
                                        <span class="ct-info-label">Email Enquiries</span>
                                        <a href="mailto:{{ $settings->support_email ?? 'bookings@destinationfareways.com' }}"
                                           class="ct-info-value text-decoration-none">
                                            {{ $settings->support_email ?? 'bookings@destinationfareways.com' }}
                                        </a>
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="ct-info-tile">
                                    <div class="ct-info-icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div class="ct-info-content">
                                        <span class="ct-info-label">Head Office</span>
                                        <span class="ct-info-value">
                                            {{ $settings->office_address ?? '1200 Avenue of the Americas, Suite 410, New York, NY 10036' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Hours --}}
                                <div class="ct-info-tile">
                                    <div class="ct-info-icon">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                    <div class="ct-info-content">
                                        <span class="ct-info-label">Business Hours</span>
                                        <span class="ct-info-value">
                                            {{ $settings->business_hours ?? 'Monday – Sunday: 24 Hours / 7 Days' }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Social + Trust --}}
                        <div class="offer-sidebar-trust flex-column align-items-start gap-3 px-4 pb-4" style="background:#fff;">
                            <div class="w-100">
                                <span class="bq-label mb-2 d-block">Connect With Us</span>
                                <div class="d-flex gap-2">
                                    @if(!empty($settings->social_facebook))
                                        <a href="{{ $settings->social_facebook }}" class="blog-share-btn" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                    @else
                                        <a href="#" class="blog-share-btn"><i class="fa-brands fa-facebook-f"></i></a>
                                    @endif
                                    @if(!empty($settings->social_twitter))
                                        <a href="{{ $settings->social_twitter }}" class="blog-share-btn" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                                    @else
                                        <a href="#" class="blog-share-btn"><i class="fa-brands fa-x-twitter"></i></a>
                                    @endif
                                    @if(!empty($settings->social_instagram))
                                        <a href="{{ $settings->social_instagram }}" class="blog-share-btn" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                                    @else
                                        <a href="#" class="blog-share-btn"><i class="fa-brands fa-instagram"></i></a>
                                    @endif
                                    @if(!empty($settings->social_linkedin))
                                        <a href="{{ $settings->social_linkedin }}" class="blog-share-btn" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                                    @else
                                        <a href="#" class="blog-share-btn"><i class="fa-brands fa-linkedin-in"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Call CTA --}}
                    @if(!empty($callSettings) && $callSettings->status)
                        <div class="ct-call-cta" data-aos="fade-up">
                            <div class="ct-call-cta-text">
                                <span class="d-block fw-bold text-navy" style="font-size:.9rem;">Prefer to Call?</span>
                                <span class="text-muted" style="font-size:.8rem;">No hold music, real experts only.</span>
                            </div>
                            <a href="tel:{{ $callSettings->phone }}" class="btn btn-gold px-4 py-2 fw-bold font-monospace d-flex align-items-center gap-2 flex-shrink-0">
                                <i class="fa-solid fa-phone"></i> Call Now
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>


@include('partials.frontend.mobile-cta')

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
/* ── Input system (bq- shared with booking enquiry) ── */
.bq-label {
    display: block;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748B;
    margin-bottom: 6px;
}
.bq-input-wrap {
    display: flex;
    align-items: center;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    background: #F8FAFC;
    overflow: hidden;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.bq-input-wrap:focus-within {
    border-color: #F59E0B;
    box-shadow: 0 0 0 3px rgba(245,158,11,.1);
    background: #fffdf5;
}
.bq-input-icon {
    padding: 0 12px;
    color: #94a3b8;
    font-size: .9rem;
    flex-shrink: 0;
}
.bq-input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    padding: 11px 12px 11px 0;
    font-family: 'DM Sans', sans-serif;
    font-size: .9rem;
    color: #07111F;
    width: 100%;
}
.bq-input::placeholder { color: #94a3b8; }
.bq-textarea {
    display: block;
    width: 100%;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 10px !important;
    padding: 11px 14px !important;
    font-family: 'DM Sans', sans-serif;
    font-size: .9rem;
    color: #07111F;
    background: #F8FAFC;
    outline: none;
    resize: vertical;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.bq-textarea:focus {
    border-color: #F59E0B !important;
    box-shadow: 0 0 0 3px rgba(245,158,11,.1) !important;
    background: #fffdf5;
}
.bq-textarea::placeholder { color: #94a3b8; }

/* ── Form Card ─────────────────────────────────── */
.ct-form-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(7,17,31,.07);
    box-shadow: 0 8px 32px rgba(7,17,31,.08);
    overflow: hidden;
}
.ct-form-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px 28px 20px;
    border-bottom: 1px solid #E2E8F0;
    background: linear-gradient(135deg, #fafbfc, #f8fafc);
}
.ct-form-header-icon {
    width: 48px; height: 48px; flex-shrink: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #07111F, #0d1f38);
    color: #F59E0B;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    box-shadow: 0 4px 14px rgba(7,17,31,.2);
}
.ct-form-body { padding: 28px; }

/* ── Contact Info Tiles ─────────────────────────── */
.ct-info-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.ct-info-tile {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #F1F5F9;
}
.ct-info-tile:last-child { border-bottom: none; }
.ct-info-icon {
    width: 40px; height: 40px; flex-shrink: 0;
    border-radius: 12px;
    background: linear-gradient(135deg, #07111F, #0d1f38);
    color: #F59E0B;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem;
    box-shadow: 0 4px 10px rgba(7,17,31,.15);
}
.ct-info-content { display: flex; flex-direction: column; gap: 2px; }
.ct-info-label {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #94a3b8;
}
.ct-info-value {
    font-weight: 700;
    color: #07111F;
    font-size: .92rem;
    line-height: 1.4;
    transition: color .2s ease;
}
a.ct-info-value:hover { color: #F59E0B !important; }
.ct-info-sub { font-size: .75rem; color: #94a3b8; }

/* ── Sidebar sticky ─────────────────────────────── */
.ct-sidebar { position: sticky; top: 100px; }

/* ── Quick Call CTA ─────────────────────────────── */
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
}

/* ── Mobile ──────────────────────────────────────── */
@media (max-width: 991.98px) {
    .ct-sidebar { position: static; }
}
@media (max-width: 767.98px) {
    .ct-form-body { padding: 18px; }
    .ct-form-header { padding: 18px; gap: 12px; }
    .ct-form-header-icon { width: 40px; height: 40px; font-size: 1rem; }
    .ct-call-cta { flex-direction: column; text-align: center; }
    .ct-call-cta .btn { width: 100%; justify-content: center; }
}
@media (max-width: 480px) {
    .ct-form-body { padding: 14px; }
}
</style>
@endsection
