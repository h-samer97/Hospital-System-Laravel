<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Section;


class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'admin' => auth('admins')->user(),
            'sections' => Section::where('is_active', true)->get(['id', 'name']),
        ]);
    }
}
