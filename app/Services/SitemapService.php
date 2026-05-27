<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Destination;
use App\Models\Offer;
use Illuminate\Support\Collection;

class SitemapService
{
    public function generate(): string
    {
        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($this->urls() as $url) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . e($url['loc']) . '</loc>';
            $xml[] = '    <lastmod>' . $url['lastmod'] . '</lastmod>';
            $xml[] = '    <changefreq>' . $url['changefreq'] . '</changefreq>';
            $xml[] = '    <priority>' . $url['priority'] . '</priority>';
            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        return implode("\n", $xml);
    }

    public function urls(): Collection
    {
        $urls = collect();

        foreach ($this->staticPages() as $routeName => $settings) {
            $urls->push([
                'loc' => route($routeName),
                'lastmod' => now()->toDateString(),
                'changefreq' => $settings['changefreq'],
                'priority' => $settings['priority'],
                'type' => 'Static Page',
            ]);
        }

        Offer::where('status', 'active')->orderBy('updated_at', 'desc')->get()->each(function (Offer $offer) use ($urls) {
            $urls->push([
                'loc' => route('offers.show', $offer->slug),
                'lastmod' => $offer->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
                'type' => 'Offer',
            ]);
        });

        Blog::where('status', 'published')->orderBy('updated_at', 'desc')->get()->each(function (Blog $blog) use ($urls) {
            $urls->push([
                'loc' => route('blog.show', $blog->slug),
                'lastmod' => ($blog->published_at ?: $blog->updated_at)->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
                'type' => 'Blog',
            ]);
        });

        Destination::where('status', 'active')->orderBy('updated_at', 'desc')->get()->each(function (Destination $destination) use ($urls) {
            $urls->push([
                'loc' => route('destinations.show', $destination->slug),
                'lastmod' => $destination->updated_at->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
                'type' => 'Destination',
            ]);
        });

        return $urls;
    }

    public function summary(): array
    {
        $urls = $this->urls();

        return [
            'total' => $urls->count(),
            'static' => $urls->where('type', 'Static Page')->count(),
            'offers' => $urls->where('type', 'Offer')->count(),
            'blogs' => $urls->where('type', 'Blog')->count(),
            'destinations' => $urls->where('type', 'Destination')->count(),
            'latest_lastmod' => $urls->max('lastmod'),
        ];
    }

    private function staticPages(): array
    {
        return [
            'home' => ['priority' => '1.0', 'changefreq' => 'daily'],
            'flights.search' => ['priority' => '0.8', 'changefreq' => 'daily'],
            'booking.enquiry' => ['priority' => '0.8', 'changefreq' => 'daily'],
            'offers.index' => ['priority' => '0.9', 'changefreq' => 'daily'],
            'blog.index' => ['priority' => '0.9', 'changefreq' => 'daily'],
            'destinations.index' => ['priority' => '0.9', 'changefreq' => 'daily'],
            'about' => ['priority' => '0.7', 'changefreq' => 'daily'],
            'contact' => ['priority' => '0.8', 'changefreq' => 'daily'],
            'faq' => ['priority' => '0.7', 'changefreq' => 'daily'],
            'privacy' => ['priority' => '0.5', 'changefreq' => 'daily'],
            'terms' => ['priority' => '0.5', 'changefreq' => 'daily'],
        ];
    }
}
