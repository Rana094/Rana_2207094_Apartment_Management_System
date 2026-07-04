<?php

namespace Database\Seeders;

use App\Models\User;
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
        $users = [
            ['Building Manager', 'manager@nestora.com', 'manager', 'approved'],
            ['Security Guard', 'security@nestora.com', 'security', 'approved'],
            ['Maintenance Staff', 'staff@nestora.com', 'staff', 'approved'],
            ['Approved Resident', 'resident@nestora.com', 'resident', 'approved'],
            ['Pending Resident', 'pending@nestora.com', 'resident', 'pending_approval'],
        ];

        foreach ($users as [$name, $email, $role, $status]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => '+880 1700 000000',
                    'password' => 'password',
                    'role' => $role,
                    'status' => $status,
                    'resident_type' => $role === 'resident' ? 'owner' : null,
                    'flat_info' => $role === 'resident' ? 'Building A, Flat 1A' : null,
                    'email_verified_at' => now(),
                    'approved_at' => $status === 'approved' ? now() : null,
                ]
            );
        }
    }
}
