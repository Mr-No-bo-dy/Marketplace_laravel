<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seller = Seller::factory()->create([
            'id_marketplace' => 1,
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => 'seller@example.com',
            'phone' => '+' . fake()->numerify('############'),
        ]);
        DB::table('sellers_passwords')->insert([
            'id_seller' => $seller->id_seller,
            'password' => Hash::make('seller_password'), // for test purposes
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Seller::factory(20)->create();
        $sellers = Seller::all(['id_seller', 'surname']);
        $sellers->each(function (Seller $seller) use ($sellers) {
            DB::table('sellers_passwords')->insert([
                'id_seller' => $seller->id_seller,
                'password' => Hash::make($seller->surname . $seller->surname), // for test purposes
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
