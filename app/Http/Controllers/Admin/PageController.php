<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the pages.
     */
    public function index()
    {
        $pages = Page::orderBy('title', 'asc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:pages,slug', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'content' => ['nullable', 'string'],
            'seo_content' => ['nullable', 'string'],
            'show_breadcrumb' => ['required', 'boolean'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'from_airport_code' => ['nullable', 'string', 'max:50'],
            'to_airport_code' => ['nullable', 'string', 'max:50'],
            'schema_markup' => ['nullable', 'string'],
            'faq_schema' => ['nullable', 'string'],
            'faq_items' => ['nullable', 'array'],
            'faq_items.*.question' => ['nullable', 'string', 'max:255'],
            'faq_items.*.answer' => ['nullable', 'string'],
            'breadcrumb_schema' => ['nullable', 'string'],
            'focus_keyword' => ['nullable', 'string', 'max:255'],
        ];

        $validatedData = $request->validate($rules);

        // Generate Slug
        $validatedData['slug'] = $validatedData['slug'] ? Str::slug($validatedData['slug']) : Str::slug($validatedData['title']);

        if (empty($validatedData['faq_schema']) && $request->has('faq_items')) {
            $faqItems = array_filter($request->input('faq_items', []), function ($item) {
                return !empty($item['question']) && !empty($item['answer']);
            });

            if (!empty($faqItems)) {
                $mainEntity = [];
                foreach ($faqItems as $faqItem) {
                    $mainEntity[] = [
                        '@type' => 'Question',
                        'name' => $faqItem['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faqItem['answer'],
                        ],
                    ];
                }
                $validatedData['faq_schema'] = "<script type=\"application/ld+json\">\n" . json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $mainEntity,
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n</script>";
            }
        }

        unset($validatedData['faq_items']);

        // Ensure uniqueness for generated slug
        $count = Page::where('slug', $validatedData['slug'])->count();
        if ($count > 0) {
            $validatedData['slug'] = $validatedData['slug'] . '-' . time();
        }

        // Handle Image Uploads
        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/pages', 'public');
        }
        if ($request->hasFile('og_image')) {
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/pages', 'public');
        }
        if ($request->hasFile('twitter_image')) {
            $validatedData['twitter_image'] = $request->file('twitter_image')->store('uploads/pages', 'public');
        }

        $validatedData['created_by'] = Auth::id() ?? 1;

        Page::create($validatedData);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:pages,slug,' . $page->id, 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'content' => ['nullable', 'string'],
            'seo_content' => ['nullable', 'string'],
            'show_breadcrumb' => ['required', 'boolean'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'from_airport_code' => ['nullable', 'string', 'max:50'],
            'to_airport_code' => ['nullable', 'string', 'max:50'],
            'schema_markup' => ['nullable', 'string'],
            'faq_schema' => ['nullable', 'string'],
            'faq_items' => ['nullable', 'array'],
            'faq_items.*.question' => ['nullable', 'string', 'max:255'],
            'faq_items.*.answer' => ['nullable', 'string'],
            'breadcrumb_schema' => ['nullable', 'string'],
            'focus_keyword' => ['nullable', 'string', 'max:255'],
        ];

        $validatedData = $request->validate($rules);
        $validatedData['slug'] = Str::slug($validatedData['slug']);

        if (empty($validatedData['faq_schema']) && $request->has('faq_items')) {
            $faqItems = array_filter($request->input('faq_items', []), function ($item) {
                return !empty($item['question']) && !empty($item['answer']);
            });

            if (!empty($faqItems)) {
                $mainEntity = [];
                foreach ($faqItems as $faqItem) {
                    $mainEntity[] = [
                        '@type' => 'Question',
                        'name' => $faqItem['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faqItem['answer'],
                        ],
                    ];
                }
                $validatedData['faq_schema'] = "<script type=\"application/ld+json\">\n" . json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $mainEntity,
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n</script>";
            }
        }

        unset($validatedData['faq_items']);

        // Handle Image Uploads & deletions
        if ($request->hasFile('banner_image')) {
            if ($page->banner_image) {
                Storage::disk('public')->delete($page->banner_image);
            }
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/pages', 'public');
        }
        if ($request->hasFile('og_image')) {
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/pages', 'public');
        }
        if ($request->hasFile('twitter_image')) {
            if ($page->twitter_image) {
                Storage::disk('public')->delete($page->twitter_image);
            }
            $validatedData['twitter_image'] = $request->file('twitter_image')->store('uploads/pages', 'public');
        }

        $page->update($validatedData);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page from storage (soft delete).
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page moved to trash.');
    }
}
