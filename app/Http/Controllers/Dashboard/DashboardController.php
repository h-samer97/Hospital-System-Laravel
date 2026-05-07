<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard/Index', [
            'admin' => auth('admin')->user(),
        ]);
    }

    /**
     * Show the dashboard analytics.
     */
    public function analytics()
    {
        return view('dashboard.analytics');
    }

    /**
     * Show the dashboard settings.
     */
    public function settings()
    {
        return view('dashboard.settings');
    }
}
