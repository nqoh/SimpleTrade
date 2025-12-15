<?php

namespace Database\Seeders;

use App\Models\Assets;
use App\Models\Order;
use App\Models\Trades;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
         User::factory(1)->create(['email'=>'user1@test.com','balance' => 10000]);

         User::factory(1)->has(
             Assets::factory(),
         )->create(['email' => 'user2@test.com', 'balance'=> 0.00]);


         Order::factory()->create(['side' => 'buy', 'price' => 950, 'user_id' => 1]);
         Order::factory()->create(['side' => 'sell','price' => 50, 'user_id' => 2]);

         Trades::factory();

    }
}
