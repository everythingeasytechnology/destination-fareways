<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    /**
     * Display a listing of the offers.
     */
    public function index()
    {
        $offers = Offer::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new offer.
     */
    public function create()
    {
        return view('admin.offers.create');
    }

    /**
     * Store a newly created offer in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:offers,slug', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'from_city' => ['required', 'string', 'max:100'],
            'to_city' => ['required', 'string', 'max:100'],
            'airline' => ['nullable', 'string', 'max:100'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'offer_price' => ['required', 'numeric', 'min:0'],
            'discount_label' => ['nullable', 'string', 'max:100'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:valid_from'],
            'is_featured' => ['required', 'boolean'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        $validatedData = $request->validate($rules);

        // Generate Slug
        $validatedData['slug'] = $validatedData['slug'] ? Str::slug($validatedData['slug']) : Str::slug($validatedData['title']);
        
        // Ensure uniqueness for generated slug
        $count = Offer::where('slug', $validatedData['slug'])->count();
        if ($count > 0) {
            $validatedData['slug'] = $validatedData['slug'] . '-' . time();
        }

        // Image upload
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('uploads/offers', 'public');
        }
        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/offers', 'public');
        }
        if ($request->hasFile('og_image')) {
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/offers', 'public');
        }

        $validatedData['created_by'] = Auth::id() ?? 1;

        Offer::create($validatedData);

        return redirect()->route('admin.offers.index')->with('success', 'Flight offer created successfully.');
    }

    /**
     * Show the form for editing the specified offer.
     */
    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    /**
     * Update the specified offer in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:offers,slug,' . $offer->id, 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'from_city' => ['required', 'string', 'max:100'],
            'to_city' => ['required', 'string', 'max:100'],
            'airline' => ['nullable', 'string', 'max:100'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'offer_price' => ['required', 'numeric', 'min:0'],
            'discount_label' => ['nullable', 'string', 'max:100'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:valid_from'],
            'is_featured' => ['required', 'boolean'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        $validatedData = $request->validate($rules);
        $validatedData['slug'] = Str::slug($validatedData['slug']);

        // Handle uploads & old file deletions
        if ($request->hasFile('image')) {
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $validatedData['image'] = $request->file('image')->store('uploads/offers', 'public');
        }
        if ($request->hasFile('banner_image')) {
            if ($offer->banner_image) {
                Storage::disk('public')->delete($offer->banner_image);
            }
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/offers', 'public');
        }
        if ($request->hasFile('og_image')) {
            if ($offer->og_image) {
                Storage::disk('public')->delete($offer->og_image);
            }
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/offers', 'public');
        }

        $offer->update($validatedData);

        return redirect()->route('admin.offers.index')->with('success', 'Flight offer updated successfully.');
    }

    /**
     * Remove the specified offer from storage (soft delete).
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('success', 'Flight offer moved to trash.');
    }
}
