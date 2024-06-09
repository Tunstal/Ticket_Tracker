<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ticket;
use Faker\Factory as Faker;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        User::all()->each(function ($user) use ($faker) {
            for ($i = 0; $i < 5; $i++) {
                Ticket::create([
                    'title' => $faker->sentence,
                    'description' => $faker->paragraph,
                    'status' => $faker->randomElement(['open', 'closed']),
                    'idUser' => $user->idUser,
                    'idCategory' => $faker->numberBetween(1, 3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}