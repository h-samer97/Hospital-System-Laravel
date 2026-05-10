<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return Inertia::render('User/Dashboard', [
            'admin' => auth('admins')->user(),
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
