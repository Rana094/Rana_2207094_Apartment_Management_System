<?php

namespace Database\Seeders;

use App\Http\Controllers\PaymentGatewayController;
use App\Models\Bill;
use App\Models\Building;
use App\Models\Complaint;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\EmergencyRequest;
use App\Models\Facility;
use App\Models\FacilityBooking;
use App\Models\Flat;
use App\Models\FlatMember;
use App\Models\MoveOutRequest;
use App\Models\Notice;
use App\Models\Notification;
use App\Models\ResidentProfile;
use App\Models\SecurityIncident;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\VehicleRegistration;
use App\Models\VisitorLog;
use App\Models\VisitorRequest;
use App\Models\WorkOrder;
use App\Models\WorkOrderNote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed realistic demo data for all portals and workflows.
     */
    public function run(): void
    {
        // Remove older demo accounts before reseeding so repeated seeding stays predictable.
        User::whereIn('email', [
            'manager@gmail.com',
            'manger@nestora.com',
            'resident@nestora.com',
            'pending@nestora.com',
            'ullas@gmail.com',
            'issac@gmail.com',
            'shorif@gmail.com',
            'avash@gmail.com',
        ])->delete();

        // Core demo users for each portal role.
        $manager = $this->seedUser('manager', 'manager@nestora.com', 'manager', 'approved');
        $security = $this->seedUser('ruhan', 'security@nestora.com', 'security', 'approved');
        $maintenance = $this->seedUser('bipro', 'staff@nestora.com', 'staff', 'approved');
        $resident = $this->seedUser('ullas', 'ullas@nestora.com', 'resident', 'approved', 'owner', 'Building A, Flat 1A');
        $issac = $this->seedUser('issac', 'issac@nestora.com', 'resident', 'approved', 'tenant', 'Building A, Flat 2B');
        $shorif = $this->seedUser('shorif', 'shorif@nestora.com', 'resident', 'approved', 'owner', 'Building A, Flat 4D');
        $tenant = $this->seedUser('avash', 'avash@nestora.com', 'resident', 'pending_approval', 'tenant', 'Building A, Flat 3C');

        // Building and flat data powers signup availability and manager flat management.
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

        $flatThree = Flat::updateOrCreate(
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

        $flatFour = Flat::updateOrCreate(
            ['building_id' => $building->id, 'flat_number' => '4D'],
            [
                'floor' => 4,
                'block' => 'A',
                'type' => 'family',
                'bedrooms' => 3,
                'area_sqft' => 1380,
                'status' => 'occupied',
            ]
        );

        $tenant->update([
            'requested_flat_id' => $flatThree->id,
            'flat_info' => $building->name.', Flat '.$flatThree->flat_number,
        ]);

        // Resident profiles connect approved residents to their active flats.
        $residentProfile = ResidentProfile::updateOrCreate(
            ['user_id' => $resident->id],
            [
                'flat_id' => $flatOne->id,
                'resident_type' => 'owner',
                'move_in_date' => now()->subYears(2)->toDateString(),
                'emergency_contact_name' => 'alok',
                'emergency_contact_phone' => '+880 1711 223344',
                'status' => 'active',
            ]
        );

        $issacProfile = ResidentProfile::updateOrCreate(
            ['user_id' => $issac->id],
            [
                'flat_id' => $flatTwo->id,
                'resident_type' => 'tenant',
                'move_in_date' => now()->subMonths(8)->toDateString(),
                'emergency_contact_name' => 'maria',
                'emergency_contact_phone' => '+880 1711 556677',
                'status' => 'active',
            ]
        );

        $shorifProfile = ResidentProfile::updateOrCreate(
            ['user_id' => $shorif->id],
            [
                'flat_id' => $flatFour->id,
                'resident_type' => 'owner',
                'move_in_date' => now()->subYear()->toDateString(),
                'emergency_contact_name' => 'ayesha',
                'emergency_contact_phone' => '+880 1711 889900',
                'status' => 'active',
            ]
        );

        ResidentProfile::updateOrCreate(
            ['user_id' => $tenant->id],
            [
                'flat_id' => $flatThree->id,
                'resident_type' => 'tenant',
                'move_in_date' => now()->subMonth()->toDateString(),
                'emergency_contact_name' => 'alok',
                'emergency_contact_phone' => '+880 1711 112233',
                'status' => 'pending',
            ]
        );

        FlatMember::updateOrCreate(
            ['resident_profile_id' => $residentProfile->id, 'name' => 'puja'],
            ['relationship' => 'Spouse', 'phone' => '+880 1711 223344']
        );

        FlatMember::updateOrCreate(
            ['resident_profile_id' => $issacProfile->id, 'name' => 'maria'],
            ['relationship' => 'Spouse', 'phone' => '+880 1711 556677']
        );

        FlatMember::updateOrCreate(
            ['resident_profile_id' => $shorifProfile->id, 'name' => 'ayesha'],
            ['relationship' => 'Spouse', 'phone' => '+880 1711 889900']
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

        VehicleRegistration::updateOrCreate(
            ['registration_number' => 'DHAKA-METRO-KHA-778899'],
            [
                'resident_profile_id' => $issacProfile->id,
                'vehicle_type' => 'motorbike',
                'brand' => 'Yamaha',
                'model' => 'FZS',
                'parking_slot' => 'B-04',
                'status' => 'active',
            ]
        );

        Document::updateOrCreate(
            ['user_id' => $resident->id, 'title' => 'NID Verification Copy'],
            [
                'flat_id' => $flatOne->id,
                'type' => 'nid',
                'file_path' => 'resident-documents/demo-nid-ullas.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 128000,
                'status' => 'approved',
                'verified_at' => now()->subDays(10),
                'verified_by' => $manager->id,
            ]
        );

        Document::updateOrCreate(
            ['user_id' => $issac->id, 'title' => 'Lease Agreement'],
            [
                'flat_id' => $flatTwo->id,
                'type' => 'lease',
                'file_path' => 'resident-documents/demo-lease-issac.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 256000,
                'status' => 'pending_verification',
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

        // Complaint and work order demonstrate resident-to-manager-to-staff maintenance flow.
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

        $workOrder = WorkOrder::updateOrCreate(
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

        WorkOrderNote::updateOrCreate(
            ['work_order_id' => $workOrder->id, 'user_id' => $maintenance->id, 'status' => 'in_progress'],
            [
                'remarks' => 'Initial inspection scheduled with resident. Replacement seal kit prepared.',
                'proof_path' => null,
                'noted_at' => now()->subHours(2),
            ]
        );

        // Bills demonstrate unpaid, paid, overdue, and facility subscription payment states.
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

        Bill::updateOrCreate(
            ['bill_number' => 'BILL-2026-07-2B'],
            [
                'resident_id' => $issac->id,
                'flat_id' => $flatTwo->id,
                'billing_month' => now()->startOfMonth()->toDateString(),
                'type' => 'monthly_service_charge',
                'amount' => 3800,
                'due_date' => now()->addDays(6)->toDateString(),
                'status' => 'unpaid',
            ]
        );

        Bill::updateOrCreate(
            ['bill_number' => 'BILL-2026-07-4D'],
            [
                'resident_id' => $shorif->id,
                'flat_id' => $flatFour->id,
                'billing_month' => now()->startOfMonth()->toDateString(),
                'type' => 'monthly_service_charge',
                'amount' => 4200,
                'due_date' => now()->addDays(4)->toDateString(),
                'status' => 'overdue',
            ]
        );

        Bill::updateOrCreate(
            ['bill_number' => 'GYM-2026-07-1A'],
            [
                'resident_id' => $resident->id,
                'flat_id' => $flatOne->id,
                'billing_month' => now()->startOfMonth()->toDateString(),
                'type' => 'gym_monthly_subscription',
                'amount' => 3000,
                'due_date' => now()->addDays(7)->toDateString(),
                'status' => 'unpaid',
            ]
        );

        // Visitor requests and logs demonstrate security check-in/check-out screens.
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

        VisitorRequest::updateOrCreate(
            ['access_code' => 'GUEST2B'],
            [
                'resident_id' => $issac->id,
                'flat_id' => $flatTwo->id,
                'visitor_name' => 'Daniel Costa',
                'visitor_phone' => '+880 1811 989898',
                'purpose' => 'Dinner visit',
                'visit_date' => now()->addDays(2)->toDateString(),
                'expected_entry_time' => '19:30',
                'status' => 'pending',
            ]
        );

        $checkedInVisitor = VisitorRequest::updateOrCreate(
            ['access_code' => 'N-5509'],
            [
                'resident_id' => $resident->id,
                'flat_id' => $flatOne->id,
                'visitor_name' => 'Farhan Alvi',
                'visitor_phone' => '+880 1811 777888',
                'purpose' => 'Courier delivery',
                'visit_date' => today(),
                'expected_entry_time' => now()->subHour()->format('H:i'),
                'status' => 'checked_in',
                'checked_in_at' => now()->subMinutes(45),
            ]
        );

        VisitorLog::updateOrCreate(
            ['visitor_request_id' => $checkedInVisitor->id, 'event_type' => 'check_in'],
            [
                'flat_id' => $flatOne->id,
                'security_user_id' => $security->id,
                'visitor_name' => $checkedInVisitor->visitor_name,
                'visitor_phone' => $checkedInVisitor->visitor_phone,
                'access_code' => $checkedInVisitor->access_code,
                'purpose' => $checkedInVisitor->purpose,
                'vehicle_plate' => null,
                'occurred_at' => now()->subMinutes(45),
            ]
        );

        // Facilities demonstrate booking requests and manager approval/billing.
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
                'description' => 'Monthly resident fitness subscription.',
                'capacity' => 20,
                'booking_fee' => 3000,
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

        $rooftop = Facility::where('name', 'Rooftop BBQ Grill Station')->first();

        FacilityBooking::updateOrCreate(
            ['resident_id' => $issac->id, 'facility_id' => $rooftop?->id, 'booking_date' => now()->addDays(5)->toDateString()],
            [
                'start_time' => '17:00',
                'end_time' => '21:00',
                'purpose' => 'Birthday BBQ',
                'status' => 'approved',
            ]
        );

        FacilityBooking::updateOrCreate(
            ['resident_id' => $shorif->id, 'facility_id' => $communityHall->id, 'booking_date' => now()->addDays(10)->toDateString()],
            [
                'start_time' => '11:00',
                'end_time' => '15:00',
                'purpose' => 'Family program',
                'status' => 'rejected',
            ]
        );

        EmergencyRequest::updateOrCreate(
            ['resident_id' => $resident->id, 'type' => 'maintenance', 'message' => 'Elevator stopped briefly near floor one.'],
            [
                'flat_id' => $flatOne->id,
                'status' => 'resolved',
                'created_at' => now()->subDays(3),
                'resolved_at' => now()->subDays(2),
            ]
        );

        EmergencyRequest::updateOrCreate(
            ['resident_id' => $issac->id, 'type' => 'medical', 'message' => 'Resident requested urgent medical help from flat 2B.'],
            [
                'flat_id' => $flatTwo->id,
                'status' => 'open',
                'created_at' => now()->subMinutes(30),
                'resolved_at' => null,
            ]
        );

        $secondComplaint = Complaint::updateOrCreate(
            ['resident_id' => $issac->id, 'title' => 'Bedroom AC not cooling'],
            [
                'flat_id' => $flatTwo->id,
                'category' => 'electrical',
                'description' => 'AC turns on but does not cool properly.',
                'priority' => 'medium',
                'status' => 'open',
            ]
        );

        Complaint::updateOrCreate(
            ['resident_id' => $shorif->id, 'title' => 'Corridor light flickering'],
            [
                'flat_id' => $flatFour->id,
                'category' => 'electrical',
                'description' => 'Common corridor light near flat 4D is flickering at night.',
                'priority' => 'low',
                'status' => 'completed',
            ]
        );

        WorkOrder::updateOrCreate(
            ['complaint_id' => $secondComplaint->id, 'title' => 'Check AC cooling issue'],
            [
                'assigned_to' => $maintenance->id,
                'assigned_by' => $manager->id,
                'instructions' => 'Inspect gas level and clean AC filter.',
                'priority' => 'medium',
                'status' => 'in_progress',
                'due_at' => now()->addDays(2),
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

        Notice::updateOrCreate(
            ['title' => 'Gym subscription policy updated'],
            [
                'created_by' => $manager->id,
                'body' => 'Gym access is now handled as a Tk 3,000 monthly subscription after manager approval.',
                'audience' => 'resident',
                'published_at' => now()->subHours(6),
            ]
        );

        ContactMessage::updateOrCreate(
            ['email' => 'society.owner@example.com', 'subject' => 'Apartment management demo request'],
            [
                'name' => 'feroz',
                'phone' => '+880 1811 112244',
                'message' => 'I want to see how Nestora handles residents, bills, visitors, and emergencies.',
                'status' => 'new',
            ]
        );

        Notification::updateOrCreate(
            ['user_id' => $resident->id, 'type' => 'bill_created', 'title' => 'New bill generated'],
            [
                'audience' => 'user',
                'body' => 'Your July service charge and gym subscription bills are available.',
                'action_url' => route('resident.bills.index', absolute: false),
                'read_at' => null,
            ]
        );

        SecurityIncident::updateOrCreate(
            ['subject' => 'Unauthorized parking block'],
            [
                'reported_by' => $security->id,
                'flat_id' => $flatOne->id,
                'category' => 'parking',
                'description' => 'A visitor vehicle temporarily blocked the basement ramp.',
                'status' => 'open',
                'occurred_at' => now()->subHours(3),
            ]
        );

        // Every unpaid bill gets a payment token for the Nestora Pay demo flow.
        Bill::where('status', '!=', 'paid')->get()->each(fn (Bill $bill) => PaymentGatewayController::sessionForBill($bill));
    }

    /**
     * Create or update one demo user; default password is "password".
     */
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
