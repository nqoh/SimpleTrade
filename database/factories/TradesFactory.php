<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trades>
 */
class TradesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'buy_order_id' => 1,
            'sell_order_id' => 2,
            'price' => 950.00000000,
            'symbol' => 'BTC',
            'amount' => 1,
            'usd_volume' => 950.00000000,
            'fee' => 14.25000000 ,
            'status' => 1
        ];
    }
}
