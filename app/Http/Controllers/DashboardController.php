<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function resident(Request $request): View
    {
        return view('resident.dashboard');
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
