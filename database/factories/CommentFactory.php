<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'iduser' => $this->faker->lexify('user_?????'),
            'text' => $this->faker->paragraph,
            'blog_id' => Blog::factory(),
        ];
    }
}