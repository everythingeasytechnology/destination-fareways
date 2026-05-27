<?php

namespace App\Services;

use App\Models\ApiSetting;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BookingFlightService
{
    public function settings(): ApiSetting
    {
        $setting = ApiSetting::firstOrCreate(['id' => 1], $this->defaultSettings());
        $defaults = $this->defaultSettings();
        $needsProviderMigration = Str::contains(strtolower((string) $setting->provider), 'amadeus');
        $bookingFields = [
            'provider',
            'base_url',
            'rapidapi_host',
            'mode',
            'endpoint_location',
            'endpoint_search',
            'endpoint_multi_stop',
            'endpoint_details',
            'endpoint_min_price',
            'endpoint_booking',
            'endpoint_fare_rules',
            'endpoint_cancellation',
            'endpoint_refund',
            'endpoint_webhook',
            'language_code',
        ];

        $dirty = false;
        foreach ($bookingFields as $field) {
            if (blank($setting->{$field}) || $needsProviderMigration) {
                $setting->{$field} = $defaults[$field];
                $dirty = true;
            }
        }

        if ($dirty) {
            $setting->save();
        }

        return $setting;
    }

    public function defaultSettings(): array
    {
        return [
            'provider' => 'Booking.com15 - DataCrawler',
            'base_url' => 'https://booking-com15.p.rapidapi.com',
            'rapidapi_host' => 'booking-com15.p.rapidapi.com',
            'mode' => 'live',
            'endpoint_location' => '/api/v1/flights/searchDestination',
            'endpoint_search' => '/api/v1/flights/searchFlights',
            'endpoint_multi_stop' => '/api/v1/flights/searchFlightsMultiStops',
            'endpoint_details' => '/api/v1/flights/getFlightDetails',
            'endpoint_min_price' => '/api/v1/flights/getMinPrice',
            'endpoint_booking' => '/api/v1/flights/getSeatMap',
            'endpoint_fare_rules' => '/api/v1/flights/getMinPrice',
            'endpoint_cancellation' => '/api/manual/offline-cancellation',
            'endpoint_refund' => '/api/manual/offline-refund',
            'endpoint_webhook' => '/api/webhooks/booking-com15',
            'api_status' => 'inactive',
            'currency' => 'USD',
            'language_code' => 'en-us',
            'markup_percent' => 5.00,
            'commission_percent' => 3.00,
        ];
    }

    public function isConfigured(?ApiSetting $setting = null): bool
    {
        $setting = $setting ?: $this->settings();

        return $setting->api_status === 'active'
            && filled($setting->api_key)
            && filled($setting->base_url)
            && filled($setting->rapidapi_host);
    }

    public function searchLocations(string $query): array
    {
        $setting = $this->settings();

        return $this->get($setting->endpoint_location, [
            'query' => $query,
            'languagecode' => $setting->language_code ?: 'en-us',
        ], $setting);
    }

    public function normalizeLocations(array $payload): array
    {
        $items = data_get($payload, 'data') ?: data_get($payload, 'result') ?: [];

        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->take(8)
            ->map(function ($item) {
                if (! is_array($item)) {
                    return null;
                }

                $id = $item['id'] ?? $item['dest_id'] ?? $this->firstValueForKeys($item, ['id', 'dest_id']);
                $code = $item['code'] ?? $item['iata'] ?? $item['airportCode'] ?? null;
                $name = $item['name'] ?? $item['label'] ?? $item['cityName'] ?? $item['city'] ?? null;
                $country = $item['countryName'] ?? $item['country'] ?? null;
                $type = $item['type'] ?? $item['search_type'] ?? 'location';

                if (blank($id) && filled($code)) {
                    $id = strtoupper($code) . '.AIRPORT';
                }

                if (blank($name) || blank($id)) {
                    return null;
                }

                $displayCode = $code ?: Str::before($id, '.');
                $labelParts = array_filter([$name, $displayCode ? strtoupper($displayCode) : null, $country]);

                return [
                    'id' => $id,
                    'code' => $displayCode ? strtoupper($displayCode) : null,
                    'name' => $name,
                    'country' => $country,
                    'type' => $type,
                    'label' => implode(' - ', $labelParts),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    public function searchFlights(Request $request): array
    {
        $setting = $this->settings();
        $tripType = str_replace('-', '_', $request->input('trip_type', 'round_trip'));

        if (! $this->isConfigured($setting)) {
            return [
                'source' => 'fallback',
                'error' => 'Booking.com15 API is inactive or API key is missing.',
                'flights' => $this->mockFlights($request),
            ];
        }

        try {
            $payload = $tripType === 'multi_city'
                ? $this->searchMultiStopFlights($request, $setting)
                : $this->searchRoundTripFlights($request, $setting);

            $flights = $this->normalizeFlights($payload, $request);

            return [
                'source' => 'booking_com15',
                'error' => null,
                'flights' => $flights ?: $this->mockFlights($request),
                'raw' => $payload,
            ];
        } catch (\Throwable $e) {
            return [
                'source' => 'fallback',
                'error' => $e->getMessage(),
                'flights' => $this->mockFlights($request),
            ];
        }
    }

    public function flightDetails(string $token, Request $request): ?array
    {
        $setting = $this->settings();

        if (! $this->isConfigured($setting) || blank($token)) {
            return null;
        }

        $payload = $this->get($setting->endpoint_details, [
            'token' => $token,
            'currency_code' => $setting->currency ?: 'USD',
        ], $setting);

        $details = data_get($payload, 'data') ?: $payload;
        $normalized = $this->normalizeOffer($details, 0, $request);
        $normalized['raw_details'] = $details;

        return $normalized;
    }

    public function testConnection(): array
    {
        $payload = $this->searchLocations('Delhi');
        $count = is_array(data_get($payload, 'data')) ? count(data_get($payload, 'data')) : 0;

        return [
            'payload' => $payload,
            'message' => 'Booking.com15 connection successful. Location records returned: ' . $count,
        ];
    }

    private function searchRoundTripFlights(Request $request, ApiSetting $setting): array
    {
        $fromId = $request->input('fromId') ?: $this->resolveLocationId($request->input('from', 'JFK'), $setting);
        $toId = $request->input('toId') ?: $this->resolveLocationId($request->input('to', 'LAX'), $setting);

        $params = [
            'fromId' => $fromId,
            'toId' => $toId,
            'departDate' => $request->input('depart', now()->addWeek()->toDateString()),
            'pageNo' => $request->input('pageNo', 1),
            'adults' => max(1, (int) $request->input('adults', 1)),
            'sort' => $this->mapSort($request->input('sort', 'price_asc')),
            'cabinClass' => $this->mapCabinClass($request->input('cabin_class', 'Economy')),
            'currency_code' => $setting->currency ?: 'USD',
        ];

        if ($request->filled('return') && str_replace('-', '_', $request->input('trip_type')) !== 'one_way') {
            $params['returnDate'] = $request->input('return');
        }

        if ((int) $request->input('children', 0) > 0) {
            $params['children'] = implode(',', array_fill(0, (int) $request->input('children'), 8));
        }

        if ($request->filled('stops') && $request->input('stops') !== 'any') {
            $params['stops'] = $request->input('stops') === 'nonstop' ? '0' : $request->input('stops');
        } else {
            $params['stops'] = 'none';
        }

        return $this->get($setting->endpoint_search, $params, $setting);
    }

    private function searchMultiStopFlights(Request $request, ApiSetting $setting): array
    {
        $legs = collect($request->input('legs', []))
            ->filter(fn ($leg) => filled($leg['from'] ?? null) && filled($leg['to'] ?? null) && filled($leg['date'] ?? null))
            ->map(function ($leg) use ($setting) {
                return [
                    'fromId' => $this->resolveLocationId($leg['from'], $setting),
                    'toId' => $this->resolveLocationId($leg['to'], $setting),
                    'date' => $leg['date'],
                ];
            })
            ->values()
            ->all();

        if (count($legs) < 2) {
            $legs = [
                [
                    'fromId' => $this->resolveLocationId($request->input('from', 'JFK'), $setting),
                    'toId' => $this->resolveLocationId($request->input('to', 'LAX'), $setting),
                    'date' => $request->input('depart', now()->addWeek()->toDateString()),
                ],
                [
                    'fromId' => $this->resolveLocationId($request->input('to', 'LAX'), $setting),
                    'toId' => $this->resolveLocationId($request->input('from', 'JFK'), $setting),
                    'date' => $request->input('return', now()->addWeeks(2)->toDateString()),
                ],
            ];
        }

        return $this->get($setting->endpoint_multi_stop, [
            'legs' => json_encode($legs),
            'pageNo' => $request->input('pageNo', 1),
            'adults' => max(1, (int) $request->input('adults', 1)),
            'sort' => $this->mapSort($request->input('sort', 'price_asc')),
            'cabinClass' => $this->mapCabinClass($request->input('cabin_class', 'Economy')),
            'currency_code' => $setting->currency ?: 'USD',
        ], $setting);
    }

    private function get(?string $endpoint, array $params, ApiSetting $setting): array
    {
        $baseUrl = $this->normalizeEndpointText($setting->base_url);
        $host = $this->normalizeEndpointText($setting->rapidapi_host);
        $url = rtrim($baseUrl, '/') . '/' . ltrim($this->normalizeEndpointText($endpoint ?: ''), '/');

        $response = Http::timeout(20)
            ->retry(1, 250)
            ->withOptions($this->curlOptions($host))
            ->withHeaders([
                'x-rapidapi-key' => $setting->api_key,
                'x-rapidapi-host' => $host,
                'Accept' => 'application/json',
            ])
            ->get($url, array_filter($params, fn ($value) => $value !== null && $value !== ''));

        if ($response->failed()) {
            throw new RequestException($response);
        }

        return $response->json() ?: [];
    }

    private function resolveLocationId(string $value, ApiSetting $setting): string
    {
        $value = trim($value);

        if (Str::contains($value, '.')) {
            return strtoupper($value);
        }

        if (preg_match('/^[A-Za-z]{3}$/', $value)) {
            return strtoupper($value) . '.AIRPORT';
        }

        $payload = $this->get($setting->endpoint_location, [
            'query' => $value,
            'languagecode' => $setting->language_code ?: 'en-us',
        ], $setting);

        return $this->firstValueForKeys($payload, ['id', 'dest_id']) ?: strtoupper(Str::substr($value, 0, 3)) . '.AIRPORT';
    }

    private function normalizeFlights(array $payload, Request $request): array
    {
        $offers = data_get($payload, 'data.flightOffers')
            ?: data_get($payload, 'data.flights')
            ?: data_get($payload, 'flightOffers')
            ?: [];

        if (! is_array($offers)) {
            return [];
        }

        return collect($offers)
            ->take(30)
            ->map(fn ($offer, $index) => $this->normalizeOffer($offer, $index, $request))
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeOffer(array $offer, int $index, Request $request): array
    {
        $segment = data_get($offer, 'segments.0', []);
        $lastSegment = Arr::last(data_get($offer, 'segments', [])) ?: $segment;
        $leg = data_get($segment, 'legs.0', []);
        $carrier = data_get($leg, 'carriersData.0', []);
        $priceData = data_get($offer, 'priceBreakdown.total', []);
        $price = (float) data_get($priceData, 'units', data_get($offer, 'price', 0));

        if (isset($priceData['nanos'])) {
            $price += ((float) $priceData['nanos']) / 1000000000;
        }

        $setting = $this->settings();
        $markup = 1 + (((float) $setting->markup_percent) / 100);
        $price = $price > 0 ? round($price * $markup) : 0;

        $from = data_get($segment, 'departureAirport.code')
            ?: data_get($segment, 'departureAirport.cityName')
            ?: strtoupper($request->input('from', 'JFK'));
        $to = data_get($lastSegment, 'arrivalAirport.code')
            ?: data_get($lastSegment, 'arrivalAirport.cityName')
            ?: strtoupper($request->input('to', 'LAX'));

        $departTime = $this->formatTime(data_get($segment, 'departureTime'));
        $arrivalTime = $this->formatTime(data_get($lastSegment, 'arrivalTime'));
        $stops = max(0, count(data_get($offer, 'segments', [])) - 1);

        return [
            'id' => 'booking-' . ($index + 1),
            'token' => $offer['token'] ?? $offer['offerToken'] ?? null,
            'airline_name' => data_get($carrier, 'name', data_get($offer, 'airline', 'Booking.com Flight')),
            'airline_code' => data_get($carrier, 'code', data_get($carrier, 'carrierCode', 'BK')),
            'airline_logo' => data_get($carrier, 'logo', 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=80&h=80&fit=crop'),
            'from' => $from,
            'to' => $to,
            'departure_time' => $departTime,
            'arrival_time' => $arrivalTime,
            'time_period' => $this->timePeriod($departTime),
            'duration' => $this->formatDuration(data_get($offer, 'totalTime', data_get($segment, 'totalTime'))),
            'duration_hours' => $this->durationHours(data_get($offer, 'totalTime', data_get($segment, 'totalTime'))),
            'stops' => $stops,
            'price' => $price,
            'currency' => data_get($priceData, 'currencyCode', $setting->currency ?: 'USD'),
            'cabin_class' => ucwords(strtolower(str_replace('_', ' ', $request->input('cabin_class', 'Economy')))),
            'baggage_allowance' => $this->firstValueForKeys($offer, ['baggageAllowance', 'baggage']) ?: 'Check airline baggage rules',
            'refund_policy' => $this->firstValueForKeys($offer, ['refundPolicy', 'fareRules']) ?: 'Fare rules from airline',
            'is_featured' => $index === 0,
            'raw' => $offer,
        ];
    }

    public function mockFlights(Request $request): array
    {
        $from = strtoupper($request->input('from', 'JFK'));
        $to = strtoupper($request->input('to', 'LAX'));
        $cabinClass = strtolower(str_replace(' ', '_', $request->input('cabin_class', 'economy')));
        $airlines = [
            ['name' => 'Delta Air Lines', 'code' => 'DL'],
            ['name' => 'American Airlines', 'code' => 'AA'],
            ['name' => 'United Airlines', 'code' => 'UA'],
            ['name' => 'Alaska Airlines', 'code' => 'AS'],
        ];
        $basePrices = ['economy' => 129, 'premium_economy' => 299, 'business' => 599, 'first' => 999];
        $times = [['06:15', '09:30'], ['10:30', '13:45'], ['14:45', '18:00'], ['19:00', '22:15'], ['23:30', '02:45']];

        return collect(range(0, 5))->map(function ($i) use ($from, $to, $cabinClass, $airlines, $basePrices, $times) {
            $airline = $airlines[$i % count($airlines)];
            $price = round(($basePrices[$cabinClass] ?? 129) * (1 + ($i * 0.08)));
            $stops = $i % 3;

            return [
                'id' => $i + 101,
                'token' => null,
                'airline_name' => $airline['name'],
                'airline_code' => $airline['code'],
                'airline_logo' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=80&h=80&fit=crop',
                'from' => $from,
                'to' => $to,
                'departure_time' => $times[$i % count($times)][0],
                'arrival_time' => $times[$i % count($times)][1],
                'time_period' => $this->timePeriod($times[$i % count($times)][0]),
                'duration' => (3 + ($stops * 2)) . 'h ' . (($i * 10) % 60) . 'm',
                'duration_hours' => 3 + ($stops * 2),
                'stops' => $stops,
                'price' => $price,
                'currency' => 'USD',
                'cabin_class' => ucwords(str_replace('_', ' ', $cabinClass)),
                'baggage_allowance' => 'Carry-on included. Checked baggage depends on airline fare.',
                'refund_policy' => 'Fare rules apply',
                'is_featured' => $i === 0,
            ];
        })->all();
    }

    private function mapSort(string $sort): string
    {
        return match ($sort) {
            'duration' => 'FASTEST',
            'price_desc', 'price_asc' => 'CHEAPEST',
            default => 'BEST',
        };
    }

    private function mapCabinClass(string $class): string
    {
        return strtoupper(str_replace(' ', '_', $class));
    }

    private function formatTime(?string $value): string
    {
        if (! $value) {
            return 'TBA';
        }

        try {
            return date('H:i', strtotime($value));
        } catch (\Throwable) {
            return $value;
        }
    }

    private function formatDuration($minutes): string
    {
        $minutes = (int) $minutes;

        if ($minutes <= 0) {
            return 'TBA';
        }

        return floor($minutes / 60) . 'h ' . ($minutes % 60) . 'm';
    }

    private function durationHours($minutes): float
    {
        return max(0.1, ((int) $minutes) / 60);
    }

    private function timePeriod(string $time): string
    {
        $hour = (int) Str::before($time, ':');

        return match (true) {
            $hour >= 6 && $hour < 12 => 'morning',
            $hour >= 12 && $hour < 18 => 'afternoon',
            $hour >= 18 && $hour < 24 => 'evening',
            default => 'night',
        };
    }

    private function firstValueForKeys($payload, array $keys): ?string
    {
        if (! is_array($payload)) {
            return null;
        }

        foreach ($payload as $key => $value) {
            if (in_array($key, $keys, true) && is_scalar($value) && filled($value)) {
                return (string) $value;
            }

            if (is_array($value)) {
                $found = $this->firstValueForKeys($value, $keys);
                if ($found) {
                    return $found;
                }
            }
        }

        return null;
    }

    private function normalizeEndpointText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim(str_replace(['–', '—', '−'], '-', $value));
    }

    private function curlOptions(?string $host): array
    {
        if ($host !== 'booking-com15.p.rapidapi.com' || ! defined('CURLOPT_RESOLVE')) {
            return [];
        }

        return [
            'curl' => [
                CURLOPT_RESOLVE => [
                    'booking-com15.p.rapidapi.com:443:52.76.106.128',
                    'booking-com15.p.rapidapi.com:443:18.136.59.144',
                ],
            ],
        ];
    }
}
