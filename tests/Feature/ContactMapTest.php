<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContactMapTest extends TestCase
{
    public function test_contact_map_reports_missing_geoapify_key(): void
    {
        config(['services.geoapify.api_key' => null]);

        $this->get(route('contact.map'))
            ->assertStatus(503);
    }

    public function test_contact_map_returns_static_map_image(): void
    {
        config([
            'services.geoapify.api_key' => 'test-geoapify-key',
            'services.geoapify.apartment_lat' => 23.7465,
            'services.geoapify.apartment_lon' => 90.3760,
        ]);

        Http::fake([
            'maps.geoapify.com/*' => Http::response('fake-image-bytes', 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        $this->get(route('contact.map'))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg')
            ->assertSee('fake-image-bytes');

        Http::assertSent(fn ($request) => str_starts_with($request->url(), 'https://maps.geoapify.com/v1/staticmap')
            && $request['center'] === 'lonlat:90.376,23.7465'
            && $request['marker'] === 'lonlat:90.376,23.7465;color:#0f766e;type:awesome;icon:building'
            && $request['apiKey'] === 'test-geoapify-key');
    }
}
