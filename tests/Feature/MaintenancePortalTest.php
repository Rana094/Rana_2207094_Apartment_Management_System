<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MaintenancePortalTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an approved staff user
        $this->staff = User::create([
            'name' => 'Approved Staff',
            'email' => 'staff@example.com',
            'phone' => '+880 1700 000003',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Test all maintenance portal pages render successfully when authenticated.
     */
    public function test_maintenance_portal_pages_render_successfully(): void
    {
        $this->actingAs($this->staff);

        // 1. Dashboard / Work Workspace
        $response = $this->get('/maintenance');
        $response->assertStatus(200);
        $response->assertSee('Maintenance Work Workspace');

        $response = $this->get('/maintenance/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Maintenance Work Workspace');

        // 2. Assigned Work Orders list
        $response = $this->get('/maintenance/work-orders');
        $response->assertStatus(200);
        $response->assertSee('Assigned Work Orders');

        // 3. In Progress Work Orders
        $response = $this->get('/maintenance/work-orders/in-progress');
        $response->assertStatus(200);
        $response->assertSee('Assigned Work Orders');

        // 4. Completed Work Orders
        $response = $this->get('/maintenance/work-orders/completed');
        $response->assertStatus(200);
        $response->assertSee('Repair History Archive');

        // 5. Repair Notes
        $response = $this->get('/maintenance/notes');
        $response->assertStatus(200);
        $response->assertSee('Repair History Archive');

        // 6. Profile
        $response = $this->get('/maintenance/profile');
        $response->assertStatus(200);
        $response->assertSee('Profile Settings');

        // 7. Work Order Details
        $response = $this->get('/maintenance/orders/2033');
        $response->assertStatus(200);
        $response->assertSee('Work Order #T-2033');
        $response->assertSee('Bathroom pipe leakage in master washroom');

        // 8. Update Status form
        $response = $this->get('/maintenance/orders/2033/update');
        $response->assertStatus(200);
        $response->assertSee('Update Work Order Status');
        $response->assertSee('Progress Update Report');

        // 9. Repair History (direct path)
        $response = $this->get('/maintenance/history');
        $response->assertStatus(200);
        $response->assertSee('Repair History Archive');
        $response->assertSee('#T-1804');
    }
}
