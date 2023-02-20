<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = Category::inRandomOrder()->first();
        return [
            'name'=> $this->faker->word(),
            'category_id' =>$category,
            'price' => $this->faker->randomFloat(),
            'quantity' => $this->faker->randomDigit(),
            'image' => $this->faker->image(),
            'description' =>$this->faker->paragraph()
        ];
    }
}
