<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Flat;
use App\Models\ResidentProfile;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResidentComplaintWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_complaint_is_visible_and_moves_through_full_workflow(): void
    {
        $manager = $this->approvedUser('manager-flow@example.com', 'manager');
        $resident = $this->approvedUser('resident-flow@example.com', 'resident');
        $staff = $this->approvedUser('staff-flow@example.com', 'staff');

        StaffProfile::create([
            'user_id' => $staff->id,
            'staff_type' => 'Plumbing',
            'employee_code' => 'STF-100',
            'status' => 'active',
        ]);

        $building = Building::create([
            'name' => 'Building B',
            'code' => 'B',
            'floors' => 5,
            'total_flats' => 10,
        ]);

        $flat = Flat::create([
            'building_id' => $building->id,
            'flat_number' => '1C',
            'floor' => 1,
            'type' => 'Family',
            'status' => 'occupied',
        ]);

        ResidentProfile::create([
            'user_id' => $resident->id,
            'flat_id' => $flat->id,
            'resident_type' => 'owner',
            'status' => 'active',
        ]);

        $this->actingAs($resident)
            ->post(route('resident.complaints.store'), [
                'title' => 'Balcony drain blockage',
                'category' => 'plumbing',
                'urgency' => 'high',
                'location' => 'my_flat',
                'description' => 'Rain water is not draining from the balcony.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('complaints', [
            'resident_id' => $resident->id,
            'flat_id' => $flat->id,
            'title' => 'Balcony drain blockage',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $complaint = $resident->complaints()->firstOrFail();

        $this->actingAs($resident)
            ->get(route('resident.complaints.index'))
            ->assertOk()
            ->assertSee('Balcony drain blockage')
            ->assertSee('View Status')
            ->assertDontSee('Bathroom pipe leakage in master washroom')
            ->assertDontSee('#T-2033');

        $this->actingAs($resident)
            ->get(route('resident.complaints.show', $complaint))
            ->assertOk()
            ->assertSee('Complaint Ticket #T-'.$complaint->id)
            ->assertSee('Balcony drain blockage')
            ->assertSee('No technician assigned yet.');

        $this->actingAs($resident)
            ->post(route('resident.complaints.messages.store', $complaint), [
                'message' => 'Please send someone before the next rain.',
            ])
            ->assertRedirect(route('resident.complaints.show', $complaint));

        $this->assertDatabaseHas('complaint_messages', [
            'complaint_id' => $complaint->id,
            'user_id' => $resident->id,
            'message' => 'Please send someone before the next rain.',
        ]);

        $this->actingAs($manager)
            ->get(route('manager.complaints.index'))
            ->assertOk()
            ->assertSee('Balcony drain blockage')
            ->assertSee('Assign Staff');

        $this->actingAs($manager)
            ->post(route('manager.complaints.work-orders.store', $complaint), [
                'technician_id' => $staff->id,
                'urgency' => 'high',
                'deadline' => now()->addDay()->toDateString(),
                'instructions' => 'Clear the drain cover and test water flow.',
            ])
            ->assertRedirect(route('manager.complaints.index'));

        $this->assertDatabaseHas('work_orders', [
            'complaint_id' => $complaint->id,
            'assigned_to' => $staff->id,
            'assigned_by' => $manager->id,
            'status' => 'todo',
        ]);

        $workOrder = WorkOrder::where('complaint_id', $complaint->id)->firstOrFail();

        $this->actingAs($staff)
            ->get(route('maintenance.dashboard'))
            ->assertOk()
            ->assertSee('Work order for Balcony drain blockage');

        $this->actingAs($staff)
            ->get(route('maintenance.show', $workOrder))
            ->assertOk()
            ->assertSee('Resident Complaint Messages')
            ->assertSee('Please send someone before the next rain.');

        $this->actingAs($staff)
            ->post(route('maintenance.orders.update', $workOrder), [
                'status' => 'completed',
                'remarks' => 'Drain cleaned and water flow tested.',
            ])
            ->assertRedirect(route('maintenance.show', $workOrder));

        $this->actingAs($resident)
            ->get(route('resident.complaints.show', $complaint->fresh()))
            ->assertOk()
            ->assertSee('resolved')
            ->assertSee('Staff User')
            ->assertSee('Plumbing')
            ->assertSee('Repair Updates')
            ->assertSee('Drain cleaned and water flow tested.');
    }

    public function test_fake_resident_complaint_ids_no_longer_render_demo_pages(): void
    {
        $resident = $this->approvedUser('resident-no-demo@example.com', 'resident');

        $this->actingAs($resident);

        $this->get('/resident/complaints')->assertOk()->assertDontSee('#T-2033');
        $this->get('/resident/complaints/2033')->assertNotFound();
    }

    private function approvedUser(string $email, string $role): User
    {
        return User::create([
            'name' => ucfirst($role).' User',
            'email' => $email,
            'phone' => '+880 1700 000003',
            'password' => Hash::make('password'),
            'role' => $role,
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}
