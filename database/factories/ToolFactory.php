<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tool>
 */
class ToolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $number = $this->faker->biasedNumberBetween(3, 6);
        $heading = "";
        $content = "";

        for ($i = 0; $i < $number; $i++) {
            $paragraph = "";
            $heading = '<h3>' . $this->faker->sentence . '</h3>';
            $paragraph_number = $this->faker->biasedNumberBetween(2, 5);
            for ($y = 0; $y < $paragraph_number; $y++) {
                $paragraph .= '<p>' . $this->faker->paragraph($this->faker->biasedNumberBetween(15, 30)) . '</p>';
            }


            $content .= $heading . $paragraph;
        }

        return [
            'description' => fake()->paragraph,
            'content' => $content,
            'meta_description' => $this->faker->sentence(6),
            'og_description' => $this->faker->sentence(6),
        ];
    }
}
