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

        $breadcrumbs = [
            ['title' => $page->title]
        ];

        return view('frontend.page', compact('settings', 'page', 'seoData', 'breadcrumbs'));
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
                'subtitle' => 'Your Privacy is Our Priority',
                'content' => '<h2>1. Information We Collect</h2><p>We collect information you provide directly to us when searching for flights, submitting booking enquiries, subscribing to our newsletter, or communicating with our team. This includes your name, email, phone, trip details, and payment preferences.</p><h2>2. How We Use Your Information</h2><p>We use the collected information to process flight reservations, reply to contact queries, send promotional campaigns, prevent fraud, and customize your travel booking experience.</p><h2>3. Data Sharing & Security</h2><p>We do not sell your personal data. We share details only with contracted airlines and travel operators required to issue your tickets. High-grade security systems are deployed to protect your transactional data.</p>'
            ];
        }

        $seoData = (object) [
            'meta_title' => $page->seo_title ?? 'Privacy Policy | Destination Fareways',
            'meta_description' => $page->seo_description ?? 'Read our privacy policy to understand how we collect, use, and secure your personal booking information.',
            'meta_keywords' => $page->seo_keywords ?? 'privacy policy, travel agency data security',
            'og_title' => $page->og_title ?? $page->title,
            'og_description' => $page->og_description ?? 'Read our privacy policy.',
            'og_image' => $page->og_image ?? null,
            'canonical_url' => route('privacy'),
            'robots_tag' => 'index, follow'
        ];

        $breadcrumbs = [
            ['title' => 'Privacy Policy']
        ];

        return view('frontend.privacy-policy', compact('settings', 'page', 'seoData', 'breadcrumbs'));
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
                'subtitle' => 'Agreement Between User & Destination Fareways',
                'content' => '<h2>1. Use of Website</h2><p>By accessing or searching flight inventory on our website, you agree to comply with these terms, all applicable laws, and airline booking agreements. You warrant that all supplied information is accurate and that you possess legal authority to initiate bookings.</p><h2>2. Airline Policies & Fees</h2><p>All flight bookings are subject to the specific operating carrier’s contract of carriage. Cancelations, baggage rates, and change fees are mandated directly by the airlines, not by our agency. Seat assignments and upgrades are subject to availability.</p><h2>3. Limitation of Liability</h2><p>We act solely as an intermediary reservation agent. We are not liable for flight delays, cancellations, schedule adjustments, or service disruptions caused by weather, labor disputes, or direct airline operational issues.</p>'
            ];
        }

        $seoData = (object) [
            'meta_title' => $page->seo_title ?? 'Terms & Conditions | Destination Fareways',
            'meta_description' => $page->seo_description ?? 'Read our terms of service and agreement guidelines for searching and booking airline tickets.',
            'meta_keywords' => $page->seo_keywords ?? 'terms of service, booking agreement, travel agency terms',
            'og_title' => $page->og_title ?? $page->title,
            'og_description' => $page->og_description ?? 'Read our terms and conditions.',
            'og_image' => $page->og_image ?? null,
            'canonical_url' => route('terms'),
            'robots_tag' => 'index, follow'
        ];

        $breadcrumbs = [
            ['title' => 'Terms & Conditions']
        ];

        return view('frontend.terms-conditions', compact('settings', 'page', 'seoData', 'breadcrumbs'));
    }
}
