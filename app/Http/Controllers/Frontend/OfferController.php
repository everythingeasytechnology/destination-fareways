<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Offer;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a paginated list of Flight Offers
     */
    public function index(Request $request)
    {
        $settings = Setting::first();
        $seoData = SeoSetting::where('page_identifier', 'offers')->first();
        
        $query = Offer::where('status', 'active')->orderBy('sort_order');

        // Apply filters based on request
        $type = $request->input('type'); // 'domestic', 'international', 'business'
        if ($type === 'domestic') {
            $query->where(function($q) {
                $q->where('to_city', 'NOT LIKE', '%London%')
                  ->where('to_city', 'NOT LIKE', '%Paris%')
                  ->where('to_city', 'NOT LIKE', '%Tokyo%');
            });
        } elseif ($type === 'international') {
            $query->where(function($q) {
                $q->where('to_city', 'LIKE', '%London%')
                  ->orWhere('to_city', 'LIKE', '%Paris%')
                  ->orWhere('to_city', 'LIKE', '%Tokyo%')
                  ->orWhere('title', 'LIKE', '%Europe%')
                  ->orWhere('title', 'LIKE', '%International%');
            });
        } elseif ($type === 'business') {
            $query->where('title', 'LIKE', '%Business%')
                  ->orWhere('short_desc', 'LIKE', '%Business%')
                  ->orWhere('subtitle', 'LIKE', '%Business%');
        }

        $offers = $query->paginate(9)->withQueryString();

        $breadcrumbs = [
            ['title' => 'Offers', 'url' => route('offers.index')]
        ];

        return view('frontend.offers.index', compact('settings', 'offers', 'seoData', 'breadcrumbs'));
    }

    /**
     * Display a Single Flight Offer Details
     */
    public function show($slug)
    {
        $settings = Setting::first();
        
        $offer = Offer::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Fetch related offers excluding the current one
        $relatedOffers = Offer::where('status', 'active')
            ->where('id', '!=', $offer->id)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        // Dynamic $seoData matching the model details
        $seoData = (object) [
            'meta_title' => $offer->seo_title ?? $offer->title . ' - Destination Fareways',
            'meta_description' => $offer->seo_description ?? $offer->short_desc,
            'meta_keywords' => $offer->seo_keywords ?? 'flight offer, discount tickets, promo flight, ' . $offer->airline,
            'og_title' => $offer->seo_title ?? $offer->title,
            'og_description' => $offer->seo_description ?? $offer->short_desc,
            'og_image' => $offer->og_image ?? $offer->image,
            'canonical_url' => route('offers.show', $offer->slug),
            'robots_tag' => 'index, follow'
        ];

        $breadcrumbs = [
            ['title' => 'Offers', 'url' => route('offers.index')],
            ['title' => $offer->title]
        ];

        return view('frontend.offers.show', compact('settings', 'offer', 'relatedOffers', 'seoData', 'breadcrumbs'));
    }
}
