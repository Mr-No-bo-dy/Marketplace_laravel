<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderDetails>
 */
class OrderDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $count = fake()->numberBetween(1, 5);
        $price = rand(300, 9000);

        return [
            'id_order' => fake()->randomElement(Order::query()->pluck('id_order')),
            'id_product' => fake()->randomElement(Product::query()->pluck('id_product')),
            'count' => $count,
            'total' => $count * $price,
        ];
    }
}
