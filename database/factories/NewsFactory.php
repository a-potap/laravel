<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text' => $this->faker->sentence(10),
            'text_en' => $this->faker->sentence(10),
            'date' => $this->faker->dateTime(),
        ];
    }
}