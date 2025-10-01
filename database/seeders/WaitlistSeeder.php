<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use App\Models\Waitlist;

class WaitlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pick first 3 events (assume tiny cap → likely full)
        $fullEvents = Event::orderBy('id')->take(3)->get();

        foreach ($fullEvents as $event) {
            // Get attendees already booked for this event
            $alreadyBooked = Booking::where('event_id', $event->id)->pluck('attendee_id');

            // Random attendees who are not already booked
            $candidates = User::where('role', 'attendee')
                ->whereNotIn('id', $alreadyBooked)
                ->inRandomOrder()
                ->take(fake()->numberBetween(2, 4)) // 2–4 waitlist entries
                ->get();

            foreach ($candidates as $user) {
                // Add waitlist entry if not already existing
                Waitlist::firstOrCreate([
                    'event_id' => $event->id,
                    'user_id'  => $user->id,
                ]);
            }
        }
    }
}