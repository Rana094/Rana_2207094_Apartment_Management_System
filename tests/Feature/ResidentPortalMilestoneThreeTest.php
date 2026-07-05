<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Building;
use App\Models\Facility;
use App\Models\Flat;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\ResidentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResidentPortalMilestoneThreeTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_can_use_core_portal_actions(): void
    {
        Storage::fake('local');

        [$resident, $flat] = $this->residentWithFlat();
        $bill = Bill::create([
            'resident_id' => $resident->id,
            'flat_id' => $flat->id,
            'bill_number' => 'BILL-T-001',
            'billing_month' => now()->startOfMonth()->toDateString(),
            'type' => 'monthly_service_charge',
            'amount' => 4500,
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'unpaid',
        ]);

        $facility = Facility::create([
            'name' => 'Community Hall',
            'description' => 'Test hall',
            'capacity' => 50,
            'booking_fee' => 2500,
        ]);

        $poll = Poll::create([
            'title' => 'Solar panel vote',
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addWeek(),
        ]);
        $option = PollOption::create(['poll_id' => $poll->id, 'label' => 'Yes']);

        $this->actingAs($resident);

        $this->get('/resident/dashboard')->assertOk();
        $this->get('/resident/flat')->assertOk();
        $this->get('/resident/bills')->assertOk();

        $this->post(route('resident.bills.payment-proofs.store', $bill), [
            'amount' => 4500,
            'transaction_id' => 'TXN-123',
            'receipt_file' => UploadedFile::fake()->create('receipt.jpg', 100, 'image/jpeg'),
        ])->assertRedirect(route('resident.bills.show', $bill, absolute: false));

        $this->assertDatabaseHas('payment_proofs', [
            'bill_id' => $bill->id,
            'user_id' => $resident->id,
            'transaction_reference' => 'TXN-123',
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('bills', [
            'id' => $bill->id,
            'status' => 'pending_verification',
        ]);

        $this->post(route('resident.complaints.store'), [
            'title' => 'Bathroom leakage',
            'category' => 'plumbing',
            'urgency' => 'high',
            'location' => 'my_flat',
            'description' => 'Water is leaking under the basin.',
        ])->assertRedirect();
        $this->assertDatabaseHas('complaints', [
            'resident_id' => $resident->id,
            'title' => 'Bathroom leakage',
            'priority' => 'high',
            'status' => 'open',
        ]);

        $this->post(route('resident.visitors.store'), [
            'name' => 'Guest Person',
            'phone' => '+880 1811 111222',
            'date' => now()->addDay()->toDateString(),
            'time' => '12:00',
            'purpose' => 'Family visit',
        ])->assertRedirect(route('resident.visitors.index', absolute: false));
        $this->assertDatabaseHas('visitor_requests', [
            'resident_id' => $resident->id,
            'visitor_name' => 'Guest Person',
            'status' => 'pending',
        ]);

        $this->post(route('resident.bookings.store'), [
            'facility_id' => $facility->id,
            'booking_date' => now()->addDays(2)->toDateString(),
            'start_time' => '16:00',
            'end_time' => '21:00',
            'purpose' => 'Birthday party',
        ])->assertRedirect(route('resident.bookings.index', absolute: false));
        $this->assertDatabaseHas('facility_bookings', [
            'resident_id' => $resident->id,
            'facility_id' => $facility->id,
            'status' => 'pending',
        ]);

        $this->post(route('resident.polls.vote', $poll), [
            'poll_option_id' => $option->id,
        ])->assertRedirect(route('resident.polls', absolute: false));
        $this->assertDatabaseHas('poll_votes', [
            'poll_id' => $poll->id,
            'poll_option_id' => $option->id,
            'user_id' => $resident->id,
        ]);

        $this->post(route('resident.emergency.store'), [
            'type' => 'leak',
            'message' => 'Severe water leak.',
        ])->assertRedirect(route('resident.emergency', absolute: false));
        $this->assertDatabaseHas('emergency_requests', [
            'resident_id' => $resident->id,
            'type' => 'maintenance',
            'status' => 'open',
        ]);

        $this->post(route('resident.documents.store'), [
            'title' => 'Lease copy',
            'category' => 'contract',
            'document_file' => UploadedFile::fake()->create('lease.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('resident.documents', absolute: false));
        $this->assertDatabaseHas('documents', [
            'user_id' => $resident->id,
            'title' => 'Lease copy',
            'type' => 'lease_agreement',
            'status' => 'pending_verification',
        ]);

        $this->post(route('resident.move-out.store'), [
            'move_out_date' => now()->addMonth()->toDateString(),
            'reason' => 'Relocating',
            'forwarding_address' => 'New address',
        ])->assertRedirect(route('resident.move-out', absolute: false));
        $this->assertDatabaseHas('move_out_requests', [
            'resident_id' => $resident->id,
            'status' => 'pending',
        ]);
    }

    public function test_resident_cannot_access_another_residents_bill(): void
    {
        [$resident] = $this->residentWithFlat('resident-one@example.com');
        [$otherResident, $otherFlat] = $this->residentWithFlat('resident-two@example.com');

        $bill = Bill::create([
            'resident_id' => $otherResident->id,
            'flat_id' => $otherFlat->id,
            'bill_number' => 'BILL-T-002',
            'billing_month' => now()->startOfMonth()->toDateString(),
            'type' => 'monthly_service_charge',
            'amount' => 3000,
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'unpaid',
        ]);

        $this->actingAs($resident)
            ->get(route('resident.bills.show', $bill))
            ->assertForbidden();
    }

    private function residentWithFlat(string $email = 'resident@example.com'): array
    {
        $resident = User::create([
            'name' => 'Resident User',
            'email' => $email,
            'phone' => '+880 1700 000001',
            'password' => 'password',
            'role' => 'resident',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $building = Building::firstOrCreate(
            ['code' => 'TEST'],
            ['name' => 'Test Building', 'floors' => 5, 'total_flats' => 10]
        );

        $flat = Flat::create([
            'building_id' => $building->id,
            'flat_number' => 'F'.str_replace(['resident-', '@example.com', 'resident'], '', $email),
            'floor' => 1,
            'status' => 'occupied',
        ]);

        ResidentProfile::create([
            'user_id' => $resident->id,
            'flat_id' => $flat->id,
            'resident_type' => 'owner',
            'status' => 'active',
        ]);

        return [$resident, $flat];
    }
}
