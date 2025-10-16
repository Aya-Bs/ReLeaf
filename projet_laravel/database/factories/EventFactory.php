<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->catchPhrase,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->dateTimeBetween('+1 days', '+1 year'),
            'location_id' => Location::factory(),
            'max_participants' => $this->faker->numberBetween(10, 200),
            'status' => 'draft',
            'images' => [],
            'user_id' => User::factory(),
            'duration' => $this->faker->randomElement(['1 heure', '2 heures', '3 heures', 'Demi-journée', 'Journée entière', 'Week-end']),
            'campaign_id' => null,
        ];
    }
}
