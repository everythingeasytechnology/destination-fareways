<?php

namespace App\Helpers;

use App\Models\SeoSetting;
use App\Models\SchemaSetting;
use Illuminate\Support\Facades\Schema;

class SeoHelper
{
    /**
     * Get SEO settings for a page.
     */
    public static function get(string $pageIdentifier): ?SeoSetting
    {
        if (!Schema::hasTable('seo_settings')) {
            return null;
        }
        return SeoSetting::where('page_identifier', $pageIdentifier)->first();
    }

    /**
     * Render the SEO Meta Tags.
     */
    public static function renderTags(string $pageIdentifier): string
    {
        $seo = self::get($pageIdentifier);
        if (!$seo) {
            return '<title>' . config('app.name', 'Destination Fareways') . '</title>';
        }

        $html = [];
        $html[] = '<!-- SEO Meta Tags for ' . e($seo->page_name) . ' -->';
        $html[] = '<title>' . e($seo->meta_title ?? $seo->page_name . ' | Destination Fareways') . '</title>';
        
        if ($seo->meta_description) {
            $html[] = '<meta name="description" content="' . e($seo->meta_description) . '">';
        }
        if ($seo->meta_keywords) {
            $html[] = '<meta name="keywords" content="' . e($seo->meta_keywords) . '">';
        }
        if ($seo->canonical_url) {
            $html[] = '<link rel="canonical" href="' . e($seo->canonical_url) . '">';
        }
        if ($seo->robots_tag) {
            $html[] = '<meta name="robots" content="' . e($seo->robots_tag) . '">';
        }

        // OpenGraph Meta
        $html[] = '<!-- OpenGraph Meta Tags -->';
        $html[] = '<meta property="og:type" content="website">';
        $html[] = '<meta property="og:title" content="' . e($seo->og_title ?? $seo->meta_title) . '">';
        if ($seo->og_description ?? $seo->meta_description) {
            $html[] = '<meta property="og:description" content="' . e($seo->og_description ?? $seo->meta_description) . '">';
        }
        if ($seo->og_image) {
            $html[] = '<meta property="og:image" content="' . e(asset('storage/' . $seo->og_image)) . '">';
        }
        $html[] = '<meta property="og:url" content="' . e($seo->canonical_url ?? request()->url()) . '">';
        $html[] = '<meta property="og:site_name" content="Destination Fareways">';

        // Twitter Meta
        $html[] = '<!-- Twitter Card Meta Tags -->';
        $html[] = '<meta name="twitter:card" content="summary_large_image">';
        $html[] = '<meta name="twitter:title" content="' . e($seo->twitter_title ?? $seo->og_title ?? $seo->meta_title) . '">';
        if ($seo->twitter_description ?? $seo->og_description ?? $seo->meta_description) {
            $html[] = '<meta name="twitter:description" content="' . e($seo->twitter_description ?? $seo->og_description ?? $seo->meta_description) . '">';
        }
        if ($seo->twitter_image ?? $seo->og_image) {
            $html[] = '<meta name="twitter:image" content="' . e(asset('storage/' . ($seo->twitter_image ?? $seo->og_image))) . '">';
        }

        return implode("\n    ", $html);
    }

    /**
     * Render the active JSON-LD Schemas for the page.
     */
    public static function renderSchemas(string $pageIdentifier): string
    {
        if (!Schema::hasTable('schema_settings')) {
            return '';
        }

        $schemas = SchemaSetting::where('page_identifier', $pageIdentifier)
            ->where('status', 'active')
            ->get();

        if ($schemas->isEmpty()) {
            return '';
        }

        $html = [];
        foreach ($schemas as $schema) {
            $html[] = '<!-- Schema Markup: ' . e($schema->schema_type) . ' -->';
            $html[] = '<script type="application/ld+json">';
            $html[] = $schema->schema_json;
            $html[] = '</script>';
        }

        return implode("\n", $html);
    }
}
