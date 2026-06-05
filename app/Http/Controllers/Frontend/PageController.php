<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Page;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a custom CMS page by slug.
     */
    public function show(string $slug)
    {
        $settings = Setting::first();
        $page = Page::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $seoData = (object) [
            'meta_title' => $page->seo_title ?? $page->title,
            'meta_description' => $page->seo_description ?? $page->subtitle ?? '',
            'meta_keywords' => $page->seo_keywords ?? '',
            'og_title' => $page->og_title ?? $page->seo_title ?? $page->title,
            'og_description' => $page->og_description ?? $page->seo_description ?? $page->subtitle ?? '',
            'og_image' => $page->og_image ?? $page->banner_image ?? null,
            'twitter_title' => $page->twitter_title ?? $page->og_title ?? $page->seo_title ?? $page->title,
            'twitter_description' => $page->twitter_description ?? $page->og_description ?? $page->seo_description ?? $page->subtitle ?? '',
            'twitter_image' => $page->twitter_image ?? $page->og_image ?? $page->banner_image ?? null,
            'canonical_url' => route('pages.show', $page->slug),
            'robots_tag' => 'index, follow',
            'schema_markup' => $page->schema_markup,
            'faq_schema' => $page->faq_schema,
            'breadcrumb_schema' => $page->breadcrumb_schema,
        ];

        $searchDefaults = null;
        $routeHeading = null;
        $routeTagline = null;

        // If this page is a route page, show the flight search form prefilled with route values.
        if ($page->from_airport_code || $page->to_airport_code) {
            $searchDefaults = [
                'from' => $page->from_airport_code,
                'to' => $page->to_airport_code,
                'fromId' => null,
                'toId' => null,
                'depart' => request('depart', now()->addWeek()->toDateString()),
                'return' => request('return', now()->addWeeks(2)->toDateString()),
            ];
            $routeHeading = $page->title;
            $routeTagline = $page->subtitle ?: 'Find low fares, direct flights, and flexible dates for your selected route.';
        } elseif (preg_match('/flight-tickets-from-([a-z0-9-]+)-to-([a-z0-9-]+)/', $page->slug, $matches)) {
            $searchDefaults = [
                'from' => Str::of($matches[1])->replace('-', ' ')->title(),
                'to' => Str::of($matches[2])->replace('-', ' ')->title(),
                'fromId' => null,
                'toId' => null,
                'depart' => request('depart', now()->addWeek()->toDateString()),
                'return' => request('return', now()->addWeeks(2)->toDateString()),
            ];
            $routeHeading = $page->title ?: 'Flight tickets from ' . Str::of($matches[1])->replace('-', ' ')->title() . ' to ' . Str::of($matches[2])->replace('-', ' ')->title();
            $routeTagline = $page->subtitle ?: 'Search top fares and book flights between your departure and destination cities.';
        }

        $breadcrumbs = [
            ['title' => $page->title]
        ];

        $routePreviewFlights = [];
        if ($searchDefaults) {
            $searchQuery = [
                'from' => $searchDefaults['from'],
                'to' => $searchDefaults['to'],
                'depart' => $searchDefaults['depart'],
                'return' => $searchDefaults['return'],
                'cabin_class' => 'economy',
                'total_passengers' => 1,
            ];

            $flightBrands = [
                ['name' => 'American Airlines', 'code' => 'AA', 'logo' => 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=80&h=80&fit=crop'],
                ['name' => 'Delta Air Lines', 'code' => 'DL', 'logo' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=80&h=80&fit=crop'],
                ['name' => 'United Airlines', 'code' => 'UA', 'logo' => 'https://images.unsplash.com/photo-1483450388369-9ed95738483c?w=80&h=80&fit=crop'],
            ];

            foreach ($flightBrands as $index => $brand) {
                $price = 129 + ($index * 35);
                $routePreviewFlights[] = [
                    'airline_name' => $brand['name'],
                    'airline_code' => $brand['code'],
                    'logo' => $brand['logo'],
                    'departure_time' => ['09:35', '11:20', '13:10'][$index],
                    'arrival_time' => ['13:15', '15:05', '17:00'][$index],
                    'duration' => ['3h 40m', '3h 45m', '3h 50m'][$index],
                    'stops' => [0, 1, 0][$index],
                    'price' => $price,
                    'price_person' => 'per person',
                    'book_url' => route('flights.details', 101 + $index) . '?' . http_build_query($searchQuery),
                ];
            }
        }

        return view('frontend.page', compact('settings', 'page', 'seoData', 'breadcrumbs', 'searchDefaults', 'routeHeading', 'routeTagline', 'routePreviewFlights'));
    }

    /**
     * Display the About Us page
     */
    public function about()
    {
        $settings = Setting::first();
        $page = Page::where('slug', 'about')->where('status', 'active')->first();
        $seoData = SeoSetting::where('page_identifier', 'about')->first();

        // If the page doesn't exist in the database, mock it dynamically
        if (!$page) {
            $page = (object) [
                'title' => 'About Destination Fareways',
                'subtitle' => 'Our Journey, Your Destination',
                'content' => '<p>Destination Fareways is a premium travel agency designed for discerning travelers searching for high-quality domestic and international flights at competitive discounts. Over the past 15 years, we have built key airline inventory partnerships to offer our clients unpublished pricing options unavailable elsewhere online.</p><p>Our dedicated booking concierges are available 24/7 to design the perfect travel itinerary for business or leisure. Let us connect you to over 200 destinations worldwide with the comfort and savings you deserve.</p>'
            ];
        }

        $stats = [
            ['value' => '10M+', 'label' => 'Travelers served'],
            ['value' => '500+', 'label' => 'Airlines contracted'],
            ['value' => '200+', 'label' => 'Destinations covered'],
            ['value' => '15+', 'label' => 'Years in business']
        ];

        // Ensure robust seo values
        if (!$seoData) {
            $seoData = (object) [
                'meta_title' => $page->seo_title ?? 'About Us | Destination Fareways',
                'meta_description' => $page->seo_description ?? 'Learn about Destination Fareways and our 15+ years of delivering luxury domestic and international travel at discounted rates.',
                'meta_keywords' => $page->seo_keywords ?? 'about us, discounted flights, flight concierges, travel agency',
                'og_title' => $page->og_title ?? $page->title,
                'og_description' => $page->og_description ?? 'Learn about Destination Fareways.',
                'og_image' => $page->og_image ?? null,
                'canonical_url' => route('about'),
                'robots_tag' => 'index, follow'
            ];
        }

        $breadcrumbs = [
            ['title' => 'About Us']
        ];

        return view('frontend.about', compact('settings', 'page', 'stats', 'seoData', 'breadcrumbs'));
    }

    /**
     * Display the Contact Us page
     */
    public function contact()
    {
        $settings = Setting::first();
        $page = Page::where('slug', 'contact')->where('status', 'active')->first();
        $seoData = SeoSetting::where('page_identifier', 'contact')->first();

        if (!$page) {
            $page = (object) [
                'title' => 'Contact Us',
                'subtitle' => 'Get in Touch with Our 24/7 Experts',
                'content' => '<p>Have questions about your booking? Or looking for exclusive phone-only flight promotions? Dial our reservation line or submit the contact form below, and one of our dedicated concierges will assist you in minutes.</p>'
            ];
        }

        if (!$seoData) {
            $seoData = (object) [
                'meta_title' => $page->seo_title ?? 'Contact Us | 24/7 Reservation Concierges',
                'meta_description' => $page->seo_description ?? 'Contact Destination Fareways. We are available 24/7 to assist with new flight searches, modifications, and cancellations.',
                'meta_keywords' => $page->seo_keywords ?? 'contact flights support, cancel flight, book tickets helpline',
                'og_title' => $page->og_title ?? $page->title,
                'og_description' => $page->og_description ?? 'Contact Destination Fareways.',
                'og_image' => $page->og_image ?? null,
                'canonical_url' => route('contact'),
                'robots_tag' => 'index, follow'
            ];
        }

        $breadcrumbs = [
            ['title' => 'Contact Us']
        ];

        return view('frontend.contact', compact('settings', 'page', 'seoData', 'breadcrumbs'));
    }

    /**
     * Display the Privacy Policy page
     */
    public function privacy()
    {
        $settings = Setting::first();
        $page = Page::where('slug', 'privacy-policy')->where('status', 'active')->first();

        if (!$page) {
            $page = (object) [
                'title' => 'Privacy Policy',
                'subtitle' => 'How we collect, use, protect, and manage your travel booking information.',
                'content' => <<<HTML
<h2>Privacy Policy Overview</h2>
<p>For the purposes of this Privacy Policy, Destination Fareways, "this site," "we," "us," and "our" refer to Destination Fareways and the travel services available through our website. "You" refers to the visitor, customer, traveler, or user of our services.</p>
<p>By using Destination Fareways, reviewing our Terms &amp; Conditions, submitting a booking request, contacting our team, or purchasing travel-related services, you agree to the collection, retention, use, and disclosure of your personal information as described in this Privacy Policy.</p>
<p>Before using our website or buying any product or service through Destination Fareways, please read this Privacy Policy carefully to understand our information collection, security, sharing, and communication practices.</p>

<h2>Our Privacy Commitment</h2>
<p>Destination Fareways respects your privacy and works to protect the personal and private information you submit through our website, contact forms, flight enquiry forms, booking assistance channels, email, or customer support interactions.</p>
<p>The information we may collect includes your name, address, telephone number, email address, travel preferences, trip details, passenger information, payment-related details, and other information required to provide flight booking assistance or respond to your request.</p>

<h2>Personal Information We Collect</h2>
<p>Destination Fareways maintains privacy standards designed to protect customer information. We do not sell, trade, rent, or otherwise share your personal information with unrelated third parties for their independent marketing purposes.</p>
<p>We request personal information when it is needed to communicate with you, verify a booking request, process travel services, assist with customer support, or complete a transaction. You may visit many areas of our website without submitting personal information, but certain features and services require details from you.</p>
<p>For booking or travel-service transactions, we may ask for information such as the passenger name as shown on a passport or government ID, phone number, email address, billing address, gender where required by an airline, date of birth, passport details, travel route, travel dates, and payment information.</p>

<h2>How We Use Your Information</h2>
<p>We use the information collected through Destination Fareways for legitimate travel, customer service, security, and business purposes, including:</p>
<ul>
    <li>Sharing required traveler details with airlines, consolidators, and travel suppliers to process ticket requests or booking services.</li>
    <li>Processing payments, verifying transactions, preventing fraud, and completing customer support requests.</li>
    <li>Working with trusted service providers that assist with payment processing, financial review, call center support, marketing communication, analytics, fraud prevention, and technical operations.</li>
    <li>Contacting you about booking status, fare changes, itinerary updates, service requests, travel alerts, promotional offers, or customer support matters.</li>
    <li>Improving website performance, customer experience, products, services, and travel assistance workflows.</li>
</ul>
<p>You may opt out of promotional email communication at any time. Even after opting out, you may still receive necessary service-related messages about active bookings, payments, refunds, itinerary changes, or customer support requests.</p>

<h2>Information Sharing and Consent</h2>
<p>We share personal information only when necessary to provide requested services, complete booking-related transactions, comply with legal obligations, protect our rights, prevent fraud, or operate our business through trusted service providers.</p>
<p>Where practical and appropriate, Destination Fareways will inform customers when personal information must be shared with third-party providers for travel processing, payment verification, fraud prevention, or related service requirements.</p>

<h2>Debit and Credit Card Protection</h2>
<p>Destination Fareways uses debit card and credit card details only for authorized payment processing, booking verification, fraud prevention, charge validation, and transaction support. We treat payment information as confidential and use reasonable safeguards to protect it.</p>
<p>Transactions involving sensitive information, including payment-card details, should be handled through secure connections and trusted payment processes. While we take appropriate security measures, no website, transmission method, or storage system can be guaranteed to be completely secure.</p>
<p>If we become aware of a security issue that affects your personal information, we will make reasonable efforts to notify affected users as required by applicable law.</p>

<h2>Legal Disclosure</h2>
<p>Destination Fareways may disclose personal or confidential information if required by law, court order, legal process, government request, fraud investigation, chargeback review, or when we believe disclosure is necessary to protect the rights, safety, property, or security of Destination Fareways, our customers, our suppliers, or others.</p>

<h2>Children's Privacy</h2>
<p>Destination Fareways is not intended for children under the age of 13, and we do not knowingly collect or maintain personal information from children under 13. Minors between the ages of 13 and 17 should use our services only with permission and supervision from a parent or legal guardian.</p>
<p>Parents or guardians should review all airline, identification, ticketing, and travel-document requirements before making bookings for minors.</p>

<h2>Email Opt Out</h2>
<p>You may opt out of promotional emails at any time by clicking the unsubscribe link at the bottom of our marketing emails or by contacting Destination Fareways customer support. Opting out of promotional communication does not prevent us from sending transactional, booking, payment, refund, or service-related messages.</p>

<h2>Accessing and Updating Information</h2>
<p>You may contact Destination Fareways to request updates, corrections, or assistance related to personal information you previously submitted. For security and fraud-prevention reasons, we may need to verify your identity before processing certain requests.</p>

<h2>Policy Updates</h2>
<p>Destination Fareways may update this Privacy Policy from time to time to reflect changes in our services, technology, legal obligations, supplier requirements, or business practices. Updated versions become effective when posted on this page.</p>

<h2>Contact Destination Fareways</h2>
<p>If you have any questions, concerns, or unclear terms related to this Privacy Policy, please contact Destination Fareways through our official contact page before using or purchasing any service from our website.</p>
HTML
            ];
        }

        $seoData = (object) [
            'meta_title' => $page->seo_title ?? 'Privacy Policy | Destination Fareways Data Protection',
            'meta_description' => $page->seo_description ?? 'Read the Destination Fareways Privacy Policy to learn how we collect, use, share, and protect personal information, traveler details, and payment data.',
            'meta_keywords' => $page->seo_keywords ?? 'Destination Fareways privacy policy, travel booking privacy, personal information protection, credit card security, email opt out',
            'og_title' => $page->og_title ?? $page->title,
            'og_description' => $page->og_description ?? 'Learn how Destination Fareways protects traveler information, payment details, booking data, and customer privacy.',
            'og_image' => $page->og_image ?? null,
            'canonical_url' => route('privacy'),
            'robots_tag' => 'index, follow',
            'schema_markup' => json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'PrivacyPolicy',
                'name' => 'Privacy Policy',
                'url' => route('privacy'),
                'description' => 'Destination Fareways Privacy Policy covering personal information, booking data, payment protection, information sharing, children privacy, and email opt out.',
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Destination Fareways',
                    'url' => url('/'),
                ],
            ], JSON_UNESCAPED_SLASHES)
        ];

        $breadcrumbs = [
            ['title' => 'Privacy Policy']
        ];

        return view('frontend.privacy-policy', compact('settings', 'page', 'seoData', 'breadcrumbs'));
    }

    /**
     * Display the Cookies Policy page
     */
    public function cookies()
    {
        $settings = Setting::first();
        $page = Page::where('slug', 'cookies')->where('status', 'active')->first();

        if (!$page) {
            $page = (object) [
                'title' => 'Cookies Policy',
                'subtitle' => 'How Destination Fareways uses cookies and similar technologies.',
                'content' => <<<HTML
<h2>Cookie Policy Overview</h2>
<p>This Cookies Policy explains how Destination Fareways uses cookies, web beacons, pixels, tags, and similar technologies when you visit or use our website, flight search tools, booking enquiry forms, customer support pages, or related online services.</p>
<p>By continuing to use Destination Fareways, you agree that we may place cookies and similar technologies on your browser or device as described in this policy, unless you disable them through your browser settings or available cookie controls.</p>

<h2>What Are Cookies?</h2>
<p>Cookies are small text files stored on your computer, mobile phone, tablet, or other device when you visit a website. Cookies help a website remember your actions, preferences, session activity, device details, and browsing behavior so that pages work properly and services can be improved.</p>
<p>Cookies may be placed directly by Destination Fareways as first-party cookies or by service providers, analytics partners, advertising platforms, payment processors, travel suppliers, or other approved third parties as third-party cookies.</p>

<h2>Session Cookies</h2>
<p>Session cookies are temporary cookies that remain on your device only while your browser is open. These cookies help you move between pages, keep form activity connected during a browsing session, protect website security, and support features such as flight search, booking enquiry steps, and customer support forms.</p>

<h2>Persistent Cookies</h2>
<p>Persistent cookies remain on your device after your browser closes until they expire or you delete them. These cookies help us remember returning visitors, improve website performance, understand repeat usage, maintain preferences, and support analytics or marketing activities where permitted.</p>

<h2>Types of Cookies We Use</h2>
<p>Destination Fareways may use the following categories of cookies:</p>
<ul>
    <li><strong>Essential cookies:</strong> Required for website security, page navigation, form submission, fraud prevention, and basic site functionality.</li>
    <li><strong>Performance and analytics cookies:</strong> Help us understand how visitors use the website, which pages are viewed, how users arrive on the site, and where improvements may be needed.</li>
    <li><strong>Preference cookies:</strong> Remember settings such as selected routes, passenger counts, form choices, language preferences, or display preferences when available.</li>
    <li><strong>Marketing cookies:</strong> May help us measure advertising effectiveness, provide relevant offers, and understand interactions with promotions or campaigns.</li>
    <li><strong>Security cookies:</strong> Help detect suspicious activity, protect forms, reduce fraud, and support safe booking-related interactions.</li>
</ul>

<h2>Information Collected Through Cookies</h2>
<p>Cookies and similar technologies may collect technical and usage information such as IP address, browser type, device type, operating system, pages viewed, referral URLs, search activity, approximate location, session identifiers, interaction events, and whether you responded to an advertisement or website feature.</p>
<p>Cookie data may be combined with booking enquiry information or customer support information when necessary to provide services, improve user experience, secure transactions, prevent fraud, or measure website performance.</p>

<h2>Third-Party Cookies</h2>
<p>Some cookies may be placed by third-party service providers that help Destination Fareways operate the website, process payments, provide analytics, support advertising, manage customer communication, prevent fraud, or connect users with travel services. These third parties may use cookies according to their own privacy and cookie policies.</p>

<h2>Web Beacons and Similar Technologies</h2>
<p>We may use web beacons, pixels, tags, clear GIFs, or similar technologies in website pages or email messages. These tools help us understand whether a page was visited, whether an email was opened, whether a promotion was effective, and how users interact with our online services.</p>

<h2>Managing Cookies</h2>
<p>You can manage or disable cookies through your browser settings. Most browsers allow you to block cookies, delete existing cookies, receive alerts before cookies are stored, or limit third-party cookies. Please review your browser's help section for instructions specific to your browser and device.</p>
<p>If you disable cookies, some Destination Fareways features may not work correctly. Flight search, forms, security checks, booking assistance, preference storage, and other website functions may be limited or unavailable.</p>

<h2>Cookies and Flight Prices</h2>
<p>Destination Fareways uses cookies to support website functionality, security, analytics, preferences, and marketing measurement. Airline fares and availability are dynamic and may change due to airline inventory, demand, routing, supplier updates, taxes, cabin availability, and ticketing rules. Cookies do not guarantee fare availability or final ticket pricing.</p>

<h2>Updates to This Cookies Policy</h2>
<p>Destination Fareways may update this Cookies Policy from time to time to reflect changes in technology, legal requirements, website features, service providers, or business practices. Updated versions become effective when posted on this page.</p>

<h2>Contact Destination Fareways</h2>
<p>If you have questions about this Cookies Policy or how cookies are used on Destination Fareways, please contact us through our official contact page.</p>
HTML
            ];
        }

        $seoData = (object) [
            'meta_title' => $page->seo_title ?? 'Cookies Policy | Destination Fareways',
            'meta_description' => $page->seo_description ?? 'Read the Destination Fareways Cookies Policy to learn how we use cookies, analytics, marketing tags, security cookies, and browser controls.',
            'meta_keywords' => $page->seo_keywords ?? 'Destination Fareways cookies policy, cookie policy, travel website cookies, analytics cookies, marketing cookies, browser cookie settings',
            'og_title' => $page->og_title ?? $page->title,
            'og_description' => $page->og_description ?? 'Learn how Destination Fareways uses cookies and similar technologies for security, analytics, preferences, and marketing.',
            'og_image' => $page->og_image ?? null,
            'canonical_url' => route('cookies'),
            'robots_tag' => 'index, follow',
            'schema_markup' => json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => 'Cookies Policy',
                'url' => route('cookies'),
                'description' => 'Destination Fareways Cookies Policy covering essential cookies, analytics cookies, marketing cookies, third-party cookies, and browser controls.',
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Destination Fareways',
                    'url' => url('/'),
                ],
            ], JSON_UNESCAPED_SLASHES)
        ];

        $breadcrumbs = [
            ['title' => 'Cookies Policy']
        ];

        return view('frontend.cookies', compact('settings', 'page', 'seoData', 'breadcrumbs'));
    }

    /**
     * Display the Refund Policy page
     */
    public function refundPolicy()
    {
        $settings = Setting::first();
        $page = Page::where('slug', 'refund-policy')->where('status', 'active')->first();

        if (!$page) {
            $page = (object) [
                'title' => 'Refund Policy',
                'subtitle' => 'Refund eligibility, airline rules, service fees, and processing timelines.',
                'content' => <<<HTML
<h2>Refund Policy Overview</h2>
<p>Destination Fareways understands that travel plans can change. This Refund Policy explains the general conditions under which a refund may be requested for flight services booked or assisted through our platform.</p>
<p>Refund eligibility depends on the airline's fare rules, ticket type, supplier conditions, cancellation timing, passenger status, and applicable law. Destination Fareways acts as a booking assistance provider and does not independently determine or guarantee airline refunds.</p>

<h2>Airline Fare Rules Apply</h2>
<p>All tickets are governed by the rules of the issuing airline, operating carrier, consolidator, or travel supplier. Refundable, partially refundable, non-refundable, promotional, basic economy, and special fares may each have different cancellation and refund restrictions.</p>
<p>Passengers are advised to review the fare rules, cancellation terms, baggage rules, change penalties, and airline refund policy before confirming any booking.</p>

<h2>Refund Eligibility</h2>
<p>A refund may be available when the airline or travel supplier permits it under the fare rules attached to your ticket. Common situations may include:</p>
<ul>
    <li>A ticket purchased as refundable according to the fare rules at the time of booking.</li>
    <li>An airline-initiated cancellation or major schedule change that qualifies for a refund under airline policy.</li>
    <li>A duplicate booking or billing issue verified by the airline, supplier, payment processor, or Destination Fareways.</li>
    <li>An exceptional circumstance accepted by the airline or supplier, such as documented medical emergency, travel restriction, or approved waiver.</li>
</ul>

<h2>Non-Refundable Situations</h2>
<p>Refunds are commonly not available in the following situations unless the airline or supplier grants an exception:</p>
<ul>
    <li>Flights marked as non-refundable at the time of booking.</li>
    <li>No-shows, missed flights, late arrival at the airport, or failure to complete check-in or boarding requirements.</li>
    <li>Incorrect traveler names, invalid documents, visa issues, denied boarding, or failure to meet destination entry requirements.</li>
    <li>Voluntary cancellations after the airline's allowed refund window has expired.</li>
    <li>Flight disruptions caused by weather, airport disruption, strikes, air traffic control restrictions, force majeure, or events outside our control.</li>
    <li>Optional add-ons, baggage fees, seat fees, insurance, visa fees, or third-party charges that are non-refundable under provider rules.</li>
</ul>

<h2>How to Request a Refund</h2>
<p>To request a refund, contact Destination Fareways customer support with your booking reference, passenger name, contact details, ticket number if available, travel date, and reason for cancellation or refund request.</p>
<p>After receiving your request, we may verify the booking, review applicable fare rules, request supporting documents, and submit the refund request to the airline or travel supplier when the fare conditions allow it.</p>

<h2>Refund Processing Timeline</h2>
<p>Refund processing times vary by airline, supplier, card issuer, payment processor, and bank. Once a refund is approved by the airline or supplier, it may take additional time to appear on your credit card, debit card, bank account, or original payment method.</p>
<p>In many cases, the overall refund process may take 60 to 90 days from the date the completed refund request is received, though some airlines or payment providers may process refunds sooner or later.</p>

<h2>Refund Amounts and Deductions</h2>
<p>The final refund amount is determined by the airline or supplier according to the ticket's fare rules. Refunds may be reduced by airline penalties, cancellation charges, non-refundable taxes, supplier fees, currency conversion adjustments, payment processor charges, or Destination Fareways service fees where applicable.</p>
<p>If the airline or supplier provides a refund breakdown, Destination Fareways may share the available details with you. We cannot guarantee that every airline or supplier will provide a detailed breakdown.</p>

<h2>Service Fees</h2>
<p>Destination Fareways service fees charged for the original travel reservation, booking assistance, support, or ticketing service are non-refundable unless otherwise stated in writing or required by law.</p>
<p>Additional refund handling or cancellation service fees may apply when Destination Fareways processes a refund, cancellation, waiver, or exchange request on your behalf. These fees may be charged per ticket and per passenger when permitted by airline or supplier rules.</p>

<h2>Travel Credits and Vouchers</h2>
<p>Some airlines may offer travel credits, vouchers, future travel funds, or rebooking options instead of a cash refund. These alternatives are controlled by the airline or supplier and may include expiration dates, route restrictions, passenger-name restrictions, fare differences, and reissue fees.</p>
<p>Acceptance of a travel credit or voucher may affect eligibility for a cash refund depending on airline policy.</p>

<h2>Chargebacks and Payment Disputes</h2>
<p>If you initiate a chargeback or payment dispute, refund processing may be delayed while the card issuer, payment processor, airline, supplier, and Destination Fareways review the transaction. Chargeback outcomes are controlled by financial institutions and card-network rules.</p>

<h2>Schedule Changes and Airline Cancellations</h2>
<p>If the airline cancels your flight or makes a significant schedule change, refund eligibility depends on the airline's policy and applicable regulations. Some airlines may offer a refund, while others may offer rebooking, travel credit, or alternate transportation.</p>
<p>Destination Fareways can assist with communicating eligible requests, but final approval, refund amount, and processing timeline remain with the airline or travel supplier.</p>

<h2>Policy Updates</h2>
<p>Destination Fareways may update this Refund Policy at any time to reflect airline rule changes, supplier requirements, legal obligations, or business practices. Updated versions become effective when posted on this page.</p>

<h2>Contact Destination Fareways</h2>
<p>If you have questions about refund eligibility, cancellation rules, service fees, travel credits, or refund timelines, please contact Destination Fareways through our official contact page before submitting a cancellation or refund request.</p>
HTML
            ];
        }

        $seoData = (object) [
            'meta_title' => $page->seo_title ?? 'Refund Policy | Destination Fareways Flight Refund Rules',
            'meta_description' => $page->seo_description ?? 'Read the Destination Fareways Refund Policy covering airline fare rules, refund eligibility, cancellation fees, service fees, travel credits, and processing timelines.',
            'meta_keywords' => $page->seo_keywords ?? 'Destination Fareways refund policy, flight refund rules, airline refund policy, cancellation refund, travel credit, non-refundable tickets',
            'og_title' => $page->og_title ?? $page->title,
            'og_description' => $page->og_description ?? 'Learn about Destination Fareways refund eligibility, airline cancellation rules, service fees, travel credits, and processing timelines.',
            'og_image' => $page->og_image ?? null,
            'canonical_url' => route('refund-policy'),
            'robots_tag' => 'index, follow',
            'schema_markup' => json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => 'Refund Policy',
                'url' => route('refund-policy'),
                'description' => 'Destination Fareways Refund Policy covering flight refund eligibility, cancellation rules, airline fare conditions, service fees, and processing timelines.',
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Destination Fareways',
                    'url' => url('/'),
                ],
            ], JSON_UNESCAPED_SLASHES)
        ];

        $breadcrumbs = [
            ['title' => 'Refund Policy']
        ];

        return view('frontend.refund-policy', compact('settings', 'page', 'seoData', 'breadcrumbs'));
    }

    /**
     * Display the Terms and Conditions page
     */
    public function terms()
    {
        $settings = Setting::first();
        $page = Page::where('slug', 'terms-conditions')->where('status', 'active')->first();

        if (!$page) {
            $page = (object) [
                'title' => 'Terms & Conditions',
                'subtitle' => 'Booking agreement, fare rules, payment policy, and traveler responsibilities.',
                'content' => <<<HTML
<h2>Terms of Use</h2>
<p>Welcome to Destination Fareways. These terms and conditions apply when you access or use our website, travel services, booking assistance, products, content, or related online features. This page forms a legal agreement between you and Destination Fareways. Please read it carefully before searching for flights, submitting traveler details, requesting a booking, or using any service available through Destination Fareways.</p>
<p>By using this website, you confirm that you accept these Terms &amp; Conditions, our Privacy Policy, and all applicable airline, supplier, and government travel rules. If you do not agree with any part of these terms, please stop using the website and do not submit a booking request.</p>

<h2>Traveler Name Policy</h2>
<p>You agree to enter the traveler's first name and last name exactly as they appear on a valid government-issued identification document. Depending on the journey type, acceptable identification may include a passport, driver's license, state ID, or another document required by the airline or travel authority.</p>
<p>Once a name has been submitted for booking or ticketing, changes may be restricted. Certain spelling corrections may be allowed only when permitted by the airline's terms, and applicable airline penalties, service fees, fare differences, or documentation requirements may apply.</p>

<h2>Fare Change Conditions</h2>
<p>Airline tickets, prices, schedules, routes, seat availability, taxes, and fare rules are not guaranteed until ticketing is completed. A quoted fare may change at any time if revised by an airline, consolidator, travel supplier, or reservation system before final ticket issuance.</p>
<p>If a fare changes before ticketing, Destination Fareways will make reasonable efforts to inform you of the new price. At that point, you may choose to continue with the updated fare or cancel the booking request. If the fare increases before ticketing and before your card is charged, you may cancel that booking request without being charged by Destination Fareways for that fare increase.</p>

<h2>Payment Policy Acceptance</h2>
<p>All prices displayed or quoted by Destination Fareways are generally shown in U.S. dollars unless stated otherwise. The final amount may include airfare, taxes, airline-imposed charges, supplier charges, and applicable service fees.</p>
<ul>
    <li>Destination Fareways may split a total charge into separate components, such as airline base fare, taxes, supplier charges, or agency service fees, while keeping the combined authorized total the same as the amount quoted at booking.</li>
    <li>Quoted prices do not usually include optional airline baggage fees, seat selection fees, priority boarding, meals, upgrades, special assistance charges, or other ancillary services unless clearly stated in writing.</li>
    <li>Tickets are guaranteed only after ticketing is completed and an e-ticket number or confirmed ticket document is issued.</li>
    <li>Submitting payment information does not by itself guarantee ticket issuance, fare availability, seat availability, or airline confirmation.</li>
    <li>If your credit card, debit card, or payment method fails, is declined, or requires verification, Destination Fareways may contact you for updated payment details or additional authorization.</li>
</ul>

<h2>Third-Party and International Card Payments</h2>
<p>If you use an international credit card, international debit card, or a card belonging to another person to purchase airline tickets for yourself or another traveler, additional verification may be required before e-tickets can be processed.</p>
<p>Required documents may include a completed credit card authorization form, a government-issued photo ID for the cardholder, and front and back copies of the payment card with sensitive numbers masked except the required identifying digits. Destination Fareways may refuse or delay ticketing when payment authorization, fraud screening, or identity verification cannot be completed.</p>

<h2>Booking Confirmation and Ticketing</h2>
<p>A booking request is not the same as a confirmed ticket. Airline inventory changes frequently, and confirmation depends on successful payment, fare availability, supplier acceptance, and completion of ticketing. Destination Fareways is not responsible for a fare, seat, route, or schedule becoming unavailable before ticketing is completed.</p>
<p>Please review all passenger names, travel dates, origin and destination airports, cabin class, layovers, baggage rules, and fare restrictions before authorizing payment. You are responsible for confirming that the itinerary matches your travel needs before ticket issuance.</p>

<h2>Cancellations, Refunds, and Changes</h2>
<p>Cancellation, refund, exchange, credit, and change requests are governed by the fare rules of the airline or travel supplier. Many discounted, promotional, basic economy, and special fares may be non-refundable, non-transferable, or subject to strict penalties.</p>
<p>When a change or cancellation is permitted, airline penalties, fare differences, supplier fees, and Destination Fareways service fees may apply. Refund processing times are controlled by the airline, supplier, card network, and issuing bank. Destination Fareways may assist with eligible requests but cannot guarantee approval when the operating airline or supplier denies a refund, credit, or exchange.</p>

<h2>Traveler Documents and Travel Requirements</h2>
<p>You are responsible for obtaining and carrying all documents required for travel, including passports, visas, transit permits, health documents, identification, vaccination records, and any documents required by an airline, airport, destination country, or government authority.</p>
<p>Destination Fareways may provide general travel guidance, but you are responsible for verifying entry, transit, identification, and health requirements directly with the relevant airline, embassy, consulate, airport, or government agency before travel.</p>

<h2>Airline and Supplier Rules</h2>
<p>All airline tickets are subject to the operating carrier's contract of carriage and the rules of the ticketing airline, codeshare partners, and travel suppliers. Destination Fareways is not responsible for airline schedule changes, cancellations, aircraft changes, missed connections, baggage handling, boarding decisions, denied boarding, weather delays, strikes, airport disruptions, or other matters controlled by airlines or third-party suppliers.</p>

<h2>Website Use and Restrictions</h2>
<p>You agree to use Destination Fareways only for lawful personal or business travel purposes. You may not misuse the website, copy content for commercial resale, attempt unauthorized access, interfere with website operations, scrape fare data through automated tools, submit false booking information, or use the website in a way that may harm Destination Fareways, other users, airlines, or travel suppliers.</p>

<h2>Limitation of Liability</h2>
<p>Destination Fareways acts as a travel service and booking assistance provider. To the fullest extent permitted by law, Destination Fareways is not liable for indirect, incidental, special, punitive, or consequential losses arising from airline actions, supplier decisions, travel disruptions, fare changes, payment delays, document issues, or your use of this website.</p>

<h2>Updates to These Terms</h2>
<p>Destination Fareways may update these Terms &amp; Conditions at any time to reflect changes in our services, airline rules, supplier requirements, legal obligations, or business practices. Updated terms become effective when posted on this page. Continued use of the website after changes are posted means you accept the revised terms.</p>

<h2>Contact Destination Fareways</h2>
<p>If you have questions about these Terms &amp; Conditions, fare rules, booking requirements, payment verification, cancellations, or refunds, please contact Destination Fareways through our official contact page before completing your booking request.</p>
HTML
            ];
        }

        $seoData = (object) [
            'meta_title' => $page->seo_title ?? 'Terms & Conditions | Destination Fareways Flight Booking Rules',
            'meta_description' => $page->seo_description ?? 'Read Destination Fareways terms and conditions covering flight booking rules, fare changes, payments, traveler names, cancellations, refunds, and airline policies.',
            'meta_keywords' => $page->seo_keywords ?? 'Destination Fareways terms and conditions, flight booking terms, airfare payment policy, traveler name policy, airline fare rules, cancellation refund terms',
            'og_title' => $page->og_title ?? $page->title,
            'og_description' => $page->og_description ?? 'Review Destination Fareways booking terms, fare rules, payment requirements, cancellations, refunds, and traveler responsibilities.',
            'og_image' => $page->og_image ?? null,
            'canonical_url' => route('terms'),
            'robots_tag' => 'index, follow',
            'schema_markup' => json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => 'Terms & Conditions',
                'url' => route('terms'),
                'description' => 'Destination Fareways terms and conditions for flight booking services, fare rules, payments, cancellations, refunds, and traveler responsibilities.',
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'Destination Fareways',
                    'url' => url('/'),
                ],
            ], JSON_UNESCAPED_SLASHES)
        ];

        $breadcrumbs = [
            ['title' => 'Terms & Conditions']
        ];

        return view('frontend.terms-conditions', compact('settings', 'page', 'seoData', 'breadcrumbs'));
    }
}
