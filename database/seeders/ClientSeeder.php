<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::factory()->create([
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => 'client@example.com',
            'phone' => '+' . fake()->numerify('############'),
        ]);
        DB::table('clients_passwords')->insert([
            'id_client' => $client->id_client,
            'password' => Hash::make('client_password'), // for test purposes
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Client::factory(50)->create();
        $clients = Client::all(['id_client', 'surname']);
        $clients->each(function (Client $client) use ($clients) {
            DB::table('clients_passwords')->insert([
                'id_client' => $client->id_client,
                'password' => Hash::make($client->surname . $client->surname), // for test purposes
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
