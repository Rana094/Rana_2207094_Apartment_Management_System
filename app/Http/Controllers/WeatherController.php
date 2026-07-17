<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function current(Request $request, WeatherService $weather): JsonResponse
    {
        $validated = $request->validate([
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        return response()->json(
            $weather->current(
                isset($validated['lat']) ? (float) $validated['lat'] : null,
                isset($validated['lon']) ? (float) $validated['lon'] : null,
            )
        );
    }
}
