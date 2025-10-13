<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Park',
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'capacity' => $this->faker->numberBetween(10, 200),
            'description' => $this->faker->sentence(10),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'images' => [],
        ];
    }
}
