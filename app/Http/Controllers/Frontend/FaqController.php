<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Faq;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display the FAQ page grouped by categories
     */
    public function index()
    {
        $settings = Setting::first();
        $seoData = SeoSetting::where('page_identifier', 'faq')->first();

        // Fetch all active FAQs
        $faqs = Faq::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        // Get unique categories from active FAQs
        $categories = $faqs->pluck('category')->unique()->filter();

        // Group by category
        $groupedFaqs = $faqs->groupBy('category');

        $breadcrumbs = [
            ['title' => 'FAQ']
        ];

        // Format FAQ items into a structured FAQ Schema dynamically for the header
        $faqSchemaJson = null;
        if ($faqs->isNotEmpty()) {
            $elements = [];
            foreach ($faqs->take(8) as $faq) {
                $elements[] = [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => strip_tags($faq->answer)
                    ]
                ];
            }
            $faqSchemaJson = json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $elements
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        if ($seoData) {
            $seoData->faq_schema = $faqSchemaJson;
        }

        return view('frontend.faq', compact('settings', 'groupedFaqs', 'categories', 'seoData', 'breadcrumbs'));
    }
}
