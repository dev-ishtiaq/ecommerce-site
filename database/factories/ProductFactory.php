<?php

namespace Database\Factories;

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
        $title = fake()->unique()->name();
        $slug = Str::slug($title);

        $subCategories = [21,22];
        $subRandKey = array_rand($subCategories);

        $brand = [2,3];
        $brandRandKey = array_rand($brand);

        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => 121,
            'sub_category_id' => $subCategories,
            'brand' => $brand,
        ];
    }
}
