<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

        // Fixed test user (organiser)
        User::create([
            'name' => 'Test Organiser',
            'email' => 'organiser@example.com',
            'password' => Hash::make('password'),
            'role' => 'organiser',
        ]);

        // Fixed test user (attendee)
        User::create([
            'name' => 'Test Attendee',
            'email' => 'attendee@example.com',
            'password' => Hash::make('password'),
            'role' => 'attendee',
        ]);
    }
}