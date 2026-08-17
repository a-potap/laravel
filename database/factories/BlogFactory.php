<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(5),
            'title_en' => $this->faker->sentence(5),
            'text' => $this->faker->paragraphs(3, true),
            'text_en' => $this->faker->paragraphs(3, true),
            'date' => $this->faker->dateTime(),
        ];
    }
}