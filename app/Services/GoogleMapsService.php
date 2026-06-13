<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Google Maps Service
 * 
 * Handles location-based features for Hot Dish
 * - Calculate delivery distance
 * - Estimate delivery time
 * - Geolocation services
 * - Store location verification
 */
class GoogleMapsService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl = 'https://maps.googleapis.com/maps/api';

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_MAPS_API_KEY');
        $this->client = new Client();
    }

    /**
     * Calculate distance between two coordinates
     * 
     * @param string $origin Origin address or coordinates (lat,lng)
     * @param string $destination Destination address or coordinates (lat,lng)
     * @return array Distance and duration
     */
    public function calculateDistance($origin, $destination)
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/distancematrix/json", [
                'query' => [
                    'origins' => $origin,
                    'destinations' => $destination,
                    'key' => $this->apiKey,
                    'units' => 'metric',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'OK' && count($data['rows']) > 0) {
                $element = $data['rows'][0]['elements'][0];

                if ($element['status'] === 'OK') {
                    Log::info('Distance Calculated', [
                        'origin' => $origin,
                        'destination' => $destination,
                        'distance' => $element['distance']['value'],
                    ]);

                    return [
                        'success' => true,
                        'distance_km' => $element['distance']['value'] / 1000,
                        'distance_text' => $element['distance']['text'],
                        'duration' => $element['duration']['value'],
                        'duration_text' => $element['duration']['text'],
                    ];
                }
            }

            return ['success' => false, 'error' => 'Could not calculate distance'];
        } catch (\Exception $e) {
            Log::error('Google Maps Distance Calculation Failed', [
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get geocode for an address
     * 
     * @param string $address Street address
     * @return array Coordinates and place details
     */
    public function geocodeAddress($address)
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/geocode/json", [
                'query' => [
                    'address' => $address,
                    'key' => $this->apiKey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'OK' && count($data['results']) > 0) {
                $location = $data['results'][0]['geometry']['location'];

                return [
                    'success' => true,
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng'],
                    'formatted_address' => $data['results'][0]['formatted_address'],
                ];
            }

            return ['success' => false, 'error' => 'Address not found'];
        } catch (\Exception $e) {
            Log::error('Google Maps Geocoding Failed', [
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Reverse geocode coordinates to address
     * 
     * @param float $latitude Latitude
     * @param float $longitude Longitude
     * @return array Address details
     */
    public function reverseGeocode($latitude, $longitude)
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/geocode/json", [
                'query' => [
                    'latlng' => "{$latitude},{$longitude}",
                    'key' => $this->apiKey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'OK' && count($data['results']) > 0) {
                return [
                    'success' => true,
                    'formatted_address' => $data['results'][0]['formatted_address'],
                    'address_components' => $data['results'][0]['address_components'],
                ];
            }

            return ['success' => false, 'error' => 'Location not found'];
        } catch (\Exception $e) {
            Log::error('Google Maps Reverse Geocoding Failed', [
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Estimate delivery time based on distance and traffic
     * 
     * @param float $distance Distance in kilometers
     * @return array Estimated delivery time
     */
    public function estimateDeliveryTime($distance)
    {
        // Simple estimation: assume 2 mins prep + 3 km/min delivery speed
        $prepTime = 2; // minutes
        $deliveryTime = ceil($distance / 3); // minutes

        return [
            'prep_time' => $prepTime,
            'delivery_time' => $deliveryTime,
            'total_time' => $prepTime + $deliveryTime,
            'estimated_arrival' => now()->addMinutes($prepTime + $deliveryTime)->format('H:i A'),
        ];
    }

    /**
     * Get nearest location (simple distance-based)
     * 
     * @param float $customerLat Customer latitude
     * @param float $customerLng Customer longitude
     * @param array $locations Array of locations with lat/lng
     * @return array Nearest location
     */
    public function getNearestLocation($customerLat, $customerLng, $locations)
    {
        $nearest = null;
        $minDistance = PHP_INT_MAX;

        foreach ($locations as $location) {
            $distance = $this->getHaversineDistance(
                $customerLat,
                $customerLng,
                $location['latitude'],
                $location['longitude']
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearest = array_merge($location, ['distance_km' => $distance]);
            }
        }

        return $nearest;
    }

    /**
     * Calculate Haversine distance between two coordinates
     * 
     * @param float $lat1 First latitude
     * @param float $lon1 First longitude
     * @param float $lat2 Second latitude
     * @param float $lon2 Second longitude
     * @return float Distance in kilometers
     */
    private function getHaversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * asin(sqrt($a));

        return $earth_radius * $c;
    }
}
