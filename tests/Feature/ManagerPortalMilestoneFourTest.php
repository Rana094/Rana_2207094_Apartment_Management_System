<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Building;
use App\Models\Complaint;
use App\Models\EmergencyRequest;
use App\Models\Facility;
use App\Models\FacilityBooking;
use App\Models\Flat;
use App\Models\PaymentProof;
use App\Models\ResidentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerPortalMilestoneFourTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_perform_core_admin_actions(): void
    {
        $manager = $this->user('manager@example.com', 'manager');
        $resident = $this->user('resident@example.com', 'resident');
        $staff = $this->user('staff@example.com', 'staff');

        $building = Building::create([
            'name' => 'Nestora Test Tower',
            'code' => 'NST-T',
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

        $bill = Bill::create([
            'resident_id' => $resident->id,
            'flat_id' => $flat->id,
            'bill_number' => 'BILL-M4-001',
            'billing_month' => now()->startOfMonth()->toDateString(),
            'type' => 'monthly_service_charge',
            'amount' => 4500,
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'pending_verification',
        ]);

        $proof = PaymentProof::create([
            'bill_id' => $bill->id,
            'user_id' => $resident->id,
            'amount' => 4500,
            'transaction_reference' => 'TXN-M4',
            'file_path' => 'payment-proofs/demo.pdf',
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        $complaint = Complaint::create([
            'resident_id' => $resident->id,
            'flat_id' => $flat->id,
            'title' => 'Pipe leak',
            'description' => 'Kitchen pipe leak.',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $facility = Facility::create([
            'name' => 'Community Hall',
            'capacity' => 80,
            'booking_fee' => 2500,
        ]);

        $booking = FacilityBooking::create([
            'resident_id' => $resident->id,
            'facility_id' => $facility->id,
            'booking_date' => now()->addDays(5)->toDateString(),
            'start_time' => '16:00',
            'end_time' => '21:00',
            'status' => 'pending',
        ]);

        $emergency = EmergencyRequest::create([
            'resident_id' => $resident->id,
            'flat_id' => $flat->id,
            'type' => 'security',
            'message' => 'Security concern.',
            'status' => 'open',
        ]);

        $this->actingAs($manager);

        $this->post(route('manager.flats.store'), [
            'number' => '2B',
            'block' => 'A',
            'floor' => 2,
            'size' => 1200,
            'beds' => 2,
            'occupancy' => 'vacant',
        ])->assertRedirect(route('manager.flats.index', absolute: false));
        $this->assertDatabaseHas('flats', ['flat_number' => '2B', 'status' => 'vacant']);

        $this->post(route('manager.bills.store'), [
            'bulk_billing' => '1',
            'category' => 'monthly_service_charge',
            'period' => now()->addMonth()->format('Y-m'),
            'due_date' => now()->addMonth()->startOfMonth()->addDays(9)->toDateString(),
            'amount' => 5000,
        ])->assertRedirect(route('manager.bills.index', absolute: false));
        $this->assertDatabaseHas('bills', [
            'resident_id' => $resident->id,
            'amount' => 5000,
            'status' => 'unpaid',
        ]);

        $this->post(route('manager.payments.verify', $proof), [
            'status' => 'approved',
        ])->assertRedirect(route('manager.payments.index', absolute: false));
        $this->assertDatabaseHas('payment_proofs', ['id' => $proof->id, 'status' => 'approved']);
        $this->assertDatabaseHas('bills', ['id' => $bill->id, 'status' => 'paid']);

        $this->post(route('manager.complaints.work-orders.store', $complaint), [
            'technician_id' => $staff->id,
            'urgency' => 'high',
            'deadline' => now()->addDay()->toDateString(),
            'instructions' => 'Fix the pipe leak.',
        ])->assertRedirect(route('manager.complaints.index', absolute: false));
        $this->assertDatabaseHas('work_orders', [
            'complaint_id' => $complaint->id,
            'assigned_to' => $staff->id,
            'status' => 'todo',
        ]);

        $this->post(route('manager.staff.store'), [
            'name' => 'Security Two',
            'email' => 'security-two@example.com',
            'phone' => '+880 1700 000004',
            'role' => 'security',
            'staff_type' => 'security',
            'employee_code' => 'SEC-M4',
            'password' => 'password123',
        ])->assertRedirect(route('manager.staff', absolute: false));
        $this->assertDatabaseHas('staff_profiles', ['employee_code' => 'SEC-M4']);

        $this->post(route('manager.bookings.status', $booking), [
            'status' => 'approved',
        ])->assertRedirect(route('manager.bookings.index', absolute: false));
        $this->assertDatabaseHas('facility_bookings', ['id' => $booking->id, 'status' => 'approved']);

        $this->post(route('manager.polls.store'), [
            'question' => 'Approve roof garden?',
            'closes_at' => now()->addDays(7)->toDateString(),
            'options' => ['Yes', 'No'],
        ])->assertRedirect(route('manager.polls', absolute: false));
        $this->assertDatabaseHas('polls', ['title' => 'Approve roof garden?']);
        $this->assertDatabaseHas('poll_options', ['label' => 'Yes']);

        $this->post(route('manager.emergencies.status', $emergency), [
            'status' => 'resolved',
        ])->assertRedirect(route('manager.emergencies.index', absolute: false));
        $this->assertDatabaseHas('emergency_requests', ['id' => $emergency->id, 'status' => 'resolved']);

        $this->post(route('manager.notices.store'), [
            'title' => 'Water shutdown',
            'category' => 'all',
            'content' => 'Water supply will pause for maintenance.',
        ])->assertRedirect(route('manager.notices.index', absolute: false));
        $this->assertDatabaseHas('notices', ['title' => 'Water shutdown']);
        $this->assertDatabaseHas('notifications', ['type' => 'notice', 'title' => 'Water shutdown']);
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
