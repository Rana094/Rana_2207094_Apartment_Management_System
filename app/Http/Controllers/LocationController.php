<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    /**
     * Generate the static map image shown on the contact page using Geoapify.
     */
    public function apartmentMap(): Response
    {
        $apiKey = config('services.geoapify.api_key');
        abort_unless($apiKey, 503, 'Geoapify API key is not configured.');

        $lat = (float) config('services.geoapify.apartment_lat');
        $lon = (float) config('services.geoapify.apartment_lon');

        // The HTTP client encodes # in the marker color, so pass the raw color value here.
        $response = Http::timeout(8)->get('https://maps.geoapify.com/v1/staticmap', [
            'style' => 'osm-bright',
            'width' => 900,
            'height' => 360,
            'center' => "lonlat:$lon,$lat",
            'zoom' => 16,
            'marker' => "lonlat:$lon,$lat;color:#0f766e;type:awesome;icon:building",
            'apiKey' => $apiKey,
        ]);

        abort_unless($response->successful(), 503, 'Map is temporarily unavailable.');

        return response($response->body(), 200, [
            'Content-Type' => $response->header('Content-Type', 'image/png'),
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Return an approximate location for the current visitor IP address.
     */
    public function currentIpLocation(Request $request): JsonResponse
    {
        $response = Http::timeout(8)->get('http://ip-api.com/json/', [
            'fields' => 'status,message,city,regionName,country,lat,lon,query',
        ]);

        if (! $response->successful() || $response->json('status') !== 'success') {
            return response()->json([
                'available' => false,
                'message' => $response->json('message', 'Location lookup is temporarily unavailable.'),
            ], 503);
        }

        return response()->json([
            'available' => true,
            'location' => $response->json(),
        ]);
    }
}
