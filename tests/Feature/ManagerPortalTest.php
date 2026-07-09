<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManagerPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an approved manager user
        $this->manager = User::create([
            'name' => 'Approved Manager',
            'email' => 'manager@example.com',
            'phone' => '+880 1700 000001',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Test all building manager portal pages render successfully when authenticated.
     */
    public function test_manager_portal_pages_render_successfully(): void
    {
        $this->actingAs($this->manager);

        // 1. Dashboard
        $response = $this->get('/manager/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Building Management Command Center'); // Check page title
        $response->assertSee('Occupancy Rate'); // Check occupancy rate widget label text
        $response->assertSee('142 Members');

        // 2. Resident Approvals
        $response = $this->get('/manager/resident-approvals');
        $response->assertStatus(200);
        $response->assertSee('Pending Resident Approvals');
        $response->assertSee('No resident approvals are pending.');

        // 3. Resident Directory
        $response = $this->get('/manager/residents');
        $response->assertStatus(200);
        $response->assertSee('Registered Residents');
        $response->assertSee('John Doe');

        // 4. Resident Show Profile
        $response = $this->get('/manager/residents/1');
        $response->assertStatus(200);
        $response->assertSee('Resident Profile: John Doe');

        // 5. Flat list
        $response = $this->get('/manager/flats');
        $response->assertStatus(200);
        $response->assertSee('Apartment Unit Registry');
        $response->assertSee('Flat 3B');

        // 6. Add flat form
        $response = $this->get('/manager/flats/create');
        $response->assertStatus(200);
        $response->assertSee('Register New Flat Unit');

        // 7. Edit flat form
        $response = $this->get('/manager/flats/1/edit');
        $response->assertStatus(200);
        $response->assertSee('Modify Flat Details');

        // 8. Generate bill form
        $response = $this->get('/manager/bills/generate');
        $response->assertStatus(200);
        $response->assertSee('Generate Dues');

        // 9. Bills list
        $response = $this->get('/manager/bills');
        $response->assertStatus(200);
        $response->assertSee('Billing Ledger');

        // 10. Payment queue
        $response = $this->get('/manager/payments');
        $response->assertStatus(200);
        $response->assertSee('Payment Verification Queue');

        // 11. Payment details
        $response = $this->get('/manager/payments/1');
        $response->assertStatus(200);
        $response->assertSee('Verify Transaction Ledger');

        // 12. Financial Report
        $response = $this->get('/manager/reports');
        $response->assertStatus(200);
        $response->assertSee('Financial Performance Reports');

        // 13. Complaint registry list
        $response = $this->get('/manager/complaints');
        $response->assertStatus(200);
        $response->assertSee('Maintenance Complaints Registry');

        // 14. Work order assignment
        $response = $this->get('/manager/complaints/2033/assign');
        $response->assertStatus(200);
        $response->assertSee('Assign Repair Technician');

        // 15. Staff Roster list
        $response = $this->get('/manager/staff');
        $response->assertStatus(200);
        $response->assertSee('Staff Roster');

        // 16. Facility Bookings queue list
        $response = $this->get('/manager/bookings');
        $response->assertStatus(200);
        $response->assertSee('Amenity Reservations Queue');

        // 17. Polls Creator
        $response = $this->get('/manager/polls');
        $response->assertStatus(200);
        $response->assertSee('Manage Society Referendums');

        // 18. Emergency Alerts Log
        $response = $this->get('/manager/emergencies');
        $response->assertStatus(200);
        $response->assertSee('Emergency dispatch panel');

        // 19. Broadcast Announcements Notice Board
        $response = $this->get('/manager/notices');
        $response->assertStatus(200);
        $response->assertSee('Broadcast Society Announcements');
    }
}
