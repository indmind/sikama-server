<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $customer = Client::customers()->inRandomOrder()->first();
        $seller = Client::sellers()->inRandomOrder()->first();

        return [
            'customer_id' => $customer->id,
            'vendor_id' => $seller->vendor->id,
            'schedule_time' => now()->addHours(rand(1, 24)),
            'status' => OrderStatus::Active,
        ];
    }
}
