<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'brief_description' => $this->faker->sentence(),
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(10000, 100000),
            'sale_price' => null,
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'status' => '판매중',
            'image_url' => 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=800',
            'is_new' => $this->faker->boolean(20),
            'is_best' => $this->faker->boolean(10),
        ];
    }
}
