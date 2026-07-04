<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResidentPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $resident;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an approved resident user
        $this->resident = User::create([
            'name' => 'Approved Resident',
            'email' => 'resident@example.com',
            'phone' => '+880 1700 000004',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Test all resident portal pages render successfully when authenticated.
     */
    public function test_resident_portal_pages_render_successfully(): void
    {
        $this->actingAs($this->resident);

        // 1. Dashboard
        $response = $this->get('/resident/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Welcome Back, John Doe');
        $response->assertSee('Flat 3B');

        // 2. Flat Details
        $response = $this->get('/resident/flat');
        $response->assertStatus(200);
        $response->assertSee('Flat Details');
        $response->assertSee('Toyota Premio');

        // 3. Bills List
        $response = $this->get('/resident/bills');
        $response->assertStatus(200);
        $response->assertSee('Bills & Payments');
        $response->assertSee('#B-9872');

        // 4. Bill Details
        $response = $this->get('/resident/bills/9872');
        $response->assertStatus(200);
        $response->assertSee('Invoice #B-9872');

        // 5. Upload Bill Proof
        $response = $this->get('/resident/bills/9872/upload');
        $response->assertStatus(200);
        $response->assertSee('Submit Payment Proof');

        // 6. Complaints List
        $response = $this->get('/resident/complaints');
        $response->assertStatus(200);
        $response->assertSee('Maintenance Complaints');

        // 7. Complaint Create
        $response = $this->get('/resident/complaints/create');
        $response->assertStatus(200);
        $response->assertSee('File Maintenance Complaint');

        // 8. Complaint Show
        $response = $this->get('/resident/complaints/2033');
        $response->assertStatus(200);
        $response->assertSee('Complaint Ticket #T-2033');

        // 9. Visitors List
        $response = $this->get('/resident/visitors');
        $response->assertStatus(200);
        $response->assertSee('Visitor Passes');

        // 10. Visitor Create
        $response = $this->get('/resident/visitors/create');
        $response->assertStatus(200);
        $response->assertSee('Create pre-approved visitor pass');

        // 11. Facility Bookings List
        $response = $this->get('/resident/bookings');
        $response->assertStatus(200);
        $response->assertSee('Facility Bookings');

        // 12. Booking Create
        $response = $this->get('/resident/bookings/create');
        $response->assertStatus(200);
        $response->assertSee('Book a Shared Facility');

        // 13. Polls
        $response = $this->get('/resident/polls');
        $response->assertStatus(200);
        $response->assertSee('Polls and Democratic Voting');

        // 14. Emergency Request Panel
        $response = $this->get('/resident/emergency');
        $response->assertStatus(200);
        $response->assertSee('Emergency Alarm Dispatch');

        // 15. Documents
        $response = $this->get('/resident/documents');
        $response->assertStatus(200);
        $response->assertSee('Document Directory');

        // 16. Move-Out
        $response = $this->get('/resident/move-out');
        $response->assertStatus(200);
        $response->assertSee('Move-Out Clearance Request');

        // 17. Profile Edit
        $response = $this->get('/resident/profile');
        $response->assertStatus(200);
        $response->assertSee('Profile Settings');
    }
}
