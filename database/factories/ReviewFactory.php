<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_client' => fake()->randomElement(Client::query()->pluck('id_client')),
            'id_product' => fake()->randomElement(Product::query()->pluck('id_product')),
            'comment' => fake()->sentence(12),
            'rating' => rand(1, 5),
            'status' => rand(1, 2),
        ];
    }
}
