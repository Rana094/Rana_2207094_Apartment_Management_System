<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Flat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResidentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_confirmation_must_match(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'name' => 'New Resident',
            'email' => 'new-resident@example.com',
            'phone' => '+880 1711 223344',
            'password' => 'password123',
            'password_confirmation' => 'different123',
            'resident_type' => 'tenant',
            'flat_id' => $this->availableFlat()->id,
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors([
                'password' => 'Password and confirm password do not match.',
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'new-resident@example.com',
        ]);
    }

    public function test_successful_signup_waits_for_manager_approval_without_email_verification(): void
    {
        $flat = $this->availableFlat();

        $response = $this->post(route('register.store'), [
            'name' => 'New Resident',
            'email' => 'new-resident@example.com',
            'phone' => '+880 1711 223344',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'resident_type' => 'tenant',
            'flat_id' => $flat->id,
        ]);

        $response
            ->assertRedirect(route('approval.pending'))
            ->assertSessionHas('status', 'Registration submitted. Please wait for manager approval.');

        $this->assertAuthenticated();

        $user = User::where('email', 'new-resident@example.com')->firstOrFail();

        $this->assertSame('pending_approval', $user->status);
        $this->assertNull($user->email_verified_at);
        $this->assertSame($flat->id, $user->requested_flat_id);
    }

    public function test_successful_signup_can_store_verification_document(): void
    {
        Storage::fake('private_uploads');
        $flat = $this->availableFlat('1A');

        $response = $this->post(route('register.store'), [
            'name' => 'Document Resident',
            'email' => 'document-resident@example.com',
            'phone' => '+880 1711 223344',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'resident_type' => 'owner',
            'flat_id' => $flat->id,
            'nid_document' => UploadedFile::fake()->create('nid.pdf', 10, 'application/pdf'),
        ]);

        $response->assertRedirect(route('approval.pending'));

        $user = User::where('email', 'document-resident@example.com')->firstOrFail();

        $this->assertNotNull($user->document_path);
        Storage::disk('private_uploads')->assertExists($user->document_path);
    }

    public function test_signup_rejects_unavailable_flat(): void
    {
        $flat = $this->availableFlat();
        $flat->update(['status' => 'occupied']);

        $response = $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Unavailable Resident',
            'email' => 'unavailable-resident@example.com',
            'phone' => '+880 1711 223344',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'resident_type' => 'tenant',
            'flat_id' => $flat->id,
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('flat_id');

        $this->assertDatabaseMissing('users', [
            'email' => 'unavailable-resident@example.com',
        ]);
    }

    public function test_signup_rejects_flat_already_requested_by_pending_resident(): void
    {
        $flat = $this->availableFlat();

        User::create([
            'name' => 'Pending Resident',
            'email' => 'pending-resident@example.com',
            'phone' => '+880 1700 000000',
            'password' => 'password',
            'role' => 'resident',
            'status' => 'pending_approval',
            'resident_type' => 'tenant',
            'requested_flat_id' => $flat->id,
        ]);

        $response = $this->from(route('register'))->post(route('register.store'), [
            'name' => 'Second Resident',
            'email' => 'second-resident@example.com',
            'phone' => '+880 1711 223344',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'resident_type' => 'tenant',
            'flat_id' => $flat->id,
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('flat_id');

        $this->assertDatabaseMissing('users', [
            'email' => 'second-resident@example.com',
        ]);
    }

    private function availableFlat(string $number = '3C'): Flat
    {
        $building = Building::create([
            'name' => 'Nestora Heights - Building A',
            'code' => 'NEST-A-'.strtolower($number),
            'address' => '12/A, Dhanmondi, Dhaka',
            'floors' => 8,
            'total_flats' => 16,
        ]);

        return Flat::create([
            'building_id' => $building->id,
            'flat_number' => $number,
            'floor' => 3,
            'block' => 'A',
            'type' => 'family',
            'bedrooms' => 3,
            'area_sqft' => 1200,
            'status' => 'vacant',
        ]);
    }
}
