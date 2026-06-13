<?php

namespace Database\Factories;

use App\Models\Ride;
use Illuminate\Database\Eloquent\Factories\Factory;

class RideFactory extends Factory
{
    protected $model = Ride::class;

    public function definition(): array
    {
        return [
            'customer_id' => 1,
            'vehicle_type_id' => 3,
            'city_id' => 1,
            'pickup_address' => $this->faker->address(),
            'pickup_latitude' => $this->faker->latitude(),
            'pickup_longitude' => $this->faker->longitude(),
            'dropoff_address' => $this->faker->address(),
            'dropoff_latitude' => $this->faker->latitude(),
            'dropoff_longitude' => $this->faker->longitude(),
            'status' => 'completed',
            'estimated_distance_km' => $this->faker->numberBetween(5, 50),
            'actual_distance_km' => $this->faker->numberBetween(5, 50),
            'estimated_duration_minutes' => $this->faker->numberBetween(10, 120),
            'actual_duration_minutes' => $this->faker->numberBetween(10, 120),
            'base_fare' => 50,
            'distance_charge' => $this->faker->numberBetween(100, 1000),
            'time_charge' => $this->faker->numberBetween(50, 300),
            'total_amount' => $this->faker->numberBetween(500, 2000),
            'requested_at' => now(),
            'completed_at' => now(),
        ];
    }
}
