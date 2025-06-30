<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard main page.
     */
    public function index(Request $request)
    {
        // For now, just return a simple message or view
        return response()->json(['message' => 'Dashboard is under construction.']);
    }
} 