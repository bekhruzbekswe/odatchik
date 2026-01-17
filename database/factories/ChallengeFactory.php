<?php

namespace Database\Factories;

use App\Enums\ChallengeFrequency;
use App\Models\Challenge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Challenge::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+3 months');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'frequency' => fake()->randomElement(ChallengeFrequency::cases()),
            'is_public' => fake()->boolean(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'checkin_deadline' => fake()->time('H:i'),
            'price_per_miss' => fake()->numberBetween(1000, 50000),
            'price_early_leave' => fake()->numberBetween(5000, 100000),
            'coins_per_checkin' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Create a daily challenge.
     */
    public function daily(): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency' => ChallengeFrequency::DAILY,
        ]);
    }

    /**
     * Create a weekly challenge.
     */
    public function weekly(): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency' => ChallengeFrequency::WEEKLY,
        ]);
    }

    /**
     * Create a monthly challenge.
     */
    public function monthly(): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency' => ChallengeFrequency::MONTHLY,
        ]);
    }

    /**
     * Create a public challenge.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }

    /**
     * Create a private challenge.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }
}
