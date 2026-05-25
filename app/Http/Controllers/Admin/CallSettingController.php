<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallSetting;
use Illuminate\Http\Request;

class CallSettingController extends Controller
{
    /**
     * Display the Call & Call buttons configuration panel.
     */
    public function index()
    {
        $callSetting = CallSetting::firstOrCreate([
            'id' => 1
        ], [
            'phone' => '+1 (800) 555-0199',
            'whatsapp' => '+1 (800) 555-0199',
            'button_text' => 'Call Now',
            'button_color' => '#F59E0B',
            'text_color' => '#FFFFFF',
            'toggle_header' => true,
            'toggle_footer' => true,
            'toggle_mobile_floating' => true,
            'toggle_desktop' => true,
            'toggle_whatsapp' => true,
            'floating_position' => 'bottom-right',
            'cta_text' => 'Speak with a Travel Expert',
            'cta_phone' => '+1 (800) 555-0199',
            'cta_subtext' => 'Get exclusive phone-only discounts up to 30% off!',
            'status' => true,
        ]);

        return view('admin.call-settings.index', compact('callSetting'));
    }

    /**
     * Update the Call & WhatsApp settings.
     */
    public function update(Request $request)
    {
        $callSetting = CallSetting::findOrFail(1);

        $rules = [
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'button_text' => ['required', 'string', 'max:100'],
            'button_color' => ['required', 'string', 'max:20'],
            'text_color' => ['required', 'string', 'max:20'],
            'floating_position' => ['required', 'string', 'in:bottom-right,bottom-left,top-right,top-left'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_phone' => ['nullable', 'string', 'max:50'],
            'cta_subtext' => ['nullable', 'string', 'max:255'],
            
            // Boolean Toggles
            'toggle_header' => ['required', 'boolean'],
            'toggle_footer' => ['required', 'boolean'],
            'toggle_mobile_floating' => ['required', 'boolean'],
            'toggle_desktop' => ['required', 'boolean'],
            'toggle_whatsapp' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ];

        // Format toggle inputs (since checkboxes are sent only when checked, we ensure boolean defaults in controller if needed, but our blade will handle active form values with fallback inputs or standard values)
        $validatedData = $request->validate($rules);

        $callSetting->update($validatedData);

        return redirect()->route('admin.call-settings.index')->with('success', 'Call buttons and WhatsApp configuration saved successfully.');
    }
}
