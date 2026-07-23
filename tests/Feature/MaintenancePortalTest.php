<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Complaint;
use App\Models\Flat;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MaintenancePortalTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;

    private User $manager;

    private User $resident;

    private WorkOrder $workOrder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = $this->approvedUser('manager@example.com', 'manager');
        $this->staff = $this->approvedUser('staff@example.com', 'staff');
        $this->resident = $this->approvedUser('resident@example.com', 'resident');

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

        $complaint = Complaint::create([
            'resident_id' => $this->resident->id,
            'flat_id' => $flat->id,
            'title' => 'Kitchen sink leakage',
            'category' => 'Plumbing',
            'description' => 'Water is leaking below the kitchen sink.',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);

        $this->workOrder = WorkOrder::create([
            'complaint_id' => $complaint->id,
            'assigned_to' => $this->staff->id,
            'assigned_by' => $this->manager->id,
            'title' => 'Kitchen sink leakage repair',
            'instructions' => 'Check the sink trap and replace the seal if needed.',
            'priority' => 'high',
            'status' => 'in_progress',
            'due_at' => now()->addDay(),
        ]);
    }

    public function test_maintenance_portal_pages_and_buttons_use_real_assigned_work_orders(): void
    {
        $this->actingAs($this->staff);

        $this->get('/maintenance')
            ->assertOk()
            ->assertSee('Maintenance Work Workspace')
            ->assertSee('Kitchen sink leakage repair')
            ->assertSee(route('maintenance.show', $this->workOrder), false)
            ->assertSee(route('maintenance.update', $this->workOrder), false);

        $this->get('/maintenance/dashboard')
            ->assertOk()
            ->assertSee('Maintenance Work Workspace')
            ->assertSee('Kitchen sink leakage repair');

        $this->get('/maintenance/work-orders')
            ->assertOk()
            ->assertSee('Assigned Work Orders')
            ->assertSee('Kitchen sink leakage repair');

        $this->get('/maintenance/work-orders/in-progress')
            ->assertOk()
            ->assertSee('Kitchen sink leakage repair');

        $this->get(route('maintenance.show', $this->workOrder))
            ->assertOk()
            ->assertSee('Work Order #T-'.$this->workOrder->id)
            ->assertSee('Kitchen sink leakage repair')
            ->assertSee('Building B')
            ->assertSee('Update Task Status');

        $this->get(route('maintenance.update', $this->workOrder))
            ->assertOk()
            ->assertSee('Update Work Order Status')
            ->assertSee('Progress Update Report')
            ->assertSee('Submit Update');
    }

    public function test_staff_can_submit_work_order_update_and_history_shows_completed_record(): void
    {
        $this->actingAs($this->staff);

        $response = $this->post(route('maintenance.orders.update', $this->workOrder), [
            'status' => 'completed',
            'remarks' => 'Leak fixed and the area was cleaned.',
        ]);

        $response->assertRedirect(route('maintenance.show', $this->workOrder));

        $this->assertDatabaseHas('work_orders', [
            'id' => $this->workOrder->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('complaints', [
            'id' => $this->workOrder->complaint_id,
            'status' => 'resolved',
        ]);

        $this->assertDatabaseHas('work_order_notes', [
            'work_order_id' => $this->workOrder->id,
            'user_id' => $this->staff->id,
            'status' => 'completed',
            'remarks' => 'Leak fixed and the area was cleaned.',
        ]);

        $this->get('/maintenance/history')
            ->assertOk()
            ->assertSee('Repair History Archive')
            ->assertSee('Kitchen sink leakage repair')
            ->assertSee('View Record');
    }

    public function test_fake_demo_work_order_id_no_longer_opens(): void
    {
        $this->actingAs($this->staff);

        $this->get('/maintenance/orders/2033')->assertNotFound();
        $this->get('/maintenance/orders/2033/update')->assertNotFound();
        $this->get('/maintenance/history')->assertDontSee('#T-1804');
    }

    public function test_staff_cannot_open_unassigned_work_order_buttons(): void
    {
        $otherStaff = $this->approvedUser('other-staff@example.com', 'staff');
        $this->workOrder->update(['assigned_to' => $otherStaff->id]);

        $this->actingAs($this->staff);

        $this->get(route('maintenance.show', $this->workOrder))->assertForbidden();
        $this->get(route('maintenance.update', $this->workOrder))->assertForbidden();
        $this->post(route('maintenance.orders.update', $this->workOrder), [
            'status' => 'completed',
            'remarks' => 'Trying to update an unassigned task.',
        ])->assertForbidden();

        $this->assertSame(0, WorkOrderNote::count());
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
