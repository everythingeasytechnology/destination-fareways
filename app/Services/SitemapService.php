<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Destination;
use App\Models\FlightRoute;
use App\Models\Offer;
use App\Models\Page;

class SitemapService
{
    /**
     * Generate sitemap index pointing to all sub-sitemaps.
     * Replaces the old single-file generate() method.
     */
    public function generateIndex(): string
    {
        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->subSitemaps() as $entry) {
            $xml[] = '  <sitemap>';
            $xml[] = '    <loc>' . e($entry['loc']) . '</loc>';
            $xml[] = '    <lastmod>' . $entry['lastmod'] . '</lastmod>';
            $xml[] = '  </sitemap>';
        }

        $xml[] = '</sitemapindex>';

        return implode("\n", $xml);
    }

    /**
     * Static + CMS pages sitemap.
     */
    public function generatePages(): string
    {
        $lines = $this->openUrlset();

        foreach ($this->staticPages() as $routeName => $settings) {
            $lines[] = $this->urlEntry(
                route($routeName),
                now()->toDateString(),
                $settings['changefreq'],
                $settings['priority']
            );
        }

        Page::where('status', 'active')
            ->whereNotIn('slug', $this->reservedPageSlugs())
            ->orderBy('updated_at', 'desc')
            ->cursor()
            ->each(function (Page $page) use (&$lines) {
                $lines[] = $this->urlEntry(
                    route('pages.show', $page->slug),
                    $page->updated_at->format('Y-m-d'),
                    'monthly',
                    '0.6'
                );
            });

        $lines[] = '</urlset>';
        return implode("\n", $lines);
    }

    /**
     * Destinations sitemap.
     */
    public function generateDestinations(): string
    {
        $lines = $this->openUrlset();

        Destination::where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->cursor()
            ->each(function (Destination $destination) use (&$lines) {
                $lines[] = $this->urlEntry(
                    route('destinations.show', $destination->slug),
                    $destination->updated_at->format('Y-m-d'),
                    'weekly',
                    '0.8'
                );
            });

        $lines[] = '</urlset>';
        return implode("\n", $lines);
    }

    /**
     * Blog posts sitemap.
     */
    public function generateBlog(): string
    {
        $lines = $this->openUrlset();

        Blog::whereIn('status', ['published', 'active'])
            ->orderBy('updated_at', 'desc')
            ->cursor()
            ->each(function (Blog $blog) use (&$lines) {
                $lines[] = $this->urlEntry(
                    route('blog.show', $blog->slug),
                    ($blog->published_at ?: $blog->updated_at)->format('Y-m-d'),
                    'weekly',
                    '0.8'
                );
            });

        $lines[] = '</urlset>';
        return implode("\n", $lines);
    }

    /**
     * Flight routes sitemap.
     */
    public function generateFlightRoutes(): string
    {
        $lines = $this->openUrlset();

        FlightRoute::where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->cursor()
            ->each(function (FlightRoute $flightRoute) use (&$lines) {
                $lines[] = $this->urlEntry(
                    route('flight-routes.show', $flightRoute->slug),
                    $flightRoute->updated_at->format('Y-m-d'),
                    'weekly',
                    '0.8'
                );
            });

        $lines[] = '</urlset>';
        return implode("\n", $lines);
    }

    /**
     * Offers sitemap.
     */
    public function generateOffers(): string
    {
        $lines = $this->openUrlset();

        Offer::where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->cursor()
            ->each(function (Offer $offer) use (&$lines) {
                $lines[] = $this->urlEntry(
                    route('offers.show', $offer->slug),
                    $offer->updated_at->format('Y-m-d'),
                    'weekly',
                    '0.8'
                );
            });

        $lines[] = '</urlset>';
        return implode("\n", $lines);
    }

    /**
     * Recent URLs for admin panel preview (10 most recent across all types).
     * Uses COUNT queries + small LIMIT fetches — no full table loads.
     */
    public function recentUrls(): array
    {
        $urls = [];

        Offer::where('status', 'active')->orderBy('updated_at', 'desc')->take(3)->get()
            ->each(function (Offer $offer) use (&$urls) {
                $urls[] = [
                    'loc'        => route('offers.show', $offer->slug),
                    'lastmod'    => $offer->updated_at->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                    'type'       => 'Offer',
                ];
            });

        Blog::whereIn('status', ['published', 'active'])->orderBy('updated_at', 'desc')->take(3)->get()
            ->each(function (Blog $blog) use (&$urls) {
                $urls[] = [
                    'loc'        => route('blog.show', $blog->slug),
                    'lastmod'    => ($blog->published_at ?: $blog->updated_at)->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                    'type'       => 'Blog',
                ];
            });

        Destination::where('status', 'active')->orderBy('updated_at', 'desc')->take(4)->get()
            ->each(function (Destination $destination) use (&$urls) {
                $urls[] = [
                    'loc'        => route('destinations.show', $destination->slug),
                    'lastmod'    => $destination->updated_at->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                    'type'       => 'Destination',
                ];
            });

        FlightRoute::where('status', 'active')->orderBy('updated_at', 'desc')->take(3)->get()
            ->each(function (FlightRoute $flightRoute) use (&$urls) {
                $urls[] = [
                    'loc'        => route('flight-routes.show', $flightRoute->slug),
                    'lastmod'    => $flightRoute->updated_at->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                    'type'       => 'Flight Route',
                ];
            });

        usort($urls, fn($a, $b) => strcmp($b['lastmod'], $a['lastmod']));

        return array_slice($urls, 0, 10);
    }

    /**
     * Summary counts for admin panel (COUNT queries only — no full loads).
     */
    public function summary(): array
    {
        $staticCount  = count($this->staticPages());
        $offersCount  = Offer::where('status', 'active')->count();
        $blogsCount   = Blog::whereIn('status', ['published', 'active'])->count();
        $destCount    = Destination::where('status', 'active')->count();
        $routesCount  = FlightRoute::where('status', 'active')->count();
        $pagesCount   = Page::where('status', 'active')
            ->whereNotIn('slug', $this->reservedPageSlugs())
            ->count();

        return [
            'total'          => $staticCount + $offersCount + $blogsCount + $destCount + $routesCount + $pagesCount,
            'static'         => $staticCount,
            'offers'         => $offersCount,
            'blogs'          => $blogsCount,
            'destinations'   => $destCount,
            'flight_routes'  => $routesCount,
            'pages'          => $pagesCount,
            'latest_lastmod' => now()->toDateString(),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function subSitemaps(): array
    {
        return [
            ['loc' => url('/sitemap-pages.xml'),         'lastmod' => now()->toDateString()],
            ['loc' => url('/sitemap-destinations.xml'),  'lastmod' => now()->toDateString()],
            ['loc' => url('/sitemap-blog.xml'),          'lastmod' => now()->toDateString()],
            ['loc' => url('/sitemap-offers.xml'),        'lastmod' => now()->toDateString()],
            ['loc' => url('/sitemap-flight-routes.xml'), 'lastmod' => now()->toDateString()],
        ];
    }

    private function openUrlset(): array
    {
        return [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];
    }

    private function urlEntry(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        return "  <url>\n    <loc>" . e($loc) . "</loc>\n    <lastmod>{$lastmod}</lastmod>\n    <changefreq>{$changefreq}</changefreq>\n    <priority>{$priority}</priority>\n  </url>";
    }

    private function staticPages(): array
    {
        return [
            'home'                => ['priority' => '1.0', 'changefreq' => 'daily'],
            'flights.search'      => ['priority' => '0.8', 'changefreq' => 'daily'],
            'booking.enquiry'     => ['priority' => '0.8', 'changefreq' => 'daily'],
            'offers.index'        => ['priority' => '0.9', 'changefreq' => 'daily'],
            'blog.index'          => ['priority' => '0.9', 'changefreq' => 'daily'],
            'destinations.index'  => ['priority' => '0.9', 'changefreq' => 'daily'],
            'flight-routes.index' => ['priority' => '0.9', 'changefreq' => 'daily'],
            'about'               => ['priority' => '0.7', 'changefreq' => 'monthly'],
            'contact'             => ['priority' => '0.8', 'changefreq' => 'monthly'],
            'faq'                 => ['priority' => '0.7', 'changefreq' => 'monthly'],
            'privacy'             => ['priority' => '0.5', 'changefreq' => 'monthly'],
            'terms'               => ['priority' => '0.5', 'changefreq' => 'monthly'],
            'editorial-policy'    => ['priority' => '0.5', 'changefreq' => 'yearly'],
        ];
    }

    private function reservedPageSlugs(): array
    {
        return [
            'about', 'admin', 'blog', 'booking-enquiry', 'contact',
            'destinations', 'editorial-policy', 'faq', 'flight-routes', 'flights', 'lead',
            'newsletter', 'offers', 'privacy-policy', 'robots.txt',
            'sitemap.xml', 'terms-conditions',
        ];
    }
}
