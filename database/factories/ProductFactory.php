<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $vendor = Vendor::query()->inRandomOrder()->first();

        return [
            'vendor_id' => $vendor->id,
            'name' => fake()->words(2, true),
            'price' => fake()->numberBetween(1000, 10000),
            'is_available' => fake()->boolean(90),
        ];
    }
}
