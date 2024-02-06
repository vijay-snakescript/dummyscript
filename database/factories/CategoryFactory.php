<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = $this->faker->words(3, true);
        return [
            'type' => 'post',
            'status' => true,
            'name' => $category,
            'slug' => Str::slug($category),
            'meta_title' => $this->faker->realText(60),
            'meta_description' => $this->faker->realText(160),
            'title' => $this->faker->realText(60),
            'description' => $this->faker->realText(160),
        ];
    }

    /**
     * Indicate that the model's category is for tools
     *
     * @return static
     */
    public function tool()
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'tool',
        ]);
    }
}
