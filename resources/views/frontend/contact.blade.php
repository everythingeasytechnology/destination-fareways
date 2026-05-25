@extends('layouts.frontend')

@section('content')
<!-- Page Header Banner -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">{{ $page->title ?? 'Contact Our Travel Experts' }}</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
            {{ $page->subtitle ?? 'We are available 24/7 to assist with flight bookings, changes, and inquiries.' }}
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
            <!-- Left Side: Contact Form (7 cols) -->
            <div class="col-lg-7" data-aos="fade-up">
                <div class="card card-flight border-light shadow-sm p-4 p-md-5">
                    <h2 class="h3 fw-bold text-navy mb-2">Send Us a Message</h2>
                    <p class="text-muted small mb-4">
                        Fill out the secure form below, and our support concierges will review your details and respond in less than 2 hours.
                    </p>

                    <!-- Validation Alerts (already supported by layout toasts, but nice to have in-page fallback) -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4 fs-8">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label class="form-label text-muted fs-8 mb-1">Full Name</label>
                                <input type="text" name="name" class="form-control py-2.5" placeholder="John Doe" value="{{ old('name') }}" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label text-muted fs-8 mb-1">Email Address</label>
                                <input type="email" name="email" class="form-control py-2.5" placeholder="john@example.com" value="{{ old('email') }}" required>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label text-muted fs-8 mb-1">Phone Number (Optional)</label>
                                <input type="tel" name="phone" class="form-control py-2.5" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                            </div>

                            <!-- Subject -->
                            <div class="col-md-6">
                                <label class="form-label text-muted fs-8 mb-1">Subject</label>
                                <select name="subject" class="form-select py-2.5" required>
                                    <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select a topic</option>
                                    <option value="New Booking Inquiry" {{ old('subject') === 'New Booking Inquiry' ? 'selected' : '' }}>New Booking Inquiry</option>
                                    <option value="Change / Reschedule" {{ old('subject') === 'Change / Reschedule' ? 'selected' : '' }}>Change / Reschedule</option>
                                    <option value="Flight Cancellation" {{ old('subject') === 'Flight Cancellation' ? 'selected' : '' }}>Flight Cancellation</option>
                                    <option value="General Feedback" {{ old('subject') === 'General Feedback' ? 'selected' : '' }}>General Feedback</option>
                                </select>
                            </div>

                            <!-- Message -->
                            <div class="col-12">
                                <label class="form-label text-muted fs-8 mb-1">Your Message</label>
                                <textarea name="message" class="form-control py-2.5" rows="5" placeholder="Please outline your flight details, airline preference, or enquiry..." required>{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 py-3 text-navy fw-bold rounded-3 mt-4 transition-lift">
                            <i class="fa-solid fa-paper-plane me-2"></i> Send Secure Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Contact Information (5 cols) -->
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                <div class="ps-lg-4">
                    <h3 class="h3 fw-bold text-navy mb-3">Our Reservation Office</h3>
                    <div class="prose-content mb-4 text-muted small">
                        {!! $page->content !!}
                    </div>

                    <!-- Direct Contacts Info List -->
                    <div class="d-flex flex-column gap-4 border-top pt-4 border-light mb-4">
                        <!-- Phone -->
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-phone text-gold"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-8 text-uppercase fw-semibold">Helpline</span>
                                @if(!empty($callSettings) && $callSettings->phone)
                                    <a href="tel:{{ $callSettings->phone }}" class="fw-bold text-navy fs-6 font-monospace text-decoration-none hover-gold-text">
                                        {{ $callSettings->phone }}
                                    </a>
                                @else
                                    <a href="tel:+18005550199" class="fw-bold text-navy fs-6 font-monospace text-decoration-none hover-gold-text">
                                        +1 (800) 555-0199
                                    </a>
                                @endif
                                <span class="text-muted fs-9 d-block">(Toll-free inside USA & Canada)</span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-envelope text-gold"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-8 text-uppercase fw-semibold">Email Enquiries</span>
                                <a href="mailto:{{ $settings->support_email ?? 'bookings@destinationfareways.com' }}" class="fw-bold text-navy fs-6 text-decoration-none hover-gold-text">
                                    {{ $settings->support_email ?? 'bookings@destinationfareways.com' }}
                                </a>
                            </div>
                        </div>

                        <!-- Office Address -->
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-location-dot text-gold"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-8 text-uppercase fw-semibold">Head Office Address</span>
                                <span class="fw-semibold text-navy fs-7">
                                    {{ $settings->office_address ?? '1200 Avenue of the Americas, Suite 410, New York, NY 10036' }}
                                </span>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-navy d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-clock text-gold"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-8 text-uppercase fw-semibold">Business Hours</span>
                                <span class="fw-semibold text-navy fs-7 d-block">
                                    {{ $settings->business_hours ?? 'Monday - Sunday: 24 Hours / 7 Days a week' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Social Icons Box -->
                    <div class="border-top pt-4 border-light">
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-3">Connect With Us</span>
                        <div class="d-flex gap-2">
                            <a href="{{ $settings->facebook_url ?? '#' }}" class="btn btn-navy rounded-circle p-0 text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;" target="_blank">
                                <i class="fa-brands fa-facebook-f fs-8"></i>
                            </a>
                            <a href="{{ $settings->twitter_url ?? '#' }}" class="btn btn-navy rounded-circle p-0 text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;" target="_blank">
                                <i class="fa-brands fa-twitter fs-8"></i>
                            </a>
                            <a href="{{ $settings->instagram_url ?? '#' }}" class="btn btn-navy rounded-circle p-0 text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;" target="_blank">
                                <i class="fa-brands fa-instagram fs-8"></i>
                            </a>
                            <a href="{{ $settings->linkedin_url ?? '#' }}" class="btn btn-navy rounded-circle p-0 text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;" target="_blank">
                                <i class="fa-brands fa-linkedin-in fs-8"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
