<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FacilityWeatherTest extends TestCase
{
    use RefreshDatabase;

    public function test_weather_endpoint_reports_missing_api_key_clearly(): void
    {
        config(['services.openweather.api_key' => null]);

        $this->actingAs($this->approvedResident())
            ->getJson(route('resident.bookings.weather.current'))
            ->assertOk()
            ->assertJson([
                'available' => false,
                'message' => 'OpenWeather API key is not configured.',
            ]);
    }

    public function test_weather_endpoint_returns_openweather_payload_when_configured(): void
    {
        config([
            'services.openweather.api_key' => 'test-openweather-key',
            'services.openweather.default_lat' => 23.7465,
            'services.openweather.default_lon' => 90.3760,
        ]);

        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'name' => 'Dhaka',
                'weather' => [
                    ['main' => 'Clear', 'description' => 'clear sky'],
                ],
                'main' => [
                    'temp' => 30.4,
                    'feels_like' => 33.1,
                    'humidity' => 70,
                ],
                'wind' => [
                    'speed' => 3.5,
                ],
            ], 200),
        ]);

        $this->actingAs($this->approvedResident())
            ->getJson(route('resident.bookings.weather.current'))
            ->assertOk()
            ->assertJson([
                'available' => true,
                'location' => 'Dhaka',
                'description' => 'Clear sky',
                'temperature' => 30.4,
                'feels_like' => 33.1,
                'humidity' => 70,
                'wind_speed' => 3.5,
                'safety_message' => 'Weather looks acceptable for outdoor facility use.',
            ]);

        Http::assertSent(fn ($request) => str_starts_with($request->url(), 'https://api.openweathermap.org/data/2.5/weather')
            && $request['lat'] === 23.7465
            && $request['lon'] === 90.376
            && $request['appid'] === 'test-openweather-key');
    }

    public function test_weather_endpoint_reports_rejected_api_key(): void
    {
        config(['services.openweather.api_key' => 'bad-openweather-key']);

        Http::fake([
            'api.openweathermap.org/*' => Http::response([
                'cod' => 401,
                'message' => 'Invalid API key.',
            ], 401),
        ]);

        $this->actingAs($this->approvedResident())
            ->getJson(route('resident.bookings.weather.current'))
            ->assertOk()
            ->assertJson([
                'available' => false,
                'message' => 'OpenWeather rejected the API key. Check OPENWEATHER_API_KEY in .env.',
            ]);
    }

    private function approvedResident(): User
    {
        return User::create([
            'name' => 'Resident User',
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+880 1700 000003',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}
