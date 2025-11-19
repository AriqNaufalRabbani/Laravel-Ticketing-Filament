<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'priority_id' => 1,
            'category_id' => 1,
            'department_id' => 1,
            'requester_id' => 1,
            'assigned_to' => 2,
            'status' => $this->faker->randomElement(['open','in_progress','resolved']),
        ];
    }
}
