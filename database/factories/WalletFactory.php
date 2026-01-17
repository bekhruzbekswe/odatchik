<?php

namespace Database\Factories;

use App\Enums\WalletType;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(WalletType::cases()),
            'balance' => fake()->numberBetween(0, 100000),
        ];
    }

    /**
     * Create a USD wallet.
     */
    public function usd(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => WalletType::USD,
        ]);
    }

    /**
     * Create a UZS wallet.
     */
    public function uzs(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => WalletType::UZS,
        ]);
    }

    /**
     * Create a COIN wallet.
     */
    public function coin(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => WalletType::COIN,
        ]);
    }
}
