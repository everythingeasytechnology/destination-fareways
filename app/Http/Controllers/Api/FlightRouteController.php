<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\FlightRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FlightRouteController extends Controller
{
    public function airports(Request $request)
    {
        $query = Airport::active()->orderBy('iata_code');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('iata_code', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%")
                  ->orWhere('airport_name', 'like', "%{$s}%");
            });
        }

        $airports = $query->get(['iata_code', 'city', 'airport_name', 'state_code']);

        return response()->json([
            'success'  => true,
            'airports' => $airports,
        ]);
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
            'is_featured'               => ['nullable', 'boolean'],
            'is_popular'                => ['nullable', 'boolean'],
            'is_domestic'               => ['nullable', 'boolean'],
            'sort_order'                => ['nullable', 'integer', 'min:0'],
            'status'                    => ['nullable', 'string', 'in:active,inactive'],
            'seo_title'                 => ['nullable', 'string', 'max:255'],
            'seo_description'           => ['nullable', 'string', 'max:500'],
            'seo_keywords'              => ['nullable', 'string', 'max:255'],
            'og_image'                  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'schema_markup'             => ['nullable', 'string'],
            'faq_schema'                => ['nullable', 'string'],
            'canonical_url'             => ['nullable', 'url', 'max:255'],
        ]);

        $validated['slug'] = Str::slug(
            ($validated['slug'] ?? '') ?: $validated['title']
        );

        if (FlightRoute::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] .= '-' . time();
        }

        $imageSizes = [
            'featured_image' => [800,  600,  82],
            'banner_image'   => [1920, 600,  80],
            'og_image'       => [1200, 630,  82],
        ];

        foreach ($imageSizes as $field => [$maxW, $maxH, $quality]) {
            if ($request->hasFile($field)) {
                $file     = $request->file($field);
                $filename = 'uploads/flight-routes/' . Str::uuid() . '.webp';
                $fullPath = storage_path('app/public/' . $filename);

                Image::make($file)
                    ->resize($maxW, $maxH, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    })
                    ->encode('webp', $quality)
                    ->save($fullPath);

                $validated[$field] = $filename;
            }
        }

        $validated['is_featured']  = $validated['is_featured']  ?? false;
        $validated['is_popular']   = $validated['is_popular']   ?? false;
        $validated['is_domestic']  = $validated['is_domestic']  ?? true;
        $validated['sort_order']   = $validated['sort_order']   ?? 0;
        $validated['status']       = $validated['status']       ?? 'active';
        $validated['created_by']   = $request->user()->id;

        $route = FlightRoute::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Flight route created successfully.',
            'route'   => [
                'id'    => $route->id,
                'title' => $route->title,
                'slug'  => $route->slug,
            ],
        ], 201);
    }
}
