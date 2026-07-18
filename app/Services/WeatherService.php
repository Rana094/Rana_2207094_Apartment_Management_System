<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function current(?float $lat = null, ?float $lon = null): array
    {
        $apiKey = config('services.openweather.api_key');

        if (! $apiKey) {
            return [
                'available' => false,
                'message' => 'OpenWeather API key is not configured.',
            ];
        }

        $location = null;

        if ($lat === null || $lon === null) {
            $location = $this->currentIpLocation();
            $lat = $location['lat'] ?? (float) config('services.openweather.default_lat');
            $lon = $location['lon'] ?? (float) config('services.openweather.default_lon');
        }

        $response = Http::timeout(8)->get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $apiKey,
            'units' => 'metric',
        ]);

        if (! $response->successful()) {
            return [
                'available' => false,
                'message' => 'Weather data is temporarily unavailable.',
            ];
        }

        $payload = $response->json();
        $condition = $payload['weather'][0]['main'] ?? 'Unknown';
        $description = $payload['weather'][0]['description'] ?? 'No description available';
        $temp = $payload['main']['temp'] ?? null;
        $feelsLike = $payload['main']['feels_like'] ?? null;
        $wind = $payload['wind']['speed'] ?? null;

        return [
            'available' => true,
            'location' => $payload['name'] ?? $location['city'] ?? 'Current area',
            'condition' => $condition,
            'description' => ucfirst($description),
            'temperature' => $temp,
            'feels_like' => $feelsLike,
            'humidity' => $payload['main']['humidity'] ?? null,
            'wind_speed' => $wind,
            'safety_message' => $this->safetyMessage($condition, $temp, $wind),
            'source' => $location ? 'ip-api' : 'configured coordinates',
        ];
    }

    private function currentIpLocation(): ?array
    {
        $response = Http::timeout(8)->get('http://ip-api.com/json/', [
            'fields' => 'status,message,city,regionName,country,lat,lon,query',
        ]);

        if (! $response->successful() || $response->json('status') !== 'success') {
            return null;
        }

        return $response->json();
    }

    private function safetyMessage(string $condition, mixed $temp, mixed $wind): string
    {
        $condition = strtolower($condition);

        if (str_contains($condition, 'thunderstorm') || str_contains($condition, 'rain')) {
            return 'Outdoor rooftop booking may be unsafe because rain or storm conditions are detected.';
        }

        if (is_numeric($wind) && (float) $wind >= 10) {
            return 'Strong wind detected. Rooftop setup should be reviewed before approval.';
        }

        if (is_numeric($temp) && (float) $temp >= 36) {
            return 'High heat detected. Residents should arrange shade and water for rooftop use.';
        }

        return 'Weather looks acceptable for outdoor facility use.';
    }
}
