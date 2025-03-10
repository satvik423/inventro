<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->randomNumber(2),
            'stock' => $this->faker->randomNumber(2),
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
