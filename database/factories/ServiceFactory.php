<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return ['title' => Str::title($title), 'slug' => Str::slug($title), 'summary' => fake()->sentence(16), 'overview' => fake()->paragraphs(3, true), 'status' => 'published', 'published_at' => now()];
    }
}
