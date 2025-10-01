<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        // Eager load counts to prevent N+1 problem
        $events = Event::withCount(['bookings', 'waitlists'])->get();

        return view('events.index', compact('events'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('events.create');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'datetime' => 'required|date',
            'capacity' => 'required|integer|min:1',
        ]);

        // Create new event
        Event::create([
            'organiser_id' => auth()->id(), // current logged-in user
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'datetime'     => $validated['datetime'],
            'capacity'     => $validated['capacity'],
            'location'     => 'TBD', // placeholder, can add later
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    // Display the specified resource
    public function show($id)
    {
        // Eager load relationships & counts
        $event = Event::withCount(['bookings', 'waitlists'])
                      ->with(['bookings', 'waitlists'])
                      ->findOrFail($id);

        return view('events.show', compact('event'));
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'datetime'    => 'required|date',
            'capacity'    => 'required|integer|min:1',
        ]);

        $event = Event::findOrFail($id);
        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}