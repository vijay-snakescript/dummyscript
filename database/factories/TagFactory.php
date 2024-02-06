<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = $this->faker->word();
        return [
            'status' => true,
            'name' => $category,
            'slug' => Str::slug($category),
            'title' => $this->faker->realText(60),
            'description' => $this->faker->realText(160),
        ];
    }
}
