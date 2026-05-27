<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;

class SitemapController extends Controller
{
    public function index(SitemapService $sitemapService)
    {
        $summary = $sitemapService->summary();
        $recentUrls = $sitemapService->urls()->sortByDesc('lastmod')->take(10);

        return view('admin.sitemap.index', compact('summary', 'recentUrls'));
    }

    public function refresh(SitemapService $sitemapService)
    {
        $summary = $sitemapService->summary();

        return redirect()
            ->route('admin.sitemap.index')
            ->with('success', 'Sitemap refreshed successfully. Total URLs: ' . $summary['total']);
    }
}
