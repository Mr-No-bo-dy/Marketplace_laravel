<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
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
            'id_seller' => fake()->randomElement(Seller::query()->pluck('id_seller')),
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'canceled']),
        ];
    }
}
