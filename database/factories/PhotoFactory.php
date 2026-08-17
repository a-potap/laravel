<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(4),
            'name_en' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'description_en' => $this->faker->paragraphs(3, true),
            'date_create' => $this->faker->dateTime(),
            'folder' => $this->faker->slug(),
        ];
    }
}