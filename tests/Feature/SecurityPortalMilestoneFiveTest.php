<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Flat;
use App\Models\ResidentProfile;
use App\Models\User;
use App\Models\VisitorRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityPortalMilestoneFiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_guard_can_manage_visitors_emergencies_and_incidents(): void
    {
        $guard = $this->user('guard@example.com', 'security');
        $resident = $this->user('resident@example.com', 'resident');

        $building = Building::create([
            'name' => 'Security Test Tower',
            'code' => 'SEC-T',
            'floors' => 5,
            'total_flats' => 10,
        ]);

        $flat = Flat::create([
            'building_id' => $building->id,
            'flat_number' => '1A',
            'floor' => 1,
            'status' => 'occupied',
        ]);

        ResidentProfile::create([
            'user_id' => $resident->id,
            'flat_id' => $flat->id,
            'resident_type' => 'owner',
            'status' => 'active',
        ]);

        $visitor = VisitorRequest::create([
            'resident_id' => $resident->id,
            'flat_id' => $flat->id,
            'visitor_name' => 'Guest Person',
            'visitor_phone' => '+880 1811 111222',
            'purpose' => 'Family visit',
            'visit_date' => today(),
            'expected_entry_time' => '12:00',
            'access_code' => 'PASS123',
            'status' => 'approved',
        ]);

        $this->actingAs($guard);

        $this->get(route('security.dashboard'))->assertOk();
        $this->get(route('security.checkin', ['passcode' => 'PASS123']))->assertOk();

        $this->post(route('security.checkin.store'), [
            'passcode' => 'PASS123',
        ])->assertRedirect(route('security.checkin', ['passcode' => 'PASS123'], false));

        $this->assertDatabaseHas('visitor_requests', [
            'id' => $visitor->id,
            'status' => 'checked_in',
        ]);
        $this->assertDatabaseHas('visitor_logs', [
            'visitor_request_id' => $visitor->id,
            'event_type' => 'check_in',
            'security_user_id' => $guard->id,
        ]);

        $this->post(route('security.checkout.store'), [
            'passcode' => 'PASS123',
        ])->assertRedirect(route('security.checkout', ['passcode' => 'PASS123'], false));

        $this->assertDatabaseHas('visitor_requests', [
            'id' => $visitor->id,
            'status' => 'checked_out',
        ]);
        $this->assertDatabaseHas('visitor_logs', [
            'visitor_request_id' => $visitor->id,
            'event_type' => 'check_out',
        ]);

        $this->post(route('security.checkin.store'), [
            'name' => 'Manual Guest',
            'phone' => '+880 1811 333444',
            'category' => 'delivery',
            'flat_id' => $flat->id,
            'purpose' => 'Package delivery',
            'vehicle_plate' => 'DHAKA-TEST-99',
        ])->assertRedirect();

        $this->assertDatabaseHas('visitor_requests', [
            'visitor_name' => 'Manual Guest',
            'status' => 'checked_in',
        ]);

        $this->post(route('security.emergency.store'), [
            'type' => 'security',
            'message' => 'Gate emergency button pressed.',
        ])->assertRedirect(route('security.emergency', absolute: false));

        $this->assertDatabaseHas('emergency_requests', [
            'resident_id' => $resident->id,
            'type' => 'security',
            'status' => 'open',
        ]);
        $this->assertDatabaseHas('notifications', [
            'type' => 'security_emergency',
            'title' => 'Security emergency triggered',
        ]);

        $this->post(route('security.incidents.store'), [
            'subject' => 'Unauthorized parking',
            'category' => 'parking',
            'date' => today()->toDateString(),
            'time' => now()->format('H:i'),
            'flat_id' => $flat->id,
            'description' => 'Visitor parked in restricted bay.',
        ])->assertRedirect(route('security.incidents', absolute: false));

        $this->assertDatabaseHas('security_incidents', [
            'reported_by' => $guard->id,
            'subject' => 'Unauthorized parking',
            'status' => 'open',
        ]);
    }

    private function user(string $email, string $role): User
    {
        return User::create([
            'name' => ucfirst($role).' User',
            'email' => $email,
            'phone' => '+880 1700 000001',
            'password' => 'password',
            'role' => $role,
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);
    }
}
