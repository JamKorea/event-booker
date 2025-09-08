<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // For the fully-booked first 3 events, add 2–4 people to waitlist
        $fullEvents = Event::orderBy('id')->take(3)->get();
        foreach ($fullEvents as $e) {
            $alreadyBooked = Booking::where('event_id', $e->id)->pluck('attendee_id');
            $candidates = User::where('role', 'attendee')
                ->whereNotIn('id', $alreadyBooked)
                ->inRandomOrder()
                ->take(fake()->numberBetween(2, 4))
                ->get();

            foreach ($candidates as $u) {
                Waitlist::firstOrCreate([
                    'event_id' => $e->id,
                    'user_id'  => $u->id,
                ]);
            }
        }
    }
}