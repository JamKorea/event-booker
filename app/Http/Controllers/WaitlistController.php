<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Waitlist;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    // Join the waitlist for a full event
    public function join($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Only allow join if event is full
        if ($event->bookings()->count() < $event->capacity) {
            return redirect()->back()->with('error', 'This event is not full yet.');
        }

        // Prevent duplicate waitlist entry
        if ($event->waitlists()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'You are already on the waitlist.');
        }

        // Add user to waitlist
        $waitlist = Waitlist::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ]);

        // Figure out user's position in waitlist
        $position = $event->waitlists()
            ->where('id', '<=', $waitlist->id)
            ->count();

        return redirect()->back()->with('success', "You have joined the waitlist. Your position is #{$position}.");
    }

    // Leave the waitlist
    public function leave($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Find the waitlist record for this user
        $waitlist = $event->waitlists()->where('user_id', auth()->id())->first();

        if (!$waitlist) {
            return redirect()->back()->with('error', 'You are not on the waitlist.');
        }

        $waitlist->delete();

        return redirect()->back()->with('success', 'You have left the waitlist.');
    }

    // Show "My Waitlists" page for the attendee
    public function index()
    {
        // Load waitlists with related event info + eager load bookings + waitlist counts
        $waitlists = auth()->user()->waitlists()
            ->with(['event' => function ($query) {
                $query->withCount(['bookings', 'waitlists']);
            }])
            ->get();

        // For each waitlist record, calculate the user's position
        foreach ($waitlists as $waitlist) {
            $waitlist->position = $waitlist->event->waitlists()
                ->where('id', '<=', $waitlist->id)
                ->count();
        }

        return view('waitlists.index', compact('waitlists'));
    }
}