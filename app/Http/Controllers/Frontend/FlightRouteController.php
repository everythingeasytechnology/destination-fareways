<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FlightRoute;
use App\Models\SeoSetting;
use App\Models\Setting;
use Illuminate\Support\Str;

class FlightRouteController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $dbSeo    = SeoSetting::where('page_identifier', 'flight_routes')->first();

        $domesticRoutes      = FlightRoute::where('status', 'active')->where('is_domestic', true)->orderBy('sort_order')->orderBy('title')->get();
        $internationalRoutes = FlightRoute::where('status', 'active')->where('is_domestic', false)->orderBy('sort_order')->orderBy('title')->get();

        $totalCount = $domesticRoutes->count() + $internationalRoutes->count();

        // Fallback seoData when admin hasn't configured it yet
        $seoData = $dbSeo ?? (object) [
            'meta_title'       => "Flight Routes & Cheap Airfare Deals | Destination Fareways",
            'meta_description' => "Browse {$totalCount}+ flight routes from major US cities and international destinations. Find cheap fares, book online or call our 24/7 team.",
            'meta_keywords'    => 'cheap flights, flight routes, airfare deals, domestic flights USA, international flights',
            'og_title'         => 'Popular Flight Routes | Destination Fareways',
            'og_description'   => "Explore {$totalCount}+ domestic and international flight corridors with real pricing and booking support.",
            'og_image'         => null,
            'canonical_url'    => route('flight-routes.index'),
            'robots_tag'       => 'index,follow',
            'schema_markup'    => null,
        ];

        $breadcrumbs = [['title' => 'Flight Routes', 'url' => route('flight-routes.index')]];

        return view('frontend.flight-routes.index', compact(
            'settings', 'seoData', 'domesticRoutes', 'internationalRoutes', 'breadcrumbs'
        ));
    }

    public function show(string $slug)
    {
        $route    = FlightRoute::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $settings = Setting::first();

        $related = FlightRoute::where('status', 'active')
            ->where('id', '!=', $route->id)
            ->where('is_domestic', $route->is_domestic)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $siteName = $settings->site_name ?? config('app.name', 'Destination Fareways');

        // ── Meta Title ────────────────────────────────────────────────────────
        // Avoid "from $0" when price is not set
        if ($route->seo_title) {
            $metaTitle = $route->seo_title;
        } elseif ($route->starting_price > 0) {
            $metaTitle = "Flights from {$route->origin_city} to {$route->destination_city} from \$" . number_format($route->starting_price, 0) . " | {$siteName}";
        } else {
            $metaTitle = "Cheap Flights from {$route->origin_city} to {$route->destination_city} | {$siteName}";
        }

        // ── Meta Description (capped at 155 chars) ────────────────────────────
        $rawDesc = $route->seo_description
            ?? $route->short_desc
            ?? "Find cheap flights from {$route->origin_city} ({$route->origin_airport_code}) to {$route->destination_city} ({$route->destination_airport_code}). Compare fares and book with Destination Fareways — 24/7 support.";
        $metaDesc = Str::limit(strip_tags($rawDesc), 155, '…');

        // ── Canonical URL ─────────────────────────────────────────────────────
        $canonicalUrl = $route->canonical_url ?? route('flight-routes.show', $route->slug);

        // ── OG Image ─────────────────────────────────────────────────────────
        $ogImage = $route->og_image ?? $route->featured_image;

        // ── BreadcrumbList JSON-LD ────────────────────────────────────────────
        $breadcrumbSchema = '<script type="application/ld+json">' . json_encode([
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',         'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Flight Routes', 'item' => route('flight-routes.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $route->title,  'item' => $canonicalUrl],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';

        // ── WebPage + Service JSON-LD ─────────────────────────────────────────
        $webPageSchema = array_filter([
            '@context'     => 'https://schema.org',
            '@type'        => 'WebPage',
            'name'         => $metaTitle,
            'description'  => $metaDesc,
            'url'          => $canonicalUrl,
            'dateModified' => $route->updated_at->toIso8601String(),
            'breadcrumb'   => [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',         'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Flight Routes', 'item' => route('flight-routes.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $route->title,  'item' => $canonicalUrl],
                ],
            ],
        ]);

        $serviceSchema = array_filter([
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'name'        => $route->title,
            'description' => $metaDesc,
            'url'         => $canonicalUrl,
            'provider'    => [
                '@type' => 'TravelAgency',
                'name'  => $siteName,
                'url'   => rtrim(config('app.url', url('/')), '/'),
            ],
            'areaServed'  => array_filter([
                $route->origin_city,
                $route->destination_city,
            ]),
            'offers' => $route->starting_price > 0 ? [
                '@type'         => 'Offer',
                'price'         => number_format($route->starting_price, 2, '.', ''),
                'priceCurrency' => 'USD',
                'availability'  => 'https://schema.org/InStock',
            ] : null,
        ]);

        $schemaMarkup = '<script type="application/ld+json">' . json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
        $schemaMarkup .= "\n" . '<script type="application/ld+json">' . json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';

        // ── FAQ JSON-LD (safe, via json_encode) ──────────────────────────────
        $faqSchema = null;
        if ($route->faq_schema) {
            $faqItems = json_decode($route->faq_schema, true);
            if (is_array($faqItems)) {
                $validFaqs = array_values(array_filter($faqItems, fn($f) => !empty($f['question'])));
                if (!empty($validFaqs)) {
                    $faqSchema = '<script type="application/ld+json">' . json_encode([
                        '@context'   => 'https://schema.org',
                        '@type'      => 'FAQPage',
                        'mainEntity' => array_map(fn($f) => [
                            '@type'          => 'Question',
                            'name'           => $f['question'],
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text'  => $f['answer'] ?? '',
                            ],
                        ], $validFaqs),
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
                }
            }
        }

        // ── Final seoData object ──────────────────────────────────────────────
        $seoData = (object) [
            'meta_title'         => $metaTitle,
            'meta_description'   => $metaDesc,
            'meta_keywords'      => $route->seo_keywords ?? "{$route->origin_city} to {$route->destination_city} flights, cheap flights {$route->origin_airport_code} {$route->destination_airport_code}, {$route->title}",
            'og_title'           => $route->seo_title ?? $route->title,
            'og_description'     => Str::limit(strip_tags($route->seo_description ?? $route->short_desc ?? $metaDesc), 155, '…'),
            'og_image'           => $ogImage,
            'twitter_title'      => $route->seo_title ?? $route->title,
            'twitter_description'=> Str::limit(strip_tags($route->seo_description ?? $route->short_desc ?? $metaDesc), 155, '…'),
            'twitter_image'      => $ogImage,
            'canonical_url'      => $canonicalUrl,
            'robots_tag'         => 'index,follow',
            'schema_markup'      => $schemaMarkup,
            'faq_schema'         => $faqSchema,
            'breadcrumb_schema'  => $breadcrumbSchema,
        ];

        $breadcrumbs = [
            ['title' => 'Flight Routes', 'url' => route('flight-routes.index')],
            ['title' => $route->title],
        ];

        return view('frontend.flight-routes.show', compact(
            'route', 'settings', 'related', 'seoData', 'breadcrumbs'
        ));
    }
}
