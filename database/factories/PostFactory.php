<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(7);

        return ['title' => $title, 'slug' => Str::slug($title), 'excerpt' => fake()->sentence(20), 'content' => fake()->paragraphs(6, true), 'status' => 'published', 'published_at' => now()];
    }
}
