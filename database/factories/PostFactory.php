<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model =  Post::class;

    public function definition()
    {
        return [
            'id' => $this->faker->randomNumber(),
            'user_id' => $this->faker->randomNumber(),
            'title' => $this->faker->sentence  ,
            'body' => $this->faker->paragraph,
            'rating' =>  $this->faker->randomNumber(2),
        ];
    }
}
