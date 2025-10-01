<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Waitlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Mail\EventWaitlistNotification;

class BookingClaimTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cancelling_a_booking_assigns_claim_to_first_waitlist_user()
    {
        Mail::fake();

        // Organiser + 2 attendees + event
        $organiser = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create(['capacity' => 1, 'organiser_id' => $organiser->id]);

        $attendee1 = User::factory()->create(['role' => 'attendee']);
        $attendee2 = User::factory()->create(['role' => 'attendee']);

        // attendee1 books
        Booking::create(['event_id' => $event->id, 'attendee_id' => $attendee1->id]);

        // attendee2 joins waitlist
        Waitlist::create(['event_id' => $event->id, 'user_id' => $attendee2->id]);

        // cancel booking (attendee1)
        $this->actingAs($attendee1)
             ->delete(route('bookings.destroy', $event->id));

        $this->assertDatabaseHas('waitlists', [
            'event_id' => $event->id,
            'user_id' => $attendee2->id,
            'notified' => true,
        ]);

        // Check claim_expires_at set
        $this->assertNotNull(Waitlist::first()->claim_expires_at);

        Mail::assertSent(EventWaitlistNotification::class, function ($mail) use ($attendee2) {
            return $mail->hasTo($attendee2->email);
        });
    }

    /** @test */
    public function non_claim_user_cannot_book_during_active_claim()
    {
        Mail::fake();

        $organiser = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create(['capacity' => 1, 'organiser_id' => $organiser->id]);

        $attendee1 = User::factory()->create(['role' => 'attendee']);
        $attendee2 = User::factory()->create(['role' => 'attendee']);
        $attendee3 = User::factory()->create(['role' => 'attendee']);

        Booking::create(['event_id' => $event->id, 'attendee_id' => $attendee1->id]);
        Waitlist::create(['event_id' => $event->id, 'user_id' => $attendee2->id]);
        Waitlist::create(['event_id' => $event->id, 'user_id' => $attendee3->id]);

        // Cancel booking → claim goes to attendee2
        $this->actingAs($attendee1)
             ->delete(route('bookings.destroy', $event->id));

        // Attendee3 tries to book (should fail because claim active)
        $response = $this->actingAs($attendee3)
                         ->post(route('bookings.store', $event->id));

        $response->assertSessionHas('error', function ($message) {
            return str_contains($message, 'A priority claim is active until');
        });
    }

    /** @test */
    public function claim_expires_and_next_waitlist_user_can_book()
    {
        $organiser = User::factory()->create(['role' => 'organiser']);
        $event = Event::factory()->create(['capacity' => 1, 'organiser_id' => $organiser->id]);

        $attendee1 = User::factory()->create(['role' => 'attendee']);
        $attendee2 = User::factory()->create(['role' => 'attendee']);
        $attendee3 = User::factory()->create(['role' => 'attendee']);

        Booking::create(['event_id' => $event->id, 'attendee_id' => $attendee1->id]);
        Waitlist::create(['event_id' => $event->id, 'user_id' => $attendee2->id]);
        Waitlist::create(['event_id' => $event->id, 'user_id' => $attendee3->id]);

        // Cancel booking → claim to attendee2
        $this->actingAs($attendee1)
             ->delete(route('bookings.destroy', $event->id));

        // Time travel forward 3 hours (claim expired)
        Carbon::setTestNow(now()->addHours(3));

        // Attendee3 now can book
        $this->actingAs($attendee3)
             ->post(route('bookings.store', $event->id));

        $this->assertDatabaseHas('bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee3->id,
        ]);
    }
}