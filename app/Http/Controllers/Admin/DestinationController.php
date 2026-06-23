<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::where('status', '!=', 'draft')
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $draftId = $request->input('draft_id') ?: null;

        $slugRule = [
            'nullable', 'string', 'max:255',
            Rule::unique('destinations', 'slug')
                ->where(fn ($q) => $q->where('status', '!=', 'draft'))
                ->ignore($draftId),
        ];

        $rules = [
            'name'               => ['required', 'string', 'max:255'],
            'slug'               => $slugRule,
            'country'            => ['required', 'string', 'max:100'],
            'state'              => ['nullable', 'string', 'max:100'],
            'airport_code'       => ['nullable', 'string', 'max:10'],
            'short_desc'         => ['nullable', 'string', 'max:500'],
            'description'        => ['nullable', 'string'],
            'featured_image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
            'banner_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery.*'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'starting_price'     => ['nullable', 'numeric', 'min:0'],
            'best_time_to_visit' => ['nullable', 'string', 'max:255'],
            'climate'            => ['nullable', 'string', 'max:100'],
            'is_domestic'        => ['required', 'boolean'],
            'is_featured'        => ['required', 'boolean'],
            'is_popular'         => ['required', 'boolean'],
            'sort_order'         => ['required', 'integer', 'min:0'],
            'status'             => ['required', 'string', 'in:active,inactive'],
            'seo_title'          => ['nullable', 'string', 'max:255'],
            'seo_description'    => ['nullable', 'string', 'max:500'],
            'seo_keywords'       => ['nullable', 'string', 'max:255'],
            'og_image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'schema_markup'      => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($rules);

        $validatedData['slug'] = $validatedData['slug']
            ? Str::slug($validatedData['slug'])
            : Str::slug($validatedData['name']);

        if ($request->hasFile('featured_image')) {
            $validatedData['featured_image'] = $request->file('featured_image')->store('uploads/destinations', 'public');
        }
        if ($request->hasFile('banner_image')) {
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/destinations', 'public');
        }
        if ($request->hasFile('og_image')) {
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/destinations', 'public');
        }

        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('uploads/destinations/gallery', 'public');
            }
        }
        $validatedData['gallery'] = $gallery;

        $validatedData['created_by'] = Auth::id() ?? 1;

        // If an auto-saved draft exists, update it and publish
        if ($draftId) {
            $draft = Destination::where('id', $draftId)->where('status', 'draft')->first();
            if ($draft) {
                // Merge existing gallery with new uploads
                if (empty($gallery)) {
                    $validatedData['gallery'] = $draft->gallery ?? [];
                }
                $draft->update($validatedData);
                return redirect()->route('admin.destinations.index')->with('success', 'Destination published successfully.');
            }
        }

        // Ensure slug uniqueness for fresh record
        $base  = $validatedData['slug'];
        $count = Destination::where('slug', $base)->count();
        if ($count > 0) {
            $validatedData['slug'] = $base . '-' . time();
        }

        Destination::create($validatedData);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully.');
    }

    public function autosave(Request $request)
    {
        $draftId = $request->input('draft_id');

        $data = $request->only([
            'name', 'slug', 'country', 'state', 'airport_code',
            'short_desc', 'description', 'best_time_to_visit', 'climate',
            'is_domestic', 'is_featured', 'is_popular', 'sort_order',
            'seo_title', 'seo_description', 'seo_keywords', 'schema_markup',
        ]);

        if ($request->filled('starting_price')) {
            $data['starting_price'] = $request->input('starting_price');
        }

        $data['status']      = 'draft';
        $data['created_by']  = Auth::id() ?? 1;
        $data['is_domestic'] = $data['is_domestic'] ?? 0;
        $data['is_featured'] = $data['is_featured'] ?? 0;
        $data['is_popular']  = $data['is_popular']  ?? 0;
        $data['sort_order']  = $data['sort_order']  ?? 0;

        if ($draftId) {
            $destination = Destination::where('id', $draftId)->where('status', 'draft')->first();
            if ($destination) {
                $destination->update($data);
                return response()->json(['success' => true, 'id' => $destination->id]);
            }
        }

        if (empty($data['name'])) {
            return response()->json(['success' => false, 'message' => 'Enter a destination name first to enable auto-save.']);
        }

        if (empty($data['country'])) {
            $data['country'] = 'Unknown';
        }

        $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
        $base = $slug;
        $i    = 1;
        while (Destination::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug']    = $slug;
        $data['gallery'] = [];

        $destination = Destination::create($data);

        return response()->json(['success' => true, 'id' => $destination->id]);
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $rules = [
            'name'               => ['required', 'string', 'max:255'],
            'slug'               => ['required', 'string', 'unique:destinations,slug,' . $destination->id, 'max:255'],
            'country'            => ['required', 'string', 'max:100'],
            'state'              => ['nullable', 'string', 'max:100'],
            'airport_code'       => ['nullable', 'string', 'max:10'],
            'short_desc'         => ['nullable', 'string', 'max:500'],
            'description'        => ['nullable', 'string'],
            'featured_image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
            'banner_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery.*'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'starting_price'     => ['nullable', 'numeric', 'min:0'],
            'best_time_to_visit' => ['nullable', 'string', 'max:255'],
            'climate'            => ['nullable', 'string', 'max:100'],
            'is_domestic'        => ['required', 'boolean'],
            'is_featured'        => ['required', 'boolean'],
            'is_popular'         => ['required', 'boolean'],
            'sort_order'         => ['required', 'integer', 'min:0'],
            'status'             => ['required', 'string', 'in:active,inactive,draft'],
            'seo_title'          => ['nullable', 'string', 'max:255'],
            'seo_description'    => ['nullable', 'string', 'max:500'],
            'seo_keywords'       => ['nullable', 'string', 'max:255'],
            'og_image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'schema_markup'      => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($rules);
        $validatedData['slug'] = Str::slug($validatedData['slug']);

        if ($request->hasFile('featured_image')) {
            if ($destination->featured_image) Storage::disk('public')->delete($destination->featured_image);
            $validatedData['featured_image'] = $request->file('featured_image')->store('uploads/destinations', 'public');
        }
        if ($request->hasFile('banner_image')) {
            if ($destination->banner_image) Storage::disk('public')->delete($destination->banner_image);
            $validatedData['banner_image'] = $request->file('banner_image')->store('uploads/destinations', 'public');
        }
        if ($request->hasFile('og_image')) {
            if ($destination->og_image) Storage::disk('public')->delete($destination->og_image);
            $validatedData['og_image'] = $request->file('og_image')->store('uploads/destinations', 'public');
        }

        $existingGallery = $request->input('existing_gallery', []);
        $oldGallery      = $destination->gallery ?? [];

        foreach (array_diff($oldGallery, $existingGallery) as $deleted) {
            Storage::disk('public')->delete($deleted);
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $existingGallery[] = $file->store('uploads/destinations/gallery', 'public');
            }
        }

        $validatedData['gallery'] = array_values($existingGallery);

        $destination->update($validatedData);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();
        return redirect()->route('admin.destinations.index')->with('success', 'Destination moved to trash.');
    }
}
