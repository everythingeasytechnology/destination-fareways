<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlightRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FlightRouteController extends Controller
{
    public function index()
    {
        $routes = FlightRoute::orderBy('sort_order', 'asc')->orderBy('title', 'asc')->get();
        return view('admin.flight-routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.flight-routes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                     => ['required', 'string', 'max:255'],
            'slug'                      => ['nullable', 'string', 'unique:flight_routes,slug', 'max:255'],
            'origin_city'               => ['required', 'string', 'max:100'],
            'origin_airport_code'       => ['nullable', 'string', 'max:10'],
            'destination_city'          => ['required', 'string', 'max:100'],
            'destination_airport_code'  => ['nullable', 'string', 'max:10'],
            'origin_country'            => ['nullable', 'string', 'max:100'],
            'destination_country'       => ['nullable', 'string', 'max:100'],
            'short_desc'                => ['nullable', 'string', 'max:500'],
            'description'               => ['nullable', 'string'],
            'featured_image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'starting_price'            => ['nullable', 'numeric', 'min:0'],
            'flight_duration'           => ['nullable', 'string', 'max:50'],
            'airlines'                  => ['nullable', 'string', 'max:255'],
            'frequency'                 => ['nullable', 'string', 'max:100'],
            'is_featured'               => ['required', 'boolean'],
            'is_popular'                => ['required', 'boolean'],
            'is_domestic'               => ['required', 'boolean'],
            'sort_order'                => ['required', 'integer', 'min:0'],
            'status'                    => ['required', 'string', 'in:active,inactive'],
            'seo_title'                 => ['nullable', 'string', 'max:255'],
            'seo_description'           => ['nullable', 'string', 'max:500'],
            'seo_keywords'              => ['nullable', 'string', 'max:255'],
            'og_image'                  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'schema_markup'             => ['nullable', 'string'],
            'faq_schema'                => ['nullable', 'string'],
            'canonical_url'             => ['nullable', 'url', 'max:255'],
        ]);

        $validated['slug'] = $validated['slug']
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        if (FlightRoute::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] .= '-' . time();
        }

        foreach (['featured_image', 'banner_image', 'og_image'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('uploads/flight-routes', 'public');
            }
        }

        $validated['created_by'] = Auth::id() ?? 1;

        FlightRoute::create($validated);

        return redirect()->route('admin.flight-routes.index')->with('success', 'Flight route created successfully.');
    }

    public function edit(FlightRoute $flightRoute)
    {
        return view('admin.flight-routes.edit', compact('flightRoute'));
    }

    public function update(Request $request, FlightRoute $flightRoute)
    {
        $validated = $request->validate([
            'title'                     => ['required', 'string', 'max:255'],
            'slug'                      => ['required', 'string', 'unique:flight_routes,slug,' . $flightRoute->id, 'max:255'],
            'origin_city'               => ['required', 'string', 'max:100'],
            'origin_airport_code'       => ['nullable', 'string', 'max:10'],
            'destination_city'          => ['required', 'string', 'max:100'],
            'destination_airport_code'  => ['nullable', 'string', 'max:10'],
            'origin_country'            => ['nullable', 'string', 'max:100'],
            'destination_country'       => ['nullable', 'string', 'max:100'],
            'short_desc'                => ['nullable', 'string', 'max:500'],
            'description'               => ['nullable', 'string'],
            'featured_image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'banner_image'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'starting_price'            => ['nullable', 'numeric', 'min:0'],
            'flight_duration'           => ['nullable', 'string', 'max:50'],
            'airlines'                  => ['nullable', 'string', 'max:255'],
            'frequency'                 => ['nullable', 'string', 'max:100'],
            'is_featured'               => ['required', 'boolean'],
            'is_popular'                => ['required', 'boolean'],
            'is_domestic'               => ['required', 'boolean'],
            'sort_order'                => ['required', 'integer', 'min:0'],
            'status'                    => ['required', 'string', 'in:active,inactive'],
            'seo_title'                 => ['nullable', 'string', 'max:255'],
            'seo_description'           => ['nullable', 'string', 'max:500'],
            'seo_keywords'              => ['nullable', 'string', 'max:255'],
            'og_image'                  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'schema_markup'             => ['nullable', 'string'],
            'faq_schema'                => ['nullable', 'string'],
            'canonical_url'             => ['nullable', 'url', 'max:255'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);

        foreach (['featured_image', 'banner_image', 'og_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($flightRoute->$field) {
                    Storage::disk('public')->delete($flightRoute->$field);
                }
                $validated[$field] = $request->file($field)->store('uploads/flight-routes', 'public');
            }
        }

        $flightRoute->update($validated);

        return redirect()->route('admin.flight-routes.index')->with('success', 'Flight route updated successfully.');
    }

    public function destroy(FlightRoute $flightRoute)
    {
        $flightRoute->delete();
        return redirect()->route('admin.flight-routes.index')->with('success', 'Flight route moved to trash.');
    }
}
