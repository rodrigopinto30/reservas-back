<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Reservation::class;

    public function definition(): array {
        return [
            'user_id' => $this->faker->numberBetween(1,2),
            'space_id' => $this->faker->numberBetween(3, 10),
            'reserv_name' => $this->faker->word(2, true),
            'reserv_start' => $this->faker->dateTimeBetween('now', '+1 days'),
            'reserv_end' => $this->faker->dateTimeBetween('+1 days', '+2 days'),
            'reserv_status' => 'confirmed',
        ];
    }
}
