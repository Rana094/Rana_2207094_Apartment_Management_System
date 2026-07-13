<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $securityGuard;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an approved security guard user
        $this->securityGuard = User::create([
            'name' => 'Approved Guard',
            'email' => 'guard@example.com',
            'phone' => '+880 1700 000002',
            'password' => Hash::make('password'),
            'role' => 'security',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Test all security portal pages render successfully when authenticated.
     */
    public function test_security_portal_pages_render_successfully(): void
    {
        $this->actingAs($this->securityGuard);

        // 1. Dashboard
        $response = $this->get('/security/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Gate Security Guard Terminal');
        $response->assertSee('Verify Pre-Approved Visitor Passcode');

        // 2. Check-In Registry
        $response = $this->get('/security/checkin');
        $response->assertStatus(200);
        $response->assertSee('Visitor Check-In Registry');
        $response->assertSee('Manual Walk-In Entry');

        // 3. Check-Out Exit Registry
        $response = $this->get('/security/checkout');
        $response->assertStatus(200);
        $response->assertSee('Visitor Check-Out Exit Registry');
        $response->assertSee('Find Checked-In Visitor');

        // 4. Logs Directory
        $response = $this->get('/security/logs');
        $response->assertStatus(200);
        $response->assertSee('Visitor Logs Directory');
        $response->assertSee('Farhan Alvi');

        // 5. Emergency Alarm Dispatch
        $response = $this->get('/security/emergency');
        $response->assertStatus(200);
        $response->assertSee('Gate Emergency Alarm Dispatch');
        $response->assertSee('DISPATCH');

        // 6. Security Incidents Directory
        $response = $this->get('/security/incidents');
        $response->assertStatus(200);
        $response->assertSee('Security Incidents Directory');
        $response->assertSee('File Incident Report');
    }
}
