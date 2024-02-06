<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FaqsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'question' => $this->faker->sentence($this->faker->numberBetween(8, 15)),
            'answer' => $this->faker->paragraphs(2, true),
            'status' => true,
            'pricing' => false,
        ];
    }

    /**
     * Indicate that the post is featured
     *
     * @return static
     */
    public function pricing()
    {
        return $this->state(fn (array $attributes) => [
            'pricing' => true,
        ]);
    }
}
