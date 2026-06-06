<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;

class SitemapController extends Controller
{
    private function xml(string $content): \Illuminate\Http\Response
    {
        return response($content, 200, ['Content-Type' => 'text/xml; charset=UTF-8']);
    }

    /** Sitemap index — points to all sub-sitemaps */
    public function index(SitemapService $sitemapService)
    {
        return $this->xml($sitemapService->generateIndex());
    }

    /** Static + CMS pages */
    public function pages(SitemapService $sitemapService)
    {
        return $this->xml($sitemapService->generatePages());
    }

    /** Destination pages */
    public function destinations(SitemapService $sitemapService)
    {
        return $this->xml($sitemapService->generateDestinations());
    }

    /** Blog posts */
    public function blog(SitemapService $sitemapService)
    {
        return $this->xml($sitemapService->generateBlog());
    }

    /** Offer pages */
    public function offers(SitemapService $sitemapService)
    {
        return $this->xml($sitemapService->generateOffers());
    }

    /** Flight route pages */
    public function flightRoutes(SitemapService $sitemapService)
    {
        return $this->xml($sitemapService->generateFlightRoutes());
    }

    /** robots.txt */
    public function robots()
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
