<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'author' => fake()->name(),
            'rating' => fake()->numberBetween(1, 5),
            'price' => fake()->randomFloat(2, 4.99, 49.99),
            'cover_url' => null,
            'isbn' => fake()->isbn13(),
            'publisher' => fake()->company(),
            'description' => fake()->paragraph(),
            'publish_year' => fake()->year(),
            'pages' => fake()->numberBetween(100, 800),
        ];
    }
}
