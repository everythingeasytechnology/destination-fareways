<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\CallSetting;
use App\Models\SeoSetting;
use App\Models\FlightEnquiry;
use App\Http\Requests\FlightEnquiryRequest;
use App\Services\BookingFlightService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FlightController extends Controller
{
    /**
     * Display the Flight Search view
     */
    public function search(Request $request)
    {
        $settings = Setting::first();
        $seoData = SeoSetting::where('page_identifier', 'flights')->first();
        
        $breadcrumbs = [
            ['title' => 'Search Flights', 'url' => route('flights.search')]
        ];

        return view('frontend.flight-search', compact('settings', 'seoData', 'breadcrumbs'));
    }

    public function locations(Request $request, BookingFlightService $bookingFlightService)
    {
        $request->validate([
            'query' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        try {
            $payload = $bookingFlightService->searchLocations($request->input('query'));
            $locations = $bookingFlightService->normalizeLocations($payload);

            return response()->json([
                'success' => true,
                'locations' => $locations,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'locations' => [],
                'message' => 'Unable to load locations right now.',
            ], 422);
        }
    }

    /**
     * Display Mock Flight Search Results
     */
    public function results(Request $request, BookingFlightService $bookingFlightService)
    {
        $settings = Setting::first();
        $seoData = SeoSetting::where('page_identifier', 'flights')->first();

        // Retrieve search query params
        $from = strtoupper($request->input('from', 'JFK'));
        $to = strtoupper($request->input('to', 'LAX'));
        $departDate = $request->input('depart', date('Y-m-d', strtotime('+7 days')));
        $returnDate = $request->input('return');
        $tripType = $request->input('trip_type', 'round_trip');
        $cabinClass = strtolower(str_replace(' ', '_', $request->input('cabin_class', 'economy')));
        $passengers = $request->input('total_passengers', 1);

        // Normalize trip type dashes
        $tripType = str_replace('-', '_', $tripType);

        $apiResult = $bookingFlightService->searchFlights($request);
        $mockFlights = $apiResult['flights'];

        // Apply filters (if present)
        $stopsFilter = $request->input('stops'); // 'any', 'nonstop', '1', '2'
        $priceFilter = $request->input('price_max');
        $timeFilter = $request->input('time_period'); // Array of morning/afternoon/evening/night
        $airlineFilter = $request->input('airlines'); // Array of airline codes
        $sortBy = $request->input('sort', 'price_asc');

        if ($stopsFilter !== null && $stopsFilter !== 'any') {
            $stopsCount = $stopsFilter == 'nonstop' ? 0 : intval($stopsFilter);
            $mockFlights = array_filter($mockFlights, function ($flight) use ($stopsCount) {
                return $flight['stops'] == $stopsCount;
            });
        }

        if ($priceFilter !== null) {
            $mockFlights = array_filter($mockFlights, function ($flight) use ($priceFilter) {
                return $flight['price'] <= intval($priceFilter);
            });
        }

        if (!empty($timeFilter)) {
            $mockFlights = array_filter($mockFlights, function ($flight) use ($timeFilter) {
                return in_array($flight['time_period'], (array)$timeFilter);
            });
        }

        if (!empty($airlineFilter)) {
            $mockFlights = array_filter($mockFlights, function ($flight) use ($airlineFilter) {
                return in_array($flight['airline_code'], (array)$airlineFilter);
            });
        }

        // Apply Sorting
        usort($mockFlights, function ($a, $b) use ($sortBy) {
            if ($sortBy == 'price_asc') {
                return $a['price'] <=> $b['price'];
            } elseif ($sortBy == 'price_desc') {
                return $b['price'] <=> $a['price'];
            } elseif ($sortBy == 'duration') {
                return $a['duration_hours'] <=> $b['duration_hours'];
            } elseif ($sortBy == 'departure') {
                return strcmp($a['departure_time'], $b['departure_time']);
            }
            return 0;
        });

        // Set breadcrumbs
        $breadcrumbs = [
            ['title' => 'Search Flights', 'url' => route('flights.search')],
            ['title' => "Results: {$from} to {$to}"]
        ];

        // Unique filters configuration
        $filters = [
            'from' => $from,
            'to' => $to,
            'depart' => $departDate,
            'return' => $returnDate,
            'trip_type' => $tripType,
            'cabin_class' => $cabinClass,
            'passengers' => $passengers
        ];

        return view('frontend.flight-results', compact('settings', 'filters', 'mockFlights', 'seoData', 'breadcrumbs', 'apiResult'));
    }

    /**
     * Display Mock Flight Details
     */
    public function details($id, Request $request, BookingFlightService $bookingFlightService)
    {
        $settings = Setting::first();
        $seoData = SeoSetting::where('page_identifier', 'flights')->first();

        if ($request->filled('token')) {
            $apiFlight = $bookingFlightService->flightDetails($request->input('token'), $request);

            if ($apiFlight) {
                $price = (float) ($apiFlight['price'] ?: 0);
                $flight = array_merge($apiFlight, [
                    'cancellation_policy' => $apiFlight['refund_policy'] ?? 'Fare rules are provided by the operating airline and Booking.com15.',
                    'base_fare' => round($price * 0.85, 2),
                    'taxes' => round($price * 0.10, 2),
                    'fees' => round($price * 0.05, 2),
                    'total' => $price,
                ]);

                $breadcrumbs = [
                    ['title' => 'Search Flights', 'url' => route('flights.search')],
                    ['title' => "Flight Details"]
                ];

                return view('frontend.flight-details', compact('settings', 'flight', 'seoData', 'breadcrumbs'));
            }
        }

        // Reconstruct the selected mock flight
        $airlines = [
            101 => ['name' => 'Delta Air Lines', 'code' => 'DL', 'logo' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=80&h=80&fit=crop'],
            102 => ['name' => 'American Airlines', 'code' => 'AA', 'logo' => 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=80&h=80&fit=crop'],
            103 => ['name' => 'United Airlines', 'code' => 'UA', 'logo' => 'https://images.unsplash.com/photo-1483450388369-9ed95738483c?w=80&h=80&fit=crop'],
            104 => ['name' => 'Alaska Airlines', 'code' => 'AS', 'logo' => 'https://images.unsplash.com/photo-1517999144091-3d9dca6d1e43?w=80&h=80&fit=crop'],
            105 => ['name' => 'Delta Air Lines', 'code' => 'DL', 'logo' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=80&h=80&fit=crop'],
            106 => ['name' => 'American Airlines', 'code' => 'AA', 'logo' => 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=80&h=80&fit=crop'],
        ];

        $id = intval($id);
        $airlineId = $id % 4 + 101;
        $airline = $airlines[$airlineId] ?? $airlines[101];
        
        $price = 129 + (($id - 101) * 35);
        $stops = ($id - 101) % 3;

        $flight = [
            'id' => $id,
            'airline_name' => $airline['name'],
            'airline_code' => $airline['code'],
            'airline_logo' => $airline['logo'],
            'from' => request('from', 'JFK'),
            'to' => request('to', 'LAX'),
            'departure_time' => '10:30 AM',
            'arrival_time' => '1:45 PM',
            'duration' => '6h 15m',
            'stops' => $stops,
            'price' => $price,
            'cabin_class' => request('cabin_class', 'Economy'),
            'baggage_allowance' => '1 Carry-on bag + 2 Checked bags (23kg/50lbs each) included.',
            'cancellation_policy' => 'Changes allowed up to 24 hours before departure with no additional airline change fee. Refundable as flight credit, or direct cash refund subject to a $75 cancellation processing charge.',
            'base_fare' => round($price * 0.85),
            'taxes' => round($price * 0.10),
            'fees' => round($price * 0.05),
            'total' => $price
        ];

        $breadcrumbs = [
            ['title' => 'Search Flights', 'url' => route('flights.search')],
            ['title' => "Flight Details"]
        ];

        return view('frontend.flight-details', compact('settings', 'flight', 'seoData', 'breadcrumbs'));
    }

    /**
     * Booking Enquiry Form Page
     */
    public function enquiryForm(Request $request)
    {
        $settings = Setting::first();
        $seoData = SeoSetting::where('page_identifier', 'newsletter')->first(); // Use a fallback or custom meta
        
        $breadcrumbs = [
            ['title' => 'Booking Enquiry']
        ];

        return view('frontend.booking-enquiry', compact('settings', 'seoData', 'breadcrumbs'));
    }

    /**
     * Submit Booking Enquiry Request
     */
    public function submitEnquiry(Request $request)
    {
        // Normalize Request Input to validate clean enum configurations
        $data = $request->all();
        if (isset($data['trip_type'])) {
            $data['trip_type'] = str_replace('-', '_', strtolower($data['trip_type']));
        }
        if (isset($data['cabin_class'])) {
            $data['cabin_class'] = str_replace(' ', '_', strtolower($data['cabin_class']));
        }

        // Validate manually with customized errors or merge back to use FormRequest validation
        $request->merge($data);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'from_airport' => 'required|string|max:100',
            'to_airport' => 'required|string|max:100',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'cabin_class' => 'required|string|in:economy,premium_economy,business,first',
            'trip_type' => 'required|string|in:one_way,round_trip,multi_city',
            'preferred_airline' => 'nullable|string|max:100',
            'budget' => 'nullable|string|max:50',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        try {
            $enquiry = FlightEnquiry::create(array_merge($validated, [
                'ip' => $request->ip(),
                'status' => 'new'
            ]));

            // Generate an attractive reference number
            $refNumber = 'DF-' . date('ymd') . '-' . str_pad($enquiry->id, 4, '0', STR_PAD_LEFT);
            $enquiry->update(['admin_notes' => 'Generated Ref: ' . $refNumber]);

            return redirect()->route('booking.enquiry')
                ->with('success', 'Your booking enquiry has been submitted successfully!')
                ->with('ref_number', $refNumber);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred while processing your request. Please try calling us directly.');
        }
    }
}
