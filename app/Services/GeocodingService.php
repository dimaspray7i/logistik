<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Known regional hubs for offline / instant resolution without network delay.
     */
    protected static array $knownLocations = [
        'medan' => ['lat' => 3.5952, 'lng' => 98.6722, 'city' => 'Medan', 'province' => 'Sumatera Utara', 'country' => 'Indonesia'],
        'pekanbaru' => ['lat' => 0.5071, 'lng' => 101.4478, 'city' => 'Pekanbaru', 'province' => 'Riau', 'country' => 'Indonesia'],
        'palembang' => ['lat' => -2.9761, 'lng' => 104.7754, 'city' => 'Palembang', 'province' => 'Sumatera Selatan', 'country' => 'Indonesia'],
        'padang' => ['lat' => -0.9471, 'lng' => 100.4172, 'city' => 'Padang', 'province' => 'Sumatera Barat', 'country' => 'Indonesia'],
        'batam' => ['lat' => 1.1301, 'lng' => 104.0529, 'city' => 'Batam', 'province' => 'Kepulauan Riau', 'country' => 'Indonesia'],
        'bandar lampung' => ['lat' => -5.4292, 'lng' => 105.2625, 'city' => 'Bandar Lampung', 'province' => 'Lampung', 'country' => 'Indonesia'],
        'lampung' => ['lat' => -5.4292, 'lng' => 105.2625, 'city' => 'Bandar Lampung', 'province' => 'Lampung', 'country' => 'Indonesia'],
        'jakarta' => ['lat' => -6.2088, 'lng' => 106.8456, 'city' => 'Jakarta', 'province' => 'DKI Jakarta', 'country' => 'Indonesia'],
        'tangerang' => ['lat' => -6.1783, 'lng' => 106.6319, 'city' => 'Tangerang', 'province' => 'Banten', 'country' => 'Indonesia'],
        'bekasi' => ['lat' => -6.2383, 'lng' => 106.9756, 'city' => 'Bekasi', 'province' => 'Jawa Barat', 'country' => 'Indonesia'],
        'bogor' => ['lat' => -6.5971, 'lng' => 106.8060, 'city' => 'Bogor', 'province' => 'Jawa Barat', 'country' => 'Indonesia'],
        'bandung' => ['lat' => -6.9175, 'lng' => 107.6191, 'city' => 'Bandung', 'province' => 'Jawa Barat', 'country' => 'Indonesia'],
        'cirebon' => ['lat' => -6.7320, 'lng' => 108.5523, 'city' => 'Cirebon', 'province' => 'Jawa Barat', 'country' => 'Indonesia'],
        'semarang' => ['lat' => -6.9667, 'lng' => 110.4167, 'city' => 'Semarang', 'province' => 'Jawa Tengah', 'country' => 'Indonesia'],
        'solo' => ['lat' => -7.5755, 'lng' => 110.8243, 'city' => 'Surakarta', 'province' => 'Jawa Tengah', 'country' => 'Indonesia'],
        'surakarta' => ['lat' => -7.5755, 'lng' => 110.8243, 'city' => 'Surakarta', 'province' => 'Jawa Tengah', 'country' => 'Indonesia'],
        'yogyakarta' => ['lat' => -7.7956, 'lng' => 110.3695, 'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta', 'country' => 'Indonesia'],
        'jogja' => ['lat' => -7.7956, 'lng' => 110.3695, 'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta', 'country' => 'Indonesia'],
        'surabaya' => ['lat' => -7.2575, 'lng' => 112.7521, 'city' => 'Surabaya', 'province' => 'Jawa Timur', 'country' => 'Indonesia'],
        'malang' => ['lat' => -7.9666, 'lng' => 112.6326, 'city' => 'Malang', 'province' => 'Jawa Timur', 'country' => 'Indonesia'],
        'denpasar' => ['lat' => -8.6705, 'lng' => 115.2126, 'city' => 'Denpasar', 'province' => 'Bali', 'country' => 'Indonesia'],
        'bali' => ['lat' => -8.4095, 'lng' => 115.1889, 'city' => 'Denpasar', 'province' => 'Bali', 'country' => 'Indonesia'],
        'mataram' => ['lat' => -8.5799, 'lng' => 116.1080, 'city' => 'Mataram', 'province' => 'Nusa Tenggara Barat', 'country' => 'Indonesia'],
        'lombok' => ['lat' => -8.5799, 'lng' => 116.1080, 'city' => 'Mataram', 'province' => 'Nusa Tenggara Barat', 'country' => 'Indonesia'],
        'kupang' => ['lat' => -10.1772, 'lng' => 123.6070, 'city' => 'Kupang', 'province' => 'Nusa Tenggara Timur', 'country' => 'Indonesia'],
        'pontianak' => ['lat' => -0.0263, 'lng' => 109.3425, 'city' => 'Pontianak', 'province' => 'Kalimantan Barat', 'country' => 'Indonesia'],
        'banjarmasin' => ['lat' => -3.3194, 'lng' => 114.5908, 'city' => 'Banjarmasin', 'province' => 'Kalimantan Selatan', 'country' => 'Indonesia'],
        'balikpapan' => ['lat' => -1.2379, 'lng' => 116.8529, 'city' => 'Balikpapan', 'province' => 'Kalimantan Timur', 'country' => 'Indonesia'],
        'samarinda' => ['lat' => -0.5022, 'lng' => 117.1536, 'city' => 'Samarinda', 'province' => 'Kalimantan Timur', 'country' => 'Indonesia'],
        'makassar' => ['lat' => -5.1477, 'lng' => 119.4327, 'city' => 'Makassar', 'province' => 'Sulawesi Selatan', 'country' => 'Indonesia'],
        'manado' => ['lat' => 1.4748, 'lng' => 124.8421, 'city' => 'Manado', 'province' => 'Sulawesi Utara', 'country' => 'Indonesia'],
        'ambon' => ['lat' => -3.6547, 'lng' => 128.1906, 'city' => 'Ambon', 'province' => 'Maluku', 'country' => 'Indonesia'],
        'jayapura' => ['lat' => -2.5916, 'lng' => 140.6690, 'city' => 'Jayapura', 'province' => 'Papua', 'country' => 'Indonesia'],
        // International Regional Hubs
        'kuala lumpur' => ['lat' => 3.1390, 'lng' => 101.6869, 'city' => 'Kuala Lumpur', 'province' => 'Wilayah Persekutuan', 'country' => 'Malaysia'],
        'penang' => ['lat' => 5.4164, 'lng' => 100.3327, 'city' => 'George Town', 'province' => 'Penang', 'country' => 'Malaysia'],
        'johor bahru' => ['lat' => 1.4927, 'lng' => 103.7414, 'city' => 'Johor Bahru', 'province' => 'Johor', 'country' => 'Malaysia'],
        'singapore' => ['lat' => 1.3521, 'lng' => 103.8198, 'city' => 'Singapore', 'province' => 'Singapore', 'country' => 'Singapore'],
        'singapura' => ['lat' => 1.3521, 'lng' => 103.8198, 'city' => 'Singapore', 'province' => 'Singapore', 'country' => 'Singapore'],
        'bangkok' => ['lat' => 13.7563, 'lng' => 100.5018, 'city' => 'Bangkok', 'province' => 'Bangkok', 'country' => 'Thailand'],
        'ho chi minh' => ['lat' => 10.8231, 'lng' => 106.6297, 'city' => 'Ho Chi Minh', 'province' => 'Ho Chi Minh', 'country' => 'Vietnam'],
        'hanoi' => ['lat' => 21.0285, 'lng' => 105.8542, 'city' => 'Hanoi', 'province' => 'Hanoi', 'country' => 'Vietnam'],
        'manila' => ['lat' => 14.5995, 'lng' => 120.9842, 'city' => 'Manila', 'province' => 'Metro Manila', 'country' => 'Philippines'],
        'tokyo' => ['lat' => 35.6762, 'lng' => 139.6503, 'city' => 'Tokyo', 'province' => 'Tokyo', 'country' => 'Japan'],
        'beijing' => ['lat' => 39.9042, 'lng' => 116.4074, 'city' => 'Beijing', 'province' => 'Beijing', 'country' => 'China'],
        'sydney' => ['lat' => -33.8688, 'lng' => 151.2093, 'city' => 'Sydney', 'province' => 'New South Wales', 'country' => 'Australia'],
        'melbourne' => ['lat' => -37.8136, 'lng' => 144.9631, 'city' => 'Melbourne', 'province' => 'Victoria', 'country' => 'Australia'],
    ];

    /**
     * Geocode location query string into coordinates and address details.
     */
    public static function geocode(string $query): ?array
    {
        $normalized = trim(strtolower($query));
        if (empty($normalized)) {
            return null;
        }

        // 1. Check offline dictionary for fast, reliable lookup
        foreach (self::$knownLocations as $keyword => $data) {
            if (str_contains($normalized, $keyword)) {
                return [
                    'latitude' => $data['lat'],
                    'longitude' => $data['lng'],
                    'city' => $data['city'],
                    'province' => $data['province'],
                    'country' => $data['country'],
                    'source' => 'dictionary',
                ];
            }
        }

        // 2. Attempt online geocoding via OpenStreetMap Nominatim with strict timeout
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'LogistikCRM/1.0 (Logistics Tracking Geocoder)',
            ])->timeout(3)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => 1,
            ]);

            if ($response->successful() && !empty($response->json())) {
                $item = $response->json()[0];
                $address = $item['address'] ?? [];

                return [
                    'latitude' => round((float) $item['lat'], 8),
                    'longitude' => round((float) $item['lon'], 8),
                    'city' => $address['city'] ?? $address['town'] ?? $address['county'] ?? null,
                    'province' => $address['state'] ?? $address['region'] ?? null,
                    'country' => $address['country'] ?? null,
                    'source' => 'nominatim',
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('GeocodingService network lookup failed: ' . $e->getMessage(), ['query' => $query]);
        }

        return null;
    }
}
