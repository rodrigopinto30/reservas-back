<?php

namespace Database\Factories;

use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Space::class;

    public function definition(): array {

        $avail_from = $this->faker->dateTimeBetween('now', '+1 month');
        $avail_to = (clone $avail_from)->modify('+3 hours'); 

        return [
            'space_name' => $this->faker->words(3, true),
            //'space_desc' => $this->faker->sentence(),
            'space_capacity' => $this->faker->numberBetween(10, 100),
            'space_avail_from' => $avail_from->format('Y-m-d H:i:s'), 
            'space_avail_to' => $avail_to->format('Y-m-d H:i:s'),     
            'space_price_hour' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}