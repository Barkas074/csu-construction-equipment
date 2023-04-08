<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->realText(rand(30, 40));
        return [
            'name' => $name,
            'content' => fake()->realText(rand(150, 200)),
            'slug' => Str::slug($name),
        ];
    }
}
