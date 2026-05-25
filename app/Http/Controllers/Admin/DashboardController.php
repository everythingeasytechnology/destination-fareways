<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\FlightEnquiry;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Blog;
use App\Models\Offer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        // 6 Stat cards counters
        $stats = [
            'leads_count'       => Lead::count(),
            'enquiries_count'   => FlightEnquiry::count(),
            'messages_count'    => ContactMessage::count(),
            'subscribers_count' => NewsletterSubscriber::count(),
            'blogs_count'       => Blog::count(),
            'offers_count'      => Offer::count(),
        ];

        // Recent Data tables
        $recentLeads = Lead::orderBy('created_at', 'desc')->take(10)->get();
        $recentEnquiries = FlightEnquiry::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard.index', compact('stats', 'recentLeads', 'recentEnquiries'));
    }
}
