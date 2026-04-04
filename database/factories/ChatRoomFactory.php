<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatRoom>
 */
class ChatRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'group',
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'created_by' => User::factory(),
        ];
    }

    public function private()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'private',
                'name' => null,
                'description' => null,
            ];
        });
    }
}
