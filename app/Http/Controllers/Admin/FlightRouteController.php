<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\FlightRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        $airports = Airport::active()->orderBy('iata_code')->get();
        return view('admin.flight-routes.create', compact('airports'));
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
        $airports = Airport::active()->orderBy('iata_code')->get();
        return view('admin.flight-routes.edit', compact('flightRoute', 'airports'));
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

    public function generateAiContent(Request $request)
    {
        $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'origin_iata'      => ['required', 'string', 'max:10'],
            'origin_city'      => ['required', 'string', 'max:100'],
            'destination_iata' => ['required', 'string', 'max:10'],
            'destination_city' => ['required', 'string', 'max:100'],
        ]);

        $apiKey = config('services.anthropic.key');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Anthropic API key not configured. Add ANTHROPIC_API_KEY to your .env file.'], 500);
        }

        $title        = $request->title;
        $originIata   = strtoupper($request->origin_iata);
        $originCity   = $request->origin_city;
        $destIata     = strtoupper($request->destination_iata);
        $destCity     = $request->destination_city;
        $price        = $request->starting_price ? '$' . $request->starting_price : 'competitive prices';
        $duration     = $request->flight_duration ?: 'varies';
        $airlines     = $request->airlines        ?: 'multiple major airlines';
        $frequency    = $request->frequency       ?: 'multiple daily flights';

        $defaultSlug = Str::slug("{$originCity}-to-{$destCity}-flights");

        $prompt = <<<PROMPT
You are an expert travel content writer specializing in SEO-optimized flight route pages for a US travel agency.

Route Information:
- Page Title: {$title}
- Origin: {$originIata} ({$originCity})
- Destination: {$destIata} ({$destCity})
- Starting Price: {$price}
- Flight Duration: {$duration}
- Airlines: {$airlines}
- Frequency: {$frequency}

Return ONLY a valid JSON object (no markdown, no code blocks, no explanation) with exactly these fields:
{
  "slug": "seo-friendly-url-slug",
  "seo_title": "SEO title max 60 chars",
  "seo_description": "Meta description 140-160 chars persuasive",
  "seo_keywords": "keyword1, keyword2, keyword3, keyword4, keyword5",
  "short_desc": "2-3 compelling sentences for listing cards. Max 280 chars.",
  "description": "Full HTML body 800-1000 words. Use <h2>, <p>, <ul>, <li>. Sections: Overview, Why Fly This Route, Top Airlines, Travel Tips, Best Time to Fly.",
  "faqs": [
    {"question": "Question 1?", "answer": "Answer 1."},
    {"question": "Question 2?", "answer": "Answer 2."},
    {"question": "Question 3?", "answer": "Answer 3."},
    {"question": "Question 4?", "answer": "Answer 4."},
    {"question": "Question 5?", "answer": "Answer 5."}
  ],
  "schema_markup": "<script type=\"application/ld+json\">{\"@context\":\"https://schema.org\",\"@type\":\"WebPage\",\"name\":\"{$title}\",\"description\":\"meta description here\"}</script>"
}
PROMPT;

        $model = config('services.anthropic.model', 'claude-haiku-4-5');

        try {
            $response = Http::withHeaders([
                    'x-api-key'         => $apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])
                ->timeout(90)
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => $model,
                    'max_tokens' => 4096,
                    'messages'   => [
                        [
                            'role'    => 'user',
                            'content' => "You are a professional travel content writer. Always respond with valid JSON only. No markdown. No code blocks. No extra text.\n\n" . $prompt,
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                $error = $response->json('error.message') ?? $response->body();
                return response()->json(['error' => 'Anthropic API error: ' . $error], 500);
            }

            $raw = $response->json('content.0.text', '');
            $raw = trim($raw);
            $raw = preg_replace('/^```json\s*/i', '', $raw);
            $raw = preg_replace('/^```\s*/i', '', $raw);
            $raw = preg_replace('/\s*```$/i', '', $raw);

            $data = json_decode($raw, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                return response()->json(['error' => 'Could not parse AI response. Please try again.'], 500);
            }

            return response()->json([
                'success'         => true,
                'slug'            => $data['slug']            ?? $defaultSlug,
                'seo_title'       => $data['seo_title']       ?? '',
                'seo_description' => $data['seo_description'] ?? '',
                'seo_keywords'    => $data['seo_keywords']    ?? '',
                'short_desc'      => $data['short_desc']      ?? '',
                'description'     => $data['description']     ?? '',
                'faqs'            => $data['faqs']            ?? [],
                'schema_markup'   => $data['schema_markup']   ?? '',
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json(['error' => 'Connection timeout. Please try again.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Request failed: ' . $e->getMessage()], 500);
        }
    }
}
