<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\EmergencyRequest;
use App\Models\Facility;
use App\Models\FacilityBooking;
use App\Models\Flat;
use App\Models\FlatMember;
use App\Models\MoveOutRequest;
use App\Models\Notice;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\ResidentProfile;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\VehicleRegistration;
use App\Models\VisitorRequest;
use App\Models\WorkOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $manager = $this->seedUser('Building Manager', 'manager@nestora.com', 'manager', 'approved');
        $security = $this->seedUser('Security Guard', 'security@nestora.com', 'security', 'approved');
        $maintenance = $this->seedUser('Maintenance Staff', 'staff@nestora.com', 'staff', 'approved');
        $resident = $this->seedUser('Approved Resident', 'resident@nestora.com', 'resident', 'approved', 'owner', 'Building A, Flat 1A');
        $tenant = $this->seedUser('Pending Resident', 'pending@nestora.com', 'resident', 'pending_approval', 'tenant', 'Building A, Flat 2B');

        $building = Building::updateOrCreate(
            ['code' => 'NEST-A'],
            [
                'name' => 'Nestora Heights - Building A',
                'address' => '12/A, Dhanmondi, Dhaka',
                'floors' => 8,
                'total_flats' => 16,
            ]
        );

        $flatOne = Flat::updateOrCreate(
            ['building_id' => $building->id, 'flat_number' => '1A'],
            [
                'floor' => 1,
                'block' => 'A',
                'type' => 'family',
                'bedrooms' => 3,
                'area_sqft' => 1450,
                'status' => 'occupied',
            ]
        );

        $flatTwo = Flat::updateOrCreate(
            ['building_id' => $building->id, 'flat_number' => '2B'],
            [
                'floor' => 2,
                'block' => 'A',
                'type' => 'family',
                'bedrooms' => 2,
                'area_sqft' => 1125,
                'status' => 'occupied',
            ]
        );

        Flat::updateOrCreate(
            ['building_id' => $building->id, 'flat_number' => '3C'],
            [
                'floor' => 3,
                'block' => 'A',
                'type' => 'studio',
                'bedrooms' => 1,
                'area_sqft' => 720,
                'status' => 'vacant',
            ]
        );

        $residentProfile = ResidentProfile::updateOrCreate(
            ['user_id' => $resident->id],
            [
                'flat_id' => $flatOne->id,
                'resident_type' => 'owner',
                'move_in_date' => now()->subYears(2)->toDateString(),
                'emergency_contact_name' => 'Ayesha Rahman',
                'emergency_contact_phone' => '+880 1711 223344',
                'status' => 'active',
            ]
        );

        ResidentProfile::updateOrCreate(
            ['user_id' => $tenant->id],
            [
                'flat_id' => $flatTwo->id,
                'resident_type' => 'tenant',
                'move_in_date' => now()->subMonths(3)->toDateString(),
                'emergency_contact_name' => 'Karim Uddin',
                'emergency_contact_phone' => '+880 1711 556677',
                'status' => 'pending',
            ]
        );

        FlatMember::updateOrCreate(
            ['resident_profile_id' => $residentProfile->id, 'name' => 'Ayesha Rahman'],
            ['relationship' => 'Spouse', 'phone' => '+880 1711 223344']
        );

        VehicleRegistration::updateOrCreate(
            ['registration_number' => 'DHAKA-METRO-GA-123456'],
            [
                'resident_profile_id' => $residentProfile->id,
                'vehicle_type' => 'car',
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'parking_slot' => 'A-12',
                'status' => 'active',
            ]
        );

        Document::updateOrCreate(
            ['user_id' => $resident->id, 'title' => 'Sample National ID'],
            [
                'flat_id' => $flatOne->id,
                'type' => 'national_id',
                'file_path' => 'resident-documents/sample-national-id.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 245760,
                'status' => 'approved',
                'verified_at' => now(),
                'verified_by' => $manager->id,
            ]
        );

        StaffProfile::updateOrCreate(
            ['user_id' => $security->id],
            [
                'staff_type' => 'security',
                'employee_code' => 'SEC-001',
                'shift' => 'Day',
                'joined_at' => now()->subYear()->toDateString(),
                'status' => 'active',
            ]
        );

        StaffProfile::updateOrCreate(
            ['user_id' => $maintenance->id],
            [
                'staff_type' => 'maintenance',
                'employee_code' => 'MNT-001',
                'shift' => 'Morning',
                'joined_at' => now()->subMonths(8)->toDateString(),
                'status' => 'active',
            ]
        );

        $complaint = Complaint::updateOrCreate(
            ['resident_id' => $resident->id, 'title' => 'Kitchen sink leakage'],
            [
                'flat_id' => $flatOne->id,
                'category' => 'plumbing',
                'description' => 'Water is leaking below the kitchen sink and needs inspection.',
                'priority' => 'high',
                'status' => 'in_progress',
            ]
        );

        WorkOrder::updateOrCreate(
            ['complaint_id' => $complaint->id, 'title' => 'Inspect kitchen sink leakage'],
            [
                'assigned_to' => $maintenance->id,
                'assigned_by' => $manager->id,
                'instructions' => 'Check pipe joints, replace seal if needed, and upload completion proof.',
                'priority' => 'high',
                'status' => 'todo',
                'due_at' => now()->addDay(),
            ]
        );

        Bill::updateOrCreate(
            ['bill_number' => 'BILL-2026-07-1A'],
            [
                'resident_id' => $resident->id,
                'flat_id' => $flatOne->id,
                'billing_month' => now()->startOfMonth()->toDateString(),
                'type' => 'monthly_service_charge',
                'amount' => 4500,
                'due_date' => now()->startOfMonth()->addDays(9)->toDateString(),
                'status' => 'unpaid',
            ]
        );

        Bill::updateOrCreate(
            ['bill_number' => 'BILL-2026-06-1A'],
            [
                'resident_id' => $resident->id,
                'flat_id' => $flatOne->id,
                'billing_month' => now()->subMonth()->startOfMonth()->toDateString(),
                'type' => 'monthly_service_charge',
                'amount' => 4500,
                'due_date' => now()->subMonth()->startOfMonth()->addDays(9)->toDateString(),
                'status' => 'paid',
                'paid_at' => now()->subMonth()->startOfMonth()->addDays(5),
            ]
        );

        VisitorRequest::updateOrCreate(
            ['access_code' => 'VISIT123'],
            [
                'resident_id' => $resident->id,
                'flat_id' => $flatOne->id,
                'visitor_name' => 'Tanvir Ahmed',
                'visitor_phone' => '+880 1811 222333',
                'purpose' => 'Family visit',
                'visit_date' => now()->addDay()->toDateString(),
                'expected_entry_time' => '18:00',
                'status' => 'approved',
            ]
        );

        $communityHall = Facility::updateOrCreate(
            ['name' => 'Community Hall'],
            [
                'description' => 'Shared hall for resident programs and family events.',
                'capacity' => 80,
                'booking_fee' => 2500,
                'status' => 'available',
            ]
        );

        $gym = Facility::updateOrCreate(
            ['name' => 'Gym'],
            [
                'description' => 'Resident fitness facility.',
                'capacity' => 20,
                'booking_fee' => 0,
                'status' => 'available',
            ]
        );

        Facility::updateOrCreate(
            ['name' => 'Rooftop BBQ Grill Station'],
            [
                'description' => 'Open rooftop cooking and gathering station.',
                'capacity' => 25,
                'booking_fee' => 1500,
                'status' => 'available',
            ]
        );

        FacilityBooking::updateOrCreate(
            ['resident_id' => $resident->id, 'facility_id' => $communityHall->id, 'booking_date' => now()->addWeek()->toDateString()],
            [
                'start_time' => '18:00',
                'end_time' => '21:00',
                'purpose' => 'Family gathering',
                'status' => 'pending',
            ]
        );

        FacilityBooking::updateOrCreate(
            ['resident_id' => $resident->id, 'facility_id' => $gym->id, 'booking_date' => now()->addDays(2)->toDateString()],
            [
                'start_time' => '07:00',
                'end_time' => '08:00',
                'purpose' => 'Morning workout',
                'status' => 'approved',
            ]
        );

        $poll = Poll::updateOrCreate(
            ['title' => 'Install rooftop solar panels?'],
            [
                'description' => 'Vote on using service funds for rooftop solar panels powering common lobby lights.',
                'status' => 'active',
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(10),
            ]
        );

        foreach (['Yes, approve installation', 'No, keep current setup', 'Abstain'] as $label) {
            PollOption::updateOrCreate(
                ['poll_id' => $poll->id, 'label' => $label],
                []
            );
        }

        EmergencyRequest::updateOrCreate(
            ['resident_id' => $resident->id, 'type' => 'maintenance', 'created_at' => now()->subDays(3)],
            [
                'flat_id' => $flatOne->id,
                'message' => 'Elevator stopped briefly near floor one.',
                'status' => 'resolved',
                'resolved_at' => now()->subDays(2),
            ]
        );

        MoveOutRequest::updateOrCreate(
            ['resident_id' => $resident->id, 'requested_move_out_date' => now()->addMonths(3)->toDateString()],
            [
                'flat_id' => $flatOne->id,
                'reason' => 'Tentative move-out planning request for manager review.',
                'status' => 'pending',
            ]
        );

        Notice::updateOrCreate(
            ['title' => 'Water tank cleaning schedule'],
            [
                'created_by' => $manager->id,
                'body' => 'The main water tank will be cleaned this Friday between 10 AM and 2 PM.',
                'audience' => 'all',
                'published_at' => now()->subDay(),
            ]
        );
    }

    private function seedUser(
        string $name,
        string $email,
        string $role,
        string $status,
        ?string $residentType = null,
        ?string $flatInfo = null
    ): User {
        return User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'phone' => '+880 1700 000000',
                'password' => 'password',
                'role' => $role,
                'status' => $status,
                'resident_type' => $residentType,
                'flat_info' => $flatInfo,
                'email_verified_at' => now(),
                'approved_at' => $status === 'approved' ? now() : null,
            ]
        );
    }
}
