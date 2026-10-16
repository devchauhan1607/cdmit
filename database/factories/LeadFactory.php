<?php

namespace Database\Factories;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(), 'email' => fake()->safeEmail(), 'company' => fake()->company(),
            'country' => fake()->country(), 'message' => fake()->paragraph(3), 'consent' => true,
            'status' => fake()->randomElement(LeadStatus::cases())->value,
            'notification_status' => 'pending', 'source' => 'test',
        ];
    }
}
