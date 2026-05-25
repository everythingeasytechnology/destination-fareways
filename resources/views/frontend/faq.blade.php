@extends('layouts.frontend')

@section('content')
<!-- Page Header Banner -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">Frequently Asked Questions</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
            Find immediate answers regarding booking policies, ticket changes, luggage limits, and cancelations.
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="150">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Content Block -->
<section class="py-5 bg-white">
    <div class="container py-3">
        @if($categories->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fa-solid fa-circle-question fs-1 text-muted mb-3"></i>
                <h3>No FAQ Content Found</h3>
                <p class="text-muted">We are currently updating our FAQ lists. For immediate support, please call our 24/7 help desk.</p>
            </div>
        @else
            <!-- Category Navigation Pills -->
            <div class="row mb-5 justify-content-center" data-aos="fade-up">
                <div class="col-lg-10 text-center">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @foreach($categories as $category)
                            <button class="btn btn-outline-navy rounded-pill px-4 py-2.5 text-uppercase fw-semibold {{ $loop->first ? 'active bg-navy text-white' : '' }}" 
                                    id="pill-{{ Str::slug($category) }}-tab" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#pane-{{ Str::slug($category) }}" 
                                    type="button" 
                                    role="tab" 
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    onclick="toggleCategoryBtn(this)">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Accordion Panels Grid -->
            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up" data-aos-delay="100">
                    <div class="tab-content" id="faqTabContent">
                        @foreach($groupedFaqs as $cat => $faqs)
                            <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" 
                                 id="pane-{{ Str::slug($cat) }}" 
                                 role="tabpanel" 
                                 aria-labelledby="pill-{{ Str::slug($cat) }}-tab">
                                
                                <div class="accordion custom-faq-accordion" id="accordion-{{ Str::slug($cat) }}">
                                    @foreach($faqs as $faq)
                                        <div class="accordion-item border-light shadow-sm rounded-3 mb-3 overflow-hidden border">
                                            <h2 class="accordion-header" id="heading-{{ $faq->id }}">
                                                <button class="accordion-button collapsed py-4 px-4 fw-semibold text-navy bg-white border-0 text-start" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse-{{ $faq->id }}" 
                                                        aria-expanded="false" 
                                                        aria-controls="collapse-{{ $faq->id }}">
                                                    {{ $faq->question }}
                                                </button>
                                            </h2>
                                            <div id="collapse-{{ $faq->id }}" 
                                                 class="accordion-collapse collapse" 
                                                 aria-labelledby="heading-{{ $faq->id }}" 
                                                 data-bs-parent="#accordion-{{ Str::slug($cat) }}">
                                                <div class="accordion-body px-4 pb-4 pt-0 text-muted small" style="line-height: 1.7;">
                                                    {!! $faq->answer !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Bottom CTA Panel -->
<section class="py-5 bg-softgray border-top border-light text-center">
    <div class="container py-4" data-aos="fade-up">
        <h3 class="h2 fw-bold text-navy mb-2">Still Have Questions?</h3>
        <p class="text-muted mb-4 max-w-600 mx-auto">
            Our expert reservation agents are on standby 24/7 to solve your booking queries. Skip the queues and call us directly now.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            @if(!empty($callSettings) && $callSettings->phone)
                <a href="tel:{{ $callSettings->phone }}" class="btn btn-navy px-4 py-2.5 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-phone text-gold"></i> Call Helpline: {{ $callSettings->phone }}
                </a>
            @else
                <a href="tel:+18005550199" class="btn btn-navy px-4 py-2.5 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-phone text-gold"></i> Call Helpline: +1 (800) 555-0199
                </a>
            @endif
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function toggleCategoryBtn(btn) {
        // Toggle styling on custom button pills manually if bootstrap bootstrap-nav conflicts
        const container = btn.closest('.d-flex');
        container.querySelectorAll('button').forEach(b => {
            b.classList.remove('bg-navy', 'text-white', 'active');
        });
        btn.classList.add('bg-navy', 'text-white', 'active');
    }
</script>
@endsection
