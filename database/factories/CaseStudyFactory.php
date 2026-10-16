<?php

namespace Database\Factories;

use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CaseStudy>
 */
class CaseStudyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return ['title' => $title, 'slug' => Str::slug($title), 'summary' => fake()->sentence(18), 'overview' => fake()->paragraphs(2, true), 'challenge' => fake()->paragraphs(2, true), 'solution' => fake()->paragraphs(3, true), 'status' => 'draft'];
    }
}
