@extends('layouts.frontend')

@section('content')
<!-- Page Header -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">Editorial Policy</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
            How we research, write, fact-check, and maintain the travel content on Destination Fareways.
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="150">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-5">

            <!-- Left: Table of Contents (sticky) -->
            <div class="col-lg-3 d-none d-lg-block" data-aos="fade-up">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card border-light shadow-sm bg-softgray rounded-3 p-4">
                        <h4 class="h6 fw-bold text-navy text-uppercase mb-3" style="letter-spacing:.6px;">
                            <i class="fa-solid fa-list-ul me-2 text-gold"></i> Contents
                        </h4>
                        <ul class="list-unstyled d-flex flex-column gap-2" style="font-size:.85rem;">
                            <li><a href="#editorial-mission" class="text-decoration-none text-navy">Our Editorial Mission</a></li>
                            <li><a href="#content-standards" class="text-decoration-none text-navy">Content Standards</a></li>
                            <li><a href="#author-qualifications" class="text-decoration-none text-navy">Author Qualifications</a></li>
                            <li><a href="#fact-checking" class="text-decoration-none text-navy">Fact-Checking Process</a></li>
                            <li><a href="#review-updates" class="text-decoration-none text-navy">Review & Updates</a></li>
                            <li><a href="#sponsored-content" class="text-decoration-none text-navy">Sponsored Content Policy</a></li>
                            <li><a href="#corrections" class="text-decoration-none text-navy">Corrections Policy</a></li>
                            <li><a href="#contact-editorial" class="text-decoration-none text-navy">Contact Editorial Team</a></li>
                        </ul>

                        <hr class="my-4">
                        <div class="d-flex flex-column gap-2" style="font-size:.82rem;">
                            <a href="{{ route('about') }}" class="text-decoration-none text-navy">
                                <i class="fa-solid fa-circle-info text-gold me-1"></i> About Us
                            </a>
                            <a href="{{ route('contact') }}" class="text-decoration-none text-navy">
                                <i class="fa-solid fa-envelope text-gold me-1"></i> Contact Us
                            </a>
                            <a href="{{ route('privacy') }}" class="text-decoration-none text-navy">
                                <i class="fa-solid fa-shield-halved text-gold me-1"></i> Privacy Policy
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Article Content -->
            <div class="col-lg-9 prose-content" data-aos="fade-up">

                <section id="editorial-mission" class="mb-5">
                    <h2>Our Editorial Mission</h2>
                    <p>Destination Fareways publishes travel guides, flight tips, destination features, and booking advice to help travelers make confident, informed decisions. Our editorial mission is to provide accurate, useful, and up-to-date information drawn from real industry expertise — not generic content generated for search rankings alone.</p>
                    <p>Every piece of content published on Destination Fareways is held to the same standard: it must serve the reader's genuine informational need before any commercial consideration.</p>
                </section>

                <section id="content-standards" class="mb-5">
                    <h2>Content Standards</h2>
                    <p>Our editorial team follows these standards for all published content:</p>
                    <ul>
                        <li><strong>Accuracy:</strong> Claims about routes, prices, visa requirements, or airline policies are verified against current airline, government, or supplier sources before publication.</li>
                        <li><strong>Relevance:</strong> Guides cover destinations, routes, and travel scenarios that reflect real demand from our readers — not hypothetical or keyword-stuffed topics.</li>
                        <li><strong>Transparency:</strong> When content includes pricing, we distinguish between illustrative "starting from" figures and guaranteed fares. Actual fares are dynamic and subject to change.</li>
                        <li><strong>Independence:</strong> Editorial decisions — what to cover, how to frame it, what to recommend — are made independently of advertising or commercial partnerships unless explicitly disclosed.</li>
                        <li><strong>Clarity:</strong> Travel information is presented in plain language accessible to all travelers, with no unnecessary jargon or ambiguity.</li>
                    </ul>
                </section>

                <section id="author-qualifications" class="mb-5">
                    <h2>Author Qualifications &amp; Trust</h2>
                    <p>Content on Destination Fareways is written and reviewed by team members with direct experience in the travel industry. Our contributors include:</p>
                    <ul>
                        <li>Travel concierges and booking specialists with 5+ years of hands-on airline reservation experience.</li>
                        <li>Destination researchers who combine first-hand travel knowledge with current airline schedule data and government travel advisories.</li>
                        <li>Senior editors who review all content for factual accuracy, editorial tone, and alignment with our policies before publication.</li>
                    </ul>
                    <p>Author bylines on blog posts and destination guides include a title and area of expertise. If you have a question about a specific author's credentials, please <a href="{{ route('contact') }}">contact us</a>.</p>

                    <div class="row g-4 mt-2">
                        @php
                            $authors = [
                                ['name' => 'Editorial Staff', 'role' => 'Travel Content Team', 'expertise' => 'Flight deals, booking strategy, fare alerts', 'icon' => 'fa-pen-nib'],
                                ['name' => 'Destination Team', 'role' => 'Destination Researchers', 'expertise' => 'International destinations, visa guides, travel logistics', 'icon' => 'fa-globe'],
                                ['name' => 'Concierge Desk', 'role' => 'Booking Specialists', 'expertise' => 'Airline policies, cabin classes, route options', 'icon' => 'fa-headset'],
                            ];
                        @endphp
                        @foreach($authors as $author)
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-3 p-4 h-100 text-center">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-navy" style="width:56px;height:56px;">
                                        <i class="fa-solid {{ $author['icon'] }} text-gold fs-5"></i>
                                    </div>
                                </div>
                                <h5 class="fw-bold text-navy mb-1" style="font-size:.95rem;">{{ $author['name'] }}</h5>
                                <p class="text-muted mb-2" style="font-size:.8rem;">{{ $author['role'] }}</p>
                                <p class="mb-0" style="font-size:.82rem;color:#475569;">{{ $author['expertise'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

                <section id="fact-checking" class="mb-5">
                    <h2>Fact-Checking Process</h2>
                    <p>Before a piece is published, our editorial process includes the following verification steps:</p>
                    <ol>
                        <li><strong>Source check:</strong> All destination facts, airline route data, visa requirements, and pricing references are cross-checked against primary sources (airline websites, IATA data, government immigration portals).</li>
                        <li><strong>Internal review:</strong> A senior editor or subject-matter specialist reviews the draft for factual accuracy and potential gaps.</li>
                        <li><strong>Date-stamping:</strong> Every article includes a publication or last-updated date so readers know when the information was verified.</li>
                        <li><strong>Disclaimer on dynamic data:</strong> Any content referencing live flight prices or availability includes a clear note that fares change in real time and the content reflects conditions at time of writing.</li>
                    </ol>
                </section>

                <section id="review-updates" class="mb-5">
                    <h2>Content Review &amp; Updates</h2>
                    <p>Travel information changes regularly. Airline routes are added and discontinued, visa rules shift, and prices fluctuate. We review published content on the following schedule:</p>
                    <ul>
                        <li><strong>Destination guides:</strong> Reviewed at least every 6 months, or immediately when a major change (new route, airline entry/exit, political advisory) affects the destination.</li>
                        <li><strong>Flight tips and booking guides:</strong> Reviewed annually, or when airline policy changes make existing advice outdated.</li>
                        <li><strong>Offer and deal pages:</strong> Monitored continuously; expired offers are removed or updated promptly.</li>
                    </ul>
                    <p>When content is significantly updated, the "Last Updated" date is revised and a note may be added to reflect what changed.</p>
                </section>

                <section id="sponsored-content" class="mb-5">
                    <h2>Sponsored Content Policy</h2>
                    <p>Destination Fareways may occasionally publish sponsored content or content produced in partnership with airlines, travel suppliers, or other travel brands. When this occurs:</p>
                    <ul>
                        <li>Sponsored content is clearly labeled with a "Sponsored" or "Paid Partnership" disclosure at the top of the page.</li>
                        <li>Sponsored content is held to the same factual accuracy standards as editorial content.</li>
                        <li>Commercial relationships do not influence the editorial coverage of non-sponsored pages, guides, or recommendations.</li>
                    </ul>
                    <p>If you have questions about whether a specific piece of content is sponsored, please <a href="{{ route('contact') }}">contact us</a>.</p>
                </section>

                <section id="corrections" class="mb-5">
                    <h2>Corrections Policy</h2>
                    <p>We take accuracy seriously. If you find a factual error in any Destination Fareways article or guide, please notify us using the <a href="{{ route('contact') }}">contact form</a>. Include:</p>
                    <ul>
                        <li>The URL of the page containing the error.</li>
                        <li>The specific statement or claim you believe is inaccurate.</li>
                        <li>The correct information or a source supporting the correction.</li>
                    </ul>
                    <p>We will investigate and, where an error is confirmed, update the content and add a correction note within 5 business days.</p>
                </section>

                <section id="contact-editorial" class="mb-5">
                    <h2>Contact the Editorial Team</h2>
                    <p>For editorial questions, content correction requests, or partnership inquiries, please reach us via our <a href="{{ route('contact') }}">contact page</a>.</p>
                    <p>We read every message and respond to editorial matters within 2–3 business days.</p>
                </section>

            </div>
        </div>
    </div>
</section>
@endsection
