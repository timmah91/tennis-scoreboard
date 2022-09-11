<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $id = 0;
        return [
            'id' => ++$id,
            'name' => 'Player ' . $id,
            'score' => 0,
            'advantage' => 0,
            'formattedScore' => NULL,
        ];
    }
}
