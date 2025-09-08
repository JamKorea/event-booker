<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organiser_id' => User::query()
                ->where('role', 'organiser')
                ->inRandomOrder()
                ->value('id') ?? User::factory()->organiser(),
            'title'       => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'datetime'    => now()->addDays(fake()->numberBetween(-10, 30)), // mix past & future
            'location'    => fake()->city(),
            'capacity'    => fake()->numberBetween(2, 30),
        ];
    }

    /**
     * Tiny capacity (for full booking demo).
     */
    public function tinyCap(): static
    {
        return $this->state(fn () => ['capacity' => 2]);
    }

    /**
     * Mid capacity (5–8 seats).
     */
    public function midCap(): static
    {
        return $this->state(fn () => [
            'capacity' => fake()->numberBetween(5, 8),
        ]);
    }

    /**
     * Large capacity (15–30 seats).
     */
    public function largeCap(): static
    {
        return $this->state(fn () => [
            'capacity' => fake()->numberBetween(15, 30),
        ]);
    }

    /**
     * Force future event datetime.
     */
    public function futureOnly(): static
    {
        return $this->state(fn () => [
            'datetime' => now()->addDays(fake()->numberBetween(1, 30)),
        ]);
    }
}