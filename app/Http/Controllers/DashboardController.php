<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Build the resident dashboard from the resident's own latest records.
     */
    public function resident(Request $request): View
    {
        $user = $request->user();
        $profile = $user->residentProfile()->with('flat.building')->first();

        return view('resident.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'flat' => $profile?->flat,
            // Keep dashboard lists short so the page stays fast and scannable.
            'currentBills' => $user->bills()->latest('due_date')->take(5)->get(),
            'activeComplaints' => $user->complaints()->whereIn('status', ['open', 'in_progress'])->latest()->take(5)->get(),
            'upcomingBookings' => $user->facilityBookings()->with('facility')->whereDate('booking_date', '>=', today())->take(5)->get(),
            'recentVisitors' => $user->visitorRequests()->latest('visit_date')->take(5)->get(),
        ]);
    }

    /**
     * Basic manager dashboard entry; detailed manager stats are handled by ManagerPortalController.
     */
    public function manager(Request $request): View
    {
        return view('manager.dashboard');
    }

    /**
     * Basic security dashboard entry.
     */
    public function security(Request $request): View
    {
        return view('security.dashboard');
    }

    /**
     * Basic maintenance dashboard entry.
     */
    public function maintenance(Request $request): View
    {
        return view('maintenance.dashboard');
    }
}
