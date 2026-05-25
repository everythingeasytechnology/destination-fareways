<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Destination;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    /**
     * Display a List of all active Destinations grouped by domestic flag
     */
    public function index()
    {
        $settings = Setting::first();
        $seoData = SeoSetting::where('page_identifier', 'destinations')->first();

        // Fetch all active destinations
        $destinations = Destination::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        // Group destinations by domestic field
        $domesticDestinations = $destinations->where('is_domestic', true);
        $internationalDestinations = $destinations->where('is_domestic', false);

        $breadcrumbs = [
            ['title' => 'Destinations', 'url' => route('destinations.index')]
        ];

        return view('frontend.destinations.index', compact(
            'settings',
            'domesticDestinations',
            'internationalDestinations',
            'seoData',
            'breadcrumbs'
        ));
    }

    /**
     * Display a Single Destination Post Details
     */
    public function show($slug)
    {
        $settings = Setting::first();

        $destination = Destination::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Dynamic SEO Details mapping model contents
        $seoData = (object) [
            'meta_title' => $destination->seo_title ?? 'Flights to ' . $destination->name . ' - starting from $' . $destination->starting_price,
            'meta_description' => $destination->seo_description ?? $destination->short_desc,
            'meta_keywords' => $destination->seo_keywords ?? 'flights to ' . $destination->name . ', ' . $destination->airport_code . ' cheap flights, travel to ' . $destination->name,
            'og_title' => $destination->seo_title ?? $destination->name,
            'og_description' => $destination->seo_description ?? $destination->short_desc,
            'og_image' => $destination->og_image ?? $destination->featured_image,
            'canonical_url' => route('destinations.show', $destination->slug),
            'robots_tag' => 'index, follow',
            'schema_markup' => $destination->schema_markup ?? null
        ];

        $breadcrumbs = [
            ['title' => 'Destinations', 'url' => route('destinations.index')],
            ['title' => $destination->name]
        ];

        return view('frontend.destinations.show', compact('settings', 'destination', 'seoData', 'breadcrumbs'));
    }
}
