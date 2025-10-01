<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use App\Models\User;
use App\Models\Booking;
use App\Models\Waitlist;
use App\Mail\EventWaitlistNotification;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cancelling_a_booking_sends_email_to_first_waitlist_user()
    {
        Mail::fake();

        // Create organiser and attendees
        $organiser = User::factory()->create(['role' => 'organiser']);
        $attendee1 = User::factory()->create(['role' => 'attendee']);
        $attendee2 = User::factory()->create(['role' => 'attendee']);

        // Create event with capacity 1
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'capacity'     => 1,
        ]);

        // attendee1 books the only spot
        Booking::create([
            'event_id'    => $event->id,
            'attendee_id' => $attendee1->id,
        ]);

        // attendee2 joins the waitlist
        Waitlist::create([
            'event_id' => $event->id,
            'user_id'  => $attendee2->id,
        ]);

        // attendee1 cancels -> should trigger mail to attendee2
        $this->actingAs($attendee1)
             ->delete(route('bookings.destroy', $event->id));

        // Assert email sent to attendee2
        Mail::assertSent(EventWaitlistNotification::class, function ($mail) use ($attendee2) {
            return $mail->hasTo($attendee2->email);
        });
    }
}