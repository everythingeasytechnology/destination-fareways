<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Offer;
use App\Models\Destination;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml
     */
    public function index()
    {
        $blogs = Blog::where('status', 'published')->orderBy('updated_at', 'desc')->get();
        $offers = Offer::where('status', 'active')->orderBy('updated_at', 'desc')->get();
        $destinations = Destination::where('status', 'active')->orderBy('updated_at', 'desc')->get();

        $staticPages = [
            route('home') => '1.0',
            route('flights.search') => '0.8',
            route('booking.enquiry') => '0.8',
            route('offers.index') => '0.9',
            route('blog.index') => '0.9',
            route('destinations.index') => '0.9',
            route('about') => '0.7',
            route('contact') => '0.8',
            route('faq') => '0.7',
            route('privacy') => '0.5',
            route('terms') => '0.5',
        ];

        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // 1. Static Pages
        foreach ($staticPages as $url => $priority) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . $url . '</loc>';
            $xml[] = '    <lastmod>' . date('Y-m-d') . '</lastmod>';
            $xml[] = '    <changefreq>daily</changefreq>';
            $xml[] = '    <priority>' . $priority . '</priority>';
            $xml[] = '  </url>';
        }

        // 2. Flight Offers
        foreach ($offers as $offer) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . route('offers.show', $offer->slug) . '</loc>';
            $xml[] = '    <lastmod>' . $offer->updated_at->format('Y-m-d') . '</lastmod>';
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.8</priority>';
            $xml[] = '  </url>';
        }

        // 3. Blog Posts
        foreach ($blogs as $blog) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . route('blog.show', $blog->slug) . '</loc>';
            $xml[] = '    <lastmod>' . ($blog->published_at ? $blog->published_at->format('Y-m-d') : $blog->updated_at->format('Y-m-d')) . '</lastmod>';
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.8</priority>';
            $xml[] = '  </url>';
        }

        // 4. Destinations
        foreach ($destinations as $dest) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . route('destinations.show', $dest->slug) . '</loc>';
            $xml[] = '    <lastmod>' . $dest->updated_at->format('Y-m-d') . '</lastmod>';
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.8</priority>';
            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        return response(implode("\n", $xml), 200, [
            'Content-Type' => 'text/xml'
        ]);
    }

    /**
     * Generate robots.txt dynamically
     */
    public function robots()
    {
        $content = [];
        $content[] = 'User-agent: *';
        $content[] = 'Allow: /';
        $content[] = 'Disallow: /admin/';
        $content[] = '';
        $content[] = 'Sitemap: ' . url('/sitemap.xml');

        return response(implode("\n", $content), 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}
