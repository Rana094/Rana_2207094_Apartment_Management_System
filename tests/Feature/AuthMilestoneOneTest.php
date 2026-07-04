<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthMilestoneOneTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_manager_logs_into_manager_dashboard(): void
    {
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'phone' => '+880 1700 000001',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'manager@example.com',
            'password' => 'password',
            'role' => 'manager',
        ]);

        $response->assertRedirect(route('manager.dashboard', absolute: false));
        $this->assertAuthenticated();
    }

    public function test_pending_resident_is_sent_to_approval_page_after_login(): void
    {
        User::create([
            'name' => 'Pending Resident',
            'email' => 'pending@example.com',
            'phone' => '+880 1700 000002',
            'password' => Hash::make('password'),
            'role' => 'resident',
            'status' => 'pending_approval',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'pending@example.com',
            'password' => 'password',
            'role' => 'resident',
        ]);

        $response->assertRedirect(route('approval.pending', absolute: false));
        $this->assertAuthenticated();
    }

    public function test_resident_registration_creates_pending_verification_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Resident',
            'email' => 'new@example.com',
            'phone' => '+880 1700 000003',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'resident_type' => 'tenant',
            'flat_info' => 'Building B, Flat 4D',
        ]);

        $response->assertRedirect(route('approval.pending', absolute: false));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'role' => 'resident',
            'status' => 'pending_verification',
            'resident_type' => 'tenant',
            'flat_info' => 'Building B, Flat 4D',
        ]);
    }
}
