<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendees = User::where('role', 'attendee')->pluck('id')->all();

        // Make E1–E3 fully booked (capacity exactly filled)
        $fullEvents = Event::orderBy('id')->take(3)->get();
        foreach ($fullEvents as $e) {
            $take = min($e->capacity, count($attendees));
            $ids = collect($attendees)->shuffle()->take($take);
            foreach ($ids as $aid) {
                Booking::firstOrCreate([
                    'event_id'    => $e->id,
                    'attendee_id' => $aid,
                ]);
            }
        }

        // Lightly fill several other events
        $other = Event::whereNotIn('id', $fullEvents->pluck('id'))->get();
        foreach ($other as $e) {
            $want = fake()->numberBetween(0, max(0, min(12, $e->capacity - 1)));
            $ids = collect($attendees)->shuffle()->take($want);
            foreach ($ids as $aid) {
                Booking::firstOrCreate([
                    'event_id'    => $e->id,
                    'attendee_id' => $aid,
                ]);
            }
        }
    }
}