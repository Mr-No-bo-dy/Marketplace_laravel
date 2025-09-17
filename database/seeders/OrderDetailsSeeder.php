<?php

namespace Database\Seeders;

use App\Models\OrderDetails;
use Illuminate\Database\Seeder;

class OrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderDetails::factory(500)->create();
    }
}
