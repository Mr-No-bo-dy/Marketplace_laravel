<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_producer' => fake()->randomElement(Producer::query()->pluck('id_producer')),
            'id_category' => fake()->randomElement(Category::query()->pluck('id_category')),
            'id_subcategory' => fake()->randomElement(Subcategory::query()->pluck('id_subcategory')),
            'id_seller' => fake()->randomElement(Seller::query()->pluck('id_seller')),
            'name' => ucwords(fake()->words(3, true)),
            'description' => fake()->paragraph(3),
            'price' => rand(300, 9000),
            'amount' => fake()->numberBetween(1, 200),
        ];
    }
}
