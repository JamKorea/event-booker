<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 2 organisers
        User::factory()->count(2)->organiser()->create();

        // Create 10 attendees
        User::factory()->count(10)->attendee()->create();
    }
}