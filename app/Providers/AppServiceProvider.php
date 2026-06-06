<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\CallSetting;
use App\Models\SchemaSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.frontend', function ($view) {
            $settings    = Setting::first();
            $callSettings = CallSetting::first();

            $view->with('settings', $settings);
            $view->with('callSettings', $callSettings);
            $view->with('globalSchemas', $this->buildGlobalSchemas($settings));
        });
    }

    /**
     * Build Organization + WebSite JSON-LD arrays from site settings.
     * These are injected on every frontend page so individual controllers
     * don't need to pass them.
     */
    private function buildGlobalSchemas(?Setting $settings): array
    {
        $siteName = $settings?->site_name ?? config('app.name', 'Destination Fareways');
        $siteUrl  = rtrim(config('app.url', url('/')), '/');
        $logoUrl  = $settings?->logo ? asset('storage/' . $settings->logo) : null;

        $sameAs = array_values(array_filter([
            $settings?->social_facebook,
            $settings?->social_twitter,
            $settings?->social_instagram,
            $settings?->social_linkedin,
            $settings?->social_youtube,
        ]));

        $organization = array_filter([
            '@context' => 'https://schema.org',
            '@type'    => 'TravelAgency',
            'name'     => $siteName,
            'url'      => $siteUrl,
            'logo'     => $logoUrl ? [
                '@type' => 'ImageObject',
                'url'   => $logoUrl,
            ] : null,
            'telephone' => $settings?->primary_phone ?? null,
            'email'     => $settings?->primary_email ?? null,
            'address'   => array_filter([
                '@type'           => 'PostalAddress',
                'streetAddress'   => $settings?->address ?? null,
                'addressLocality' => $settings?->city ?? null,
                'addressRegion'   => $settings?->state ?? null,
                'postalCode'      => $settings?->zip ?? null,
                'addressCountry'  => $settings?->country ?? 'US',
            ]),
            'sameAs' => $sameAs ?: null,
        ]);

        $website = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => $siteName,
            'url'      => $siteUrl,
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => $siteUrl . '/flights/search?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

        return [$organization, $website];
    }
}
