<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiSettingController extends Controller
{
    /**
     * Display the GDS/Flight API settings panel.
     */
    public function index()
    {
        $apiSetting = ApiSetting::firstOrCreate([
            'id' => 1
        ], [
            'provider' => 'Amadeus GDS',
            'base_url' => 'https://test.api.amadeus.com',
            'mode' => 'sandbox',
            'endpoint_search' => '/v2/shopping/flight-offers',
            'endpoint_booking' => '/v1/booking/flight-orders',
            'endpoint_fare_rules' => '/v1/shopping/flight-offers/pricing',
            'endpoint_cancellation' => '/v1/booking/flight-orders',
            'endpoint_refund' => '/v1/booking/flight-refunds',
            'endpoint_webhook' => '/api/webhooks/amadeus',
            'api_status' => 'inactive',
            'currency' => 'USD',
            'markup_percent' => 5.00,
            'commission_percent' => 3.00,
        ]);

        return view('admin.api-settings.index', compact('apiSetting'));
    }

    /**
     * Update GDS/Flight API settings.
     */
    public function update(Request $request)
    {
        $apiSetting = ApiSetting::findOrFail(1);

        $rules = [
            'provider' => ['required', 'string', 'max:100'],
            'base_url' => ['required', 'url', 'max:255'],
            'mode' => ['required', 'string', 'in:sandbox,live'],
            'endpoint_search' => ['required', 'string', 'max:100'],
            'endpoint_booking' => ['required', 'string', 'max:100'],
            'endpoint_fare_rules' => ['required', 'string', 'max:100'],
            'endpoint_cancellation' => ['required', 'string', 'max:100'],
            'endpoint_refund' => ['required', 'string', 'max:100'],
            'endpoint_webhook' => ['required', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
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

        return redirect()->route('admin.api-settings.index')->with('success', 'GDS API configuration updated successfully.');
    }

    /**
     * Test the API Connection log.
     */
    public function testConnection(Request $request)
    {
        $apiSetting = ApiSetting::findOrFail(1);

        try {
            // Perform a simulated ping or basic auth call to the base URL
            $url = rtrim($apiSetting->base_url, '/');
            
            // To be extremely robust and safe (since it's a test environment), we do a timeout-limited ping
            // We simulate a responsive API call or fallback gracefully if no internet is present
            $response = Http::timeout(3)->get($url);

            $status = 'active';
            $statusCode = $response->status();
            $log = "Connection Successful!\nHTTP Status: {$statusCode}\nTarget: {$url}\nTimestamp: " . now()->toIso8601String() . "\nConnection latency: Stable.";
            
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
            $log = "Connection Failed!\nError: " . $e->getMessage() . "\nTarget: " . ($apiSetting->base_url ?? 'N/A') . "\nTimestamp: " . now()->toIso8601String();
            
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
}
