<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Waitlist;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventWaitlistNotification;

class BookingController extends Controller
{
    // Store a new booking (attendee joins an event)
    public function store($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Capacity check
        if ($event->bookings()->count() >= $event->capacity) {
            return redirect()->back()->with('error', 'This event is already full.');
        }

        // Prevent duplicate booking
        if ($event->bookings()->where('attendee_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'You have already booked this event.');
        }

        // Create booking
        Booking::create([
            'event_id'    => $event->id,
            'attendee_id' => auth()->id(),
        ]);

        return redirect()->route('events.show', $event)
                         ->with('success', 'You have successfully booked this event!');
    }

    // Cancel an existing booking
    public function destroy($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Find the booking for this user
        $booking = $event->bookings()->where('attendee_id', auth()->id())->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'You do not have a booking for this event.');
        }

        // Delete booking
        $booking->delete();

        // Excellence Marker: Notify first waitlist user if any exist
        $nextWaitlist = $event->waitlists()->orderBy('created_at')->first();

        if ($nextWaitlist) {
            Mail::to($nextWaitlist->user->email)->send(
                new EventWaitlistNotification($event, $nextWaitlist->user)
            );

            return redirect()->route('events.show', $event)
                             ->with('success', 'Your booking has been cancelled. The next waitlist user has been notified.');
        }

        return redirect()->route('events.show', $event)
                         ->with('success', 'Your booking has been cancelled.');
    }
}