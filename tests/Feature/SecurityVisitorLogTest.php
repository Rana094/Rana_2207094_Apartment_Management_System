<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Flat;
use App\Models\ResidentProfile;
use App\Models\User;
use App\Models\VisitorLog;
use App\Models\VisitorRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityVisitorLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_checked_in_visitor_appears_in_security_logs(): void
    {
        $security = $this->user('security', 'security@example.com');
        $resident = $this->user('resident', 'resident@example.com');
        $flat = $this->flat();

        ResidentProfile::create([
            'user_id' => $resident->id,
            'flat_id' => $flat->id,
            'resident_type' => 'tenant',
            'move_in_date' => today(),
            'status' => 'active',
        ]);

        $visitor = VisitorRequest::create([
            'resident_id' => $resident->id,
            'flat_id' => $flat->id,
            'visitor_name' => 'Test Visitor',
            'visitor_phone' => '+880 1800 000000',
            'purpose' => 'guest: Family visit',
            'visit_date' => today(),
            'expected_entry_time' => now()->format('H:i'),
            'access_code' => 'TEST123',
            'status' => 'approved',
        ]);

        $this->actingAs($security)
            ->post(route('security.checkin.store'), ['passcode' => $visitor->access_code])
            ->assertRedirect(route('security.checkin', ['passcode' => $visitor->access_code]));

        $this->assertSame(1, VisitorLog::count());

        $this->actingAs($security)
            ->get(route('security.logs'))
            ->assertOk()
            ->assertSee('Test Visitor')
            ->assertSee('TEST123')
            ->assertSee('inside')
            ->assertDontSee('320');
    }

    private function user(string $role, string $email): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $email,
            'phone' => '+880 1700 000000',
            'password' => Hash::make('password'),
            'role' => $role,
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    private function flat(): Flat
    {
        $building = Building::create([
            'name' => 'Nestora Heights',
            'code' => 'NEST-T',
            'floors' => 8,
            'total_flats' => 16,
        ]);

        return Flat::create([
            'building_id' => $building->id,
            'flat_number' => '1A',
            'floor' => 1,
            'block' => 'A',
            'type' => 'family',
            'bedrooms' => 3,
            'status' => 'occupied',
        ]);
    }
}
