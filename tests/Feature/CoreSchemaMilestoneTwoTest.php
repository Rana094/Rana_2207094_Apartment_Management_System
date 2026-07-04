<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\Flat;
use App\Models\FlatMember;
use App\Models\ResidentProfile;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\VehicleRegistration;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoreSchemaMilestoneTwoTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_apartment_relationships_are_available(): void
    {
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'phone' => '+880 1700 000001',
            'password' => 'password',
            'role' => 'manager',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $resident = User::create([
            'name' => 'Resident',
            'email' => 'resident@example.com',
            'phone' => '+880 1700 000002',
            'password' => 'password',
            'role' => 'resident',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $staff = User::create([
            'name' => 'Technician',
            'email' => 'staff@example.com',
            'phone' => '+880 1700 000003',
            'password' => 'password',
            'role' => 'staff',
            'status' => 'approved',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $building = Building::create([
            'name' => 'Nestora Heights',
            'code' => 'NEST-T',
            'floors' => 5,
            'total_flats' => 10,
        ]);

        $flat = Flat::create([
            'building_id' => $building->id,
            'flat_number' => '1A',
            'floor' => 1,
            'status' => 'occupied',
        ]);

        $profile = ResidentProfile::create([
            'user_id' => $resident->id,
            'flat_id' => $flat->id,
            'resident_type' => 'owner',
            'status' => 'active',
        ]);

        FlatMember::create([
            'resident_profile_id' => $profile->id,
            'name' => 'Family Member',
        ]);

        VehicleRegistration::create([
            'resident_profile_id' => $profile->id,
            'registration_number' => 'DHAKA-TEST-123',
        ]);

        Document::create([
            'user_id' => $resident->id,
            'flat_id' => $flat->id,
            'title' => 'NID',
            'type' => 'national_id',
            'file_path' => 'resident-documents/nid.pdf',
        ]);

        StaffProfile::create([
            'user_id' => $staff->id,
            'staff_type' => 'maintenance',
            'employee_code' => 'MNT-T',
        ]);

        $complaint = Complaint::create([
            'resident_id' => $resident->id,
            'flat_id' => $flat->id,
            'title' => 'Leakage',
            'description' => 'Kitchen sink leakage.',
        ]);

        WorkOrder::create([
            'complaint_id' => $complaint->id,
            'assigned_to' => $staff->id,
            'assigned_by' => $manager->id,
            'title' => 'Fix leakage',
        ]);

        $this->assertTrue($building->flats()->where('flat_number', '1A')->exists());
        $this->assertSame('1A', $resident->residentProfile->flat->flat_number);
        $this->assertCount(1, $resident->residentProfile->flatMembers);
        $this->assertCount(1, $resident->residentProfile->vehicleRegistrations);
        $this->assertCount(1, $resident->documents);
        $this->assertSame('maintenance', $staff->staffProfile->staff_type);
        $this->assertSame('Resident', $complaint->resident->name);
        $this->assertSame('Technician', $complaint->workOrders->first()->assignedStaff->name);
    }
}
