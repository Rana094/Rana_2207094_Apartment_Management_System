<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Flat;
use App\Models\ResidentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResidentApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_approval_assigns_requested_flat_and_marks_it_occupied(): void
    {
        $manager = $this->user('manager', 'manager@example.com', 'approved');
        $resident = $this->user('resident', 'resident@example.com', 'pending_approval');
        $flat = $this->flat();

        $resident->update([
            'resident_type' => 'tenant',
            'requested_flat_id' => $flat->id,
            'flat_info' => 'Nestora Heights - Building B, Flat B-301',
        ]);

        $this->actingAs($manager)
            ->post(route('manager.resident-approvals.approve', $resident))
            ->assertRedirect();

        $resident->refresh();
        $flat->refresh();

        $this->assertSame('approved', $resident->status);
        $this->assertNotNull($resident->approved_at);
        $this->assertSame('occupied', $flat->status);

        $this->assertDatabaseHas('resident_profiles', [
            'user_id' => $resident->id,
            'flat_id' => $flat->id,
            'resident_type' => 'tenant',
            'status' => 'active',
        ]);
    }

    public function test_manager_cannot_approve_resident_without_requested_flat(): void
    {
        $manager = $this->user('manager', 'manager@example.com', 'approved');
        $resident = $this->user('resident', 'resident@example.com', 'pending_approval');

        $this->actingAs($manager)
            ->post(route('manager.resident-approvals.approve', $resident))
            ->assertStatus(422);

        $this->assertSame(0, ResidentProfile::count());
    }

    public function test_pending_requested_flat_is_shown_as_pending_not_available(): void
    {
        $manager = $this->user('manager', 'manager@example.com', 'approved');
        $resident = $this->user('resident', 'resident@example.com', 'pending_approval');
        $flat = $this->flat();

        $resident->update([
            'requested_flat_id' => $flat->id,
            'resident_type' => 'tenant',
        ]);

        $this->actingAs($manager)
            ->get(route('manager.dashboard'))
            ->assertOk()
            ->assertSee('0 available for signup')
            ->assertSee('1 pending approval');

        $this->actingAs($manager)
            ->get(route('manager.flats.index'))
            ->assertOk()
            ->assertSee('pending approval')
            ->assertSee($resident->name);
    }

    private function user(string $role, string $email, string $status): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $email,
            'phone' => '+880 1700 000000',
            'password' => Hash::make('password'),
            'role' => $role,
            'status' => $status,
            'email_verified_at' => null,
            'approved_at' => $status === 'approved' ? now() : null,
        ]);
    }

    private function flat(): Flat
    {
        $building = Building::create([
            'name' => 'Nestora Heights - Building B',
            'code' => 'NEST-B',
            'address' => '14/A, Dhanmondi, Dhaka',
            'floors' => 10,
            'total_flats' => 20,
        ]);

        return Flat::create([
            'building_id' => $building->id,
            'flat_number' => 'B-301',
            'floor' => 3,
            'block' => 'B',
            'type' => 'family',
            'bedrooms' => 3,
            'area_sqft' => 1250,
            'status' => 'vacant',
        ]);
    }
}
