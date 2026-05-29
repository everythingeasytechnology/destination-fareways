<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\CallSetting;
use App\Models\Offer;
use App\Models\Destination;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\SeoSetting;
use App\Models\SchemaSetting;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Display the Frontend Homepage
     */
    public function index()
    {
        $settings = Setting::first();
        $callSettings = CallSetting::first();
        
        // Featured Flight Offers (take 3)
        $featuredOffers = Offer::where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(3)
            ->get();
            
        // Featured Destinations (take 6)
        $featuredDestinations = Destination::where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();
            
        // Latest published blogs (take 3)
        $featuredBlogs = Blog::whereIn('status', ['published', 'active'])
            ->orderByDesc('is_featured')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
            
        // Active Testimonials
        $testimonials = Testimonial::where('status', 'active')
            ->orderBy('sort_order')
            ->get();
            
        // Active FAQs for Home Page (or fallback to FAQ page if home is empty)
        $homeFaqs = Faq::where('status', 'active')
            ->where('page_slug', 'home')
            ->orderBy('sort_order')
            ->get();
            
        if ($homeFaqs->isEmpty()) {
            $homeFaqs = Faq::where('status', 'active')
                ->orderBy('sort_order')
                ->take(6)
                ->get();
        }
        
        // SEO Meta Data for Home
        $seoData = SeoSetting::where('page_identifier', 'home')->first();
        
        // Active Schema settings
        $schemas = SchemaSetting::where('status', 'active')->get();

        return view('frontend.home', compact(
            'settings',
            'callSettings',
            'featuredOffers',
            'featuredDestinations',
            'featuredBlogs',
            'testimonials',
            'homeFaqs',
            'seoData',
            'schemas'
        ));
    }

    /**
     * Submit a Quick Lead Form via AJAX
     */
    public function submitLead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:30',
            'from_city' => 'nullable|string|max:100',
            'to_city' => 'nullable|string|max:100',
            'travel_date' => 'nullable|date',
            'passengers' => 'nullable|integer|min:1',
            'cabin_class' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $lead = Lead::create(array_merge($request->all(), [
                'trip_type' => $request->input('trip_type', 'one-way'),
                'source_page' => 'Homepage Quick Form',
                'ip' => $request->ip(),
                'status' => 'new'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Your lead request has been received. Our flight experts will call you shortly!',
                'lead_id' => $lead->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving your request. Please try calling us directly.'
            ], 500);
        }
    }
}
