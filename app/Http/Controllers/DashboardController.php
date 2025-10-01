<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Show organiser dashboard with raw SQL stats
    public function index()
    {
        // Raw SQL query: events + capacity + bookings + remaining
        $stats = DB::select("
            SELECT 
                e.id,
                e.title,
                e.capacity,
                COUNT(b.id) as bookings_count,
                (e.capacity - COUNT(b.id)) as remaining
            FROM events e
            LEFT JOIN bookings b ON e.id = b.event_id
            GROUP BY e.id, e.title, e.capacity
            ORDER BY e.id ASC
        ");

        return view('dashboard', compact('stats'));
    }
}