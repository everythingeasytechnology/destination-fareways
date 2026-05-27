<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml
     */
    public function index(SitemapService $sitemapService)
    {
        return response($sitemapService->generate(), 200, [
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
