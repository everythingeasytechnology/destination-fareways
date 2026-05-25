<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeoController extends Controller
{
    /**
     * Display a listing of the SEO settings.
     */
    public function index()
    {
        $seoSettings = SeoSetting::orderBy('page_name', 'asc')->get();
        return view('admin.seo.index', compact('seoSettings'));
    }

    /**
     * Show the form for editing the specified SEO setting.
     */
    public function edit(SeoSetting $seo)
    {
        return view('admin.seo.edit', compact('seo'));
    }

    /**
     * Update the specified SEO setting in storage.
     */
    public function update(Request $request, SeoSetting $seo)
    {
        $rules = [
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['required', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'seo_content_block' => ['nullable', 'string'],
            'robots_tag' => ['required', 'string', 'max:100'],
        ];

        $validatedData = $request->validate($rules);

        // Handle File uploads & deletions
        if ($request->hasFile('og_image')) {
            if ($seo->og_image) {
                Storage::disk('public')->delete($seo->og_image);
            }
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/seo', 'public');
        }
        if ($request->hasFile('twitter_image')) {
            if ($seo->twitter_image) {
                Storage::disk('public')->delete($seo->twitter_image);
            }
            $validatedData['twitter_image'] = $request->file('twitter_image')->store('uploads/seo', 'public');
        }

        $seo->update($validatedData);

        return redirect()->route('admin.seo.index')->with('success', "SEO metadata for '{$seo->page_name}' updated successfully.");
    }
}
