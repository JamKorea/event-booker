<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // E1–E3 tiny capacity (2 seats) for waitlist demo
        Event::factory()->count(3)->tinyCap()->futureOnly()->create();

        // E4–E7 mid capacity (5–8 seats)
        Event::factory()->count(4)->midCap()->futureOnly()->create();

        // E8–E15 larger capacity (15–30 seats, mix past/future)
        Event::factory()->count(8)->largeCap()->create();
    }
}