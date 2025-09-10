<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Temporary placeholder until we add SQL report
        return view('dashboard', [
            'message' => 'Dashboard works! (placeholder)'
        ]);
    }
}