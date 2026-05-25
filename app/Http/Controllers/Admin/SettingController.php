<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the global settings panel.
     */
    public function index()
    {
        $setting = Setting::firstOrCreate([
            'id' => 1
        ], [
            'site_name' => 'Destination Fareways',
            'primary_email' => 'info@destinationfareways.com',
            'primary_phone' => '+1 (800) 555-0199',
            'address' => '100 Premium Fareways Blvd',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10001',
            'country' => 'USA',
            'maintenance_mode' => 'inactive',
        ]);

        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Update the global settings.
     */
    public function update(Request $request)
    {
        $setting = Setting::findOrFail(1);

        $rules = [
            'site_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,ico,webp', 'max:2048'],
            'primary_email' => ['required', 'email', 'max:255'],
            'secondary_email' => ['nullable', 'email', 'max:255'],
            'primary_phone' => ['required', 'string', 'max:50'],
            'secondary_phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'zip' => ['required', 'string', 'max:20'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_youtube' => ['nullable', 'url', 'max:255'],
            'analytics_google_id' => ['nullable', 'string', 'max:50'],
            'analytics_facebook_pixel' => ['nullable', 'string', 'max:50'],
            'analytics_custom_code' => ['nullable', 'string'],
            'header_scripts' => ['nullable', 'string'],
            'footer_scripts' => ['nullable', 'string'],
            'copyright' => ['nullable', 'string', 'max:255'],
            'maintenance_mode' => ['required', 'string', 'in:active,inactive'],
        ];

        $validatedData = $request->validate($rules);

        // Logo image upload handler
        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validatedData['logo'] = $request->file('logo')->store('uploads/settings', 'public');
        }

        // Favicon image upload handler
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $validatedData['favicon'] = $request->file('favicon')->store('uploads/settings', 'public');
        }

        $setting->update($validatedData);

        return redirect()->route('admin.settings.index')->with('success', 'Global site settings updated successfully.');
    }
}
