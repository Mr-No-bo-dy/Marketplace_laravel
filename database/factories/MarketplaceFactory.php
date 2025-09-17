<?php

namespace Database\Factories;

use App\Models\Marketplace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Marketplace>
 */
class MarketplaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        return [
//            'country_code' => fake()->countryCode(),
//            'country' => fake()->country(),
//            'currency' => fake()->currencyCode(),
//        ];

        static $codes = ['UA', 'UK', 'US',];
        $code = array_shift($codes);

        static $countries = ['Ukraine', 'Great Britain', 'USA',];
        $country = array_shift($countries);

        static $currencies = ['UAH', 'GBP', 'USD',];
        $currency = array_shift($currencies);

        return [
            'country_code' => $code,
            'country' => $country,
            'currency' => $currency,
        ];
    }
}
