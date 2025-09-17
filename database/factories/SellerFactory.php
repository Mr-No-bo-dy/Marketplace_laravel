<?php

namespace Database\Factories;

use App\Models\Marketplace;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seller>
 */
class SellerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_marketplace' => fake()->randomElement(Marketplace::query()->pluck('id_marketplace')),
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+' . fake()->numerify('############'),
        ];
    }
}
