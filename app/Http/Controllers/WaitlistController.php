<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    /**
     * Show all waitlists that the current user has joined.
     */
    public function index()
    {
        $waitlists = Waitlist::with('event')
            ->where('user_id', auth()->id())
            ->get();

        return view('waitlists.index', compact('waitlists'));
    }

    /**
     * Join the waitlist for a specific event.
     */
    public function join($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Prevent joining if seats are still available
        if ($event->bookings()->count() < $event->capacity) {
            return redirect()->back()->with('error', 'Seats are still available. Please book directly.');
        }

        // Prevent duplicate waitlist entry
        if ($event->waitlists()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'You are already on the waitlist for this event.');
        }

        // Add user to waitlist
        Waitlist::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'You have joined the waitlist for this event.');
    }

    /**
     * Leave the waitlist for a specific event.
     */
    public function leave($eventId)
    {
        $event = Event::findOrFail($eventId);

        $waitlist = $event->waitlists()->where('user_id', auth()->id())->first();

        if (!$waitlist) {
            return redirect()->back()->with('error', 'You are not on the waitlist for this event.');
        }

        $waitlist->delete();

        return redirect()->back()->with('success', 'You have left the waitlist for this event.');
    }
}