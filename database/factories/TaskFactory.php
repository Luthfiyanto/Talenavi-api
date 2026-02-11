<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'open', 'in_progress', 'completed']);

        return [
            'title' => fake()->sentence(),
            'assignee' => fake()->randomElement(['Ujang', 'Gatot', 'Azril', 'Fahri']),
            'due_date' => fake()->dateTimeBetween('+1 day', '+3 years'),
            'time_tracked' => match ($status) {
                'pending', 'open' => 0,
                'in_progress' => random_int(1, 5),
                'completed' => random_int(5, 15)
            },
            'status' => $status,
            'priority' => fake()->randomElement(['low', 'medium', 'high'])
        ];
    }
}
