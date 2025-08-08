<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->firstName();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'date' => $this->faker->dateTimeBetween('now', '+60 days'),
            'location' => $this->faker->address(),
            'roles' => [
                [
                    'role_name' => 'role 1',
                    'description' => 'description 1',
                    'start_date' => $this->faker->dateTimeBetween('now', '+60 days'),
                    'needed' => [
                        $this->faker->dateTimeBetween('now', '+60 days')->format('Y-m-d'),
                        $this->faker->dateTimeBetween('now', '+60 days')->format('Y-m-d'),
                    ],
                ],
                [
                    'role_name' => 'role 2',
                    'description' => 'description 2',
                    'start_date' => $this->faker->dateTimeBetween('now', '+60 days'),
                    'needed' => [
                        $this->faker->dateTimeBetween('now', '+60 days')->format('Y-m-d'),
                        $this->faker->dateTimeBetween('now', '+60 days')->format('Y-m-d'),
                    ],
                ],
            ],
            'picture' => $this->faker->imageUrl(),
        ];
    }
}
