<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Store a new booking (attendee joins an event).
     *
     * @param  int  $eventId
     */
    public function store($eventId)
    {
        $event = Event::findOrFail($eventId);

        // 1. Capacity check
        if ($event->bookings()->count() >= $event->capacity) {
            return redirect()->back()->with('error', 'This event is already full.');
        }

        // 2. Prevent duplicate booking
        if ($event->bookings()->where('attendee_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'You have already booked this event.');
        }

        // 3. Create booking
        Booking::create([
            'event_id'    => $event->id,
            'attendee_id' => auth()->id(),
        ]);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'You have successfully booked this event!');
    }

    /**
     * Cancel an existing booking.
     *
     * @param  int  $eventId
     */
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

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Your booking has been cancelled.');
    }
}