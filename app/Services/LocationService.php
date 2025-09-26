<?php

namespace App\Services;

class LocationService
{
    public function getLocation(string $ip): ?array
    {
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return [
                'city' => 'Localhost',
                'country' => 'Local',
                'countryCode' => 'LOCAL'
            ];
        }

        try {
            $response = file_get_contents("http://ip-api.com/json/{$ip}");
            $data = json_decode($response, true);

            if ($data && $data['status'] === 'success') {
                return [
                    'city' => $data['city'] ?? 'Unknown',
                    'country' => $data['country'] ?? 'Unknown',
                    'countryCode' => $data['countryCode'] ?? 'UN'
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Error getting location: ' . $e->getMessage());
        }

        return null;
    }
}
