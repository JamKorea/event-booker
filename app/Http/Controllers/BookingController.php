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

        // Respect active claim: only the claim holder can book during the window
        $activeClaim = $event->waitlists()
        ->whereNotNull('claim_expires_at')
        ->where('claim_expires_at', '>', now())
        ->orderBy('created_at')
        ->first();

        if ($activeClaim && $activeClaim->user_id !== auth()->id()) {
            return redirect()->back()->with(
                'error',
                'A priority claim is active until ' .
                $activeClaim->claim_expires_at->format('M d, Y h:i A') .
                '. Only the notified user can book during this window.'
            );
        }

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

        // If this user was in waitlist, remove their waitlist entry
        $event->waitlists()->where('user_id', auth()->id())->delete();

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

        //Notify first waitlist user if any exist
        $nextWaitlist = $event->waitlists()->orderBy('created_at')->first();

        if ($nextWaitlist) {
            // Assign a 2-hour claim window
            $nextWaitlist->assignClaimWindow(120);

            // Mark as notified
            $nextWaitlist->markAsNotified();

            // Send email with claim expiry
            Mail::to($nextWaitlist->user->email)->send(
                new EventWaitlistNotification($event, $nextWaitlist->user, $nextWaitlist->claim_expires_at)
            );

            return redirect()->route('events.show', $event)
                             ->with('success', 'Your booking has been cancelled. The next waitlist user has been notified.');
        }

        return redirect()->route('events.show', $event)
                         ->with('success', 'Your booking has been cancelled.');
    }
}