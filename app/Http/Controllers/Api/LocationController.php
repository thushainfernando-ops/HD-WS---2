<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleMapsService;
use App\Models\MenuItem;
use Illuminate\Http\Request;

/**
 * Location API Controller
 * 
 * Handles location-based features
 * - Calculate delivery distance
 * - Estimate delivery time
 * - Geocoding services
 */
class LocationController extends Controller
{
    protected $mapsService;

    public function __construct(GoogleMapsService $mapsService)
    {
        $this->mapsService = $mapsService;
    }

    /**
     * Calculate delivery distance and time
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateDelivery(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
        ]);

        $result = $this->mapsService->calculateDistance(
            $validated['origin'],
            $validated['destination']
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        // Estimate delivery time based on distance
        $delivery = $this->mapsService->estimateDeliveryTime(
            $result['distance_km']
        );

        return response()->json([
            'success' => true,
            'distance_km' => $result['distance_km'],
            'distance_text' => $result['distance_text'],
            'duration_text' => $result['duration_text'],
            'estimated_delivery' => $delivery,
        ]);
    }

    /**
     * Geocode an address to coordinates
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function geocodeAddress(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string',
        ]);

        $result = $this->mapsService->geocodeAddress($validated['address']);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'latitude' => $result['latitude'],
            'longitude' => $result['longitude'],
            'formatted_address' => $result['formatted_address'],
        ]);
    }

    /**
     * Reverse geocode coordinates to address
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reverseGeocode(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $result = $this->mapsService->reverseGeocode(
            $validated['latitude'],
            $validated['longitude']
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'formatted_address' => $result['formatted_address'],
            'address_components' => $result['address_components'],
        ]);
    }
}
