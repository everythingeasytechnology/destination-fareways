<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * Display a listing of the destinations.
     */
    public function index()
    {
        $destinations = Destination::orderBy('sort_order', 'asc')->orderBy('name', 'asc')->get();
        return view('admin.destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new destination.
     */
    public function create()
    {
        return view('admin.destinations.create');
    }

    /**
     * Store a newly created destination in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:destinations,slug', 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'airport_code' => ['nullable', 'string', 'max:10'],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'starting_price' => ['nullable', 'numeric', 'min:0'],
            'best_time_to_visit' => ['nullable', 'string', 'max:255'],
            'climate' => ['nullable', 'string', 'max:100'],
            'is_domestic' => ['required', 'boolean'],
            'is_featured' => ['required', 'boolean'],
            'is_popular' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'schema_markup' => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($rules);

        // Generate Slug
        $validatedData['slug'] = $validatedData['slug'] ? Str::slug($validatedData['slug']) : Str::slug($validatedData['name']);
        
        // Ensure uniqueness for generated slug
        $count = Destination::where('slug', $validatedData['slug'])->count();
        if ($count > 0) {
            $validatedData['slug'] = $validatedData['slug'] . '-' . time();
        }

        // Image uploads
        if ($request->hasFile('featured_image')) {
            $validatedData['featured_image'] = $request->file('featured_image')->store('uploads/destinations', 'public');
        }
        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/destinations', 'public');
        }
        if ($request->hasFile('og_image')) {
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/destinations', 'public');
        }

        // Handle Gallery Multi-uploads
        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('uploads/destinations/gallery', 'public');
            }
        }
        $validatedData['gallery'] = $gallery;

        $validatedData['created_by'] = Auth::id() ?? 1;

        Destination::create($validatedData);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully.');
    }

    /**
     * Show the form for editing the specified destination.
     */
    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    /**
     * Update the specified destination in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:destinations,slug,' . $destination->id, 'max:255'],
            'country' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'airport_code' => ['nullable', 'string', 'max:10'],
            'short_desc' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'starting_price' => ['nullable', 'numeric', 'min:0'],
            'best_time_to_visit' => ['nullable', 'string', 'max:255'],
            'climate' => ['nullable', 'string', 'max:100'],
            'is_domestic' => ['required', 'boolean'],
            'is_featured' => ['required', 'boolean'],
            'is_popular' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'schema_markup' => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($rules);
        $validatedData['slug'] = Str::slug($validatedData['slug']);

        // Handle uploads & old file deletions
        if ($request->hasFile('featured_image')) {
            if ($destination->featured_image) {
                Storage::disk('public')->delete($destination->featured_image);
            }
            $validatedData['featured_image'] = $request->file('featured_image')->store('uploads/destinations', 'public');
        }
        if ($request->hasFile('banner_image')) {
            if ($destination->banner_image) {
                Storage::disk('public')->delete($destination->banner_image);
            }
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/destinations', 'public');
        }
        if ($request->hasFile('og_image')) {
            if ($destination->og_image) {
                Storage::disk('public')->delete($destination->og_image);
            }
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/destinations', 'public');
        }

        // Handle Gallery Multi-uploads & retention of existing items
        $existingGallery = $request->input('existing_gallery', []);
        
        // Clean up deleted items from disk
        $oldGallery = $destination->gallery ?? [];
        $deletedGalleryItems = array_diff($oldGallery, $existingGallery);
        foreach ($deletedGalleryItems as $deletedItem) {
            Storage::disk('public')->delete($deletedItem);
        }

        // Add newly uploaded items
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $existingGallery[] = $file->store('uploads/destinations/gallery', 'public');
            }
        }
        
        $validatedData['gallery'] = array_values($existingGallery);

        $destination->update($validatedData);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated successfully.');
    }

    /**
     * Remove the specified destination from storage (soft delete).
     */
    public function destroy(Destination $destination)
    {
        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('success', 'Destination moved to trash.');
    }
}
