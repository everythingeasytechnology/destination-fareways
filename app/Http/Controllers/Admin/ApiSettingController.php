<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiSetting;
use App\Services\BookingFlightService;
use Illuminate\Http\Request;

class ApiSettingController extends Controller
{
    /**
     * Display the GDS/Flight API settings panel.
     */
    public function index(BookingFlightService $bookingFlightService)
    {
        $apiSetting = ApiSetting::firstOrCreate([
            'id' => 1
        ], $bookingFlightService->defaultSettings());

        return view('admin.api-settings.index', compact('apiSetting'));
    }

    /**
     * Update GDS/Flight API settings.
     */
    public function update(Request $request)
    {
        $apiSetting = ApiSetting::findOrFail(1);

        $request->merge([
            'base_url' => $this->normalizeEndpointText($request->input('base_url')),
            'rapidapi_host' => $this->normalizeEndpointText($request->input('rapidapi_host')),
            'endpoint_location' => $this->normalizeEndpointText($request->input('endpoint_location')),
            'endpoint_search' => $this->normalizeEndpointText($request->input('endpoint_search')),
            'endpoint_multi_stop' => $this->normalizeEndpointText($request->input('endpoint_multi_stop')),
            'endpoint_details' => $this->normalizeEndpointText($request->input('endpoint_details')),
            'endpoint_min_price' => $this->normalizeEndpointText($request->input('endpoint_min_price')),
            'endpoint_booking' => $this->normalizeEndpointText($request->input('endpoint_booking')),
            'endpoint_fare_rules' => $this->normalizeEndpointText($request->input('endpoint_fare_rules')),
            'endpoint_cancellation' => $this->normalizeEndpointText($request->input('endpoint_cancellation')),
            'endpoint_refund' => $this->normalizeEndpointText($request->input('endpoint_refund')),
            'endpoint_webhook' => $this->normalizeEndpointText($request->input('endpoint_webhook')),
        ]);

        $rules = [
            'provider' => ['required', 'string', 'max:100'],
            'base_url' => ['required', 'url', 'max:255'],
            'rapidapi_host' => ['required', 'string', 'max:255'],
            'mode' => ['required', 'string', 'in:sandbox,live'],
            'endpoint_location' => ['required', 'string', 'max:150'],
            'endpoint_search' => ['required', 'string', 'max:150'],
            'endpoint_multi_stop' => ['required', 'string', 'max:150'],
            'endpoint_details' => ['required', 'string', 'max:150'],
            'endpoint_min_price' => ['required', 'string', 'max:150'],
            'endpoint_booking' => ['nullable', 'string', 'max:150'],
            'endpoint_fare_rules' => ['nullable', 'string', 'max:150'],
            'endpoint_cancellation' => ['nullable', 'string', 'max:150'],
            'endpoint_refund' => ['nullable', 'string', 'max:150'],
            'endpoint_webhook' => ['nullable', 'string', 'max:150'],
            'currency' => ['required', 'string', 'max:10'],
            'language_code' => ['required', 'string', 'max:10'],
            'markup_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'commission_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'api_status' => ['required', 'string', 'in:active,inactive,error'],
        ];

        // Only validate and set keys if they are not masked
        if ($request->filled('api_key') && $request->input('api_key') !== '••••••••••••••••') {
            $rules['api_key'] = ['required', 'string'];
        }
        if ($request->filled('api_secret') && $request->input('api_secret') !== '••••••••••••••••') {
            $rules['api_secret'] = ['required', 'string'];
        }

        $validatedData = $request->validate($rules);

        // Remove from validated data if not changed (i.e. remains masked)
        if ($request->input('api_key') === '••••••••••••••••') {
            unset($validatedData['api_key']);
        }
        if ($request->input('api_secret') === '••••••••••••••••') {
            unset($validatedData['api_secret']);
        }

        $apiSetting->update($validatedData);

        return redirect()->route('admin.api-settings.index')->with('success', 'Booking.com15 API configuration updated successfully.');
    }

    /**
     * Test the API Connection log.
     */
    public function testConnection(Request $request, BookingFlightService $bookingFlightService)
    {
        $apiSetting = ApiSetting::findOrFail(1);

        try {
            $result = $bookingFlightService->testConnection();

            $status = 'active';
            $log = "Connection Successful!\nProvider: {$apiSetting->provider}\nHost: {$apiSetting->rapidapi_host}\nEndpoint: {$apiSetting->endpoint_location}\nMessage: {$result['message']}\nTimestamp: " . now()->toIso8601String();
            
            $apiSetting->update([
                'api_status' => 'active',
                'last_sync_at' => now(),
                'last_error_log' => $log,
            ]);

            return response()->json([
                'success' => true,
                'status' => 'active',
                'message' => 'API Connection tested successfully!',
                'log' => $log,
            ]);

        } catch (\Exception $e) {
            $log = "Connection Failed!\nError: " . $e->getMessage() . "\nTarget: " . ($apiSetting->base_url ?? 'N/A') . "\nHost: " . ($apiSetting->rapidapi_host ?? 'N/A') . "\nTimestamp: " . now()->toIso8601String();
            
            $apiSetting->update([
                'api_status' => 'error',
                'last_error_log' => $log,
            ]);

            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'API Connection failed. See error log for details.',
                'log' => $log,
            ]);
        }
    }

    private function normalizeEndpointText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim(str_replace(['–', '—', '−'], '-', $value));
    }
}
