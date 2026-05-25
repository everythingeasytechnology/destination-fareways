<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchemaSetting;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class SchemaController extends Controller
{
    /**
     * Display a listing of schema settings.
     */
    public function index()
    {
        $schemas = SchemaSetting::orderBy('page_identifier', 'asc')->get();
        return view('admin.schema.index', compact('schemas'));
    }

    /**
     * Show the form for creating a new schema setting.
     */
    public function create()
    {
        // Fetch pages from SEO settings to populate page identifier dropdown
        $pages = SeoSetting::orderBy('page_name', 'asc')->pluck('page_name', 'page_identifier')->toArray();
        
        // Default standard pages if database is empty
        if (empty($pages)) {
            $pages = [
                'home' => 'Home Page',
                'about' => 'About Us Page',
                'flights' => 'Flights Page',
                'contact' => 'Contact Us Page',
                'newsletter' => 'Newsletter Page'
            ];
        }

        return view('admin.schema.create', compact('pages'));
    }

    /**
     * Store a newly created schema setting in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_identifier' => ['required', 'string', 'max:255'],
            'schema_type' => ['required', 'string', 'max:255'],
            'schema_json' => ['required', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $json = trim($request->schema_json);

        // Auto-extract content inside <script type="application/ld+json"> if user pasted full script tags
        if (preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $json, $matches)) {
            $json = trim($matches[1]);
        }

        // Validate JSON syntax
        json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors([
                'schema_json' => 'Invalid JSON structure: ' . json_last_error_msg()
            ])->withInput();
        }

        // Pretty print verified JSON
        $formattedJson = json_encode(json_decode($json), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        SchemaSetting::create([
            'page_identifier' => $request->page_identifier,
            'schema_type' => $request->schema_type,
            'schema_json' => $formattedJson,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.schema.index')->with('success', 'Schema markup created and validated successfully.');
    }

    /**
     * Display the specified schema setting.
     */
    public function show(SchemaSetting $schema)
    {
        return redirect()->route('admin.schema.edit', $schema->id);
    }

    /**
     * Show the form for editing the specified schema setting.
     */
    public function edit(SchemaSetting $schema)
    {
        $pages = SeoSetting::orderBy('page_name', 'asc')->pluck('page_name', 'page_identifier')->toArray();
        if (empty($pages)) {
            $pages = [
                'home' => 'Home Page',
                'about' => 'About Us Page',
                'flights' => 'Flights Page',
                'contact' => 'Contact Us Page',
                'newsletter' => 'Newsletter Page'
            ];
        }

        return view('admin.schema.edit', compact('schema', 'pages'));
    }

    /**
     * Update the specified schema setting in storage.
     */
    public function update(Request $request, SchemaSetting $schema)
    {
        $request->validate([
            'page_identifier' => ['required', 'string', 'max:255'],
            'schema_type' => ['required', 'string', 'max:255'],
            'schema_json' => ['required', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $json = trim($request->schema_json);

        // Auto-extract content inside <script type="application/ld+json"> if user pasted full script tags
        if (preg_match('/<script\b[^>]*>(.*?)<\/script>/is', $json, $matches)) {
            $json = trim($matches[1]);
        }

        // Validate JSON syntax
        json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors([
                'schema_json' => 'Invalid JSON structure: ' . json_last_error_msg()
            ])->withInput();
        }

        // Pretty print verified JSON
        $formattedJson = json_encode(json_decode($json), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $schema->update([
            'page_identifier' => $request->page_identifier,
            'schema_type' => $request->schema_type,
            'schema_json' => $formattedJson,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.schema.index')->with('success', 'Schema markup updated and validated successfully.');
    }

    /**
     * Remove the specified schema setting from storage.
     */
    public function destroy(SchemaSetting $schema)
    {
        $schema->delete();
        return redirect()->route('admin.schema.index')->with('success', 'Schema markup deleted successfully.');
    }
}
