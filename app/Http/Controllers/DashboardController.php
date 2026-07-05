<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function resident(Request $request): View
    {
        $user = $request->user();
        $profile = $user->residentProfile()->with('flat.building')->first();

        return view('resident.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'flat' => $profile?->flat,
            'currentBills' => $user->bills()->latest('due_date')->take(5)->get(),
            'activeComplaints' => $user->complaints()->whereIn('status', ['open', 'in_progress'])->latest()->take(5)->get(),
            'upcomingBookings' => $user->facilityBookings()->with('facility')->whereDate('booking_date', '>=', today())->take(5)->get(),
            'recentVisitors' => $user->visitorRequests()->latest('visit_date')->take(5)->get(),
        ]);
    }

    public function manager(Request $request): View
    {
        return view('dashboards.placeholder', [
            'title' => 'Building Manager Dashboard',
            'message' => 'Manager backend access is ready, including resident approval routes.',
            'user' => $request->user(),
        ]);
    }

    public function security(Request $request): View
    {
        return view('dashboards.placeholder', [
            'title' => 'Security Guard Dashboard',
            'message' => 'Security backend access is ready.',
            'user' => $request->user(),
        ]);
    }

    public function maintenance(Request $request): View
    {
        return view('dashboards.placeholder', [
            'title' => 'Maintenance Staff Dashboard',
            'message' => 'Maintenance backend access is ready.',
            'user' => $request->user(),
        ]);
    }
}
