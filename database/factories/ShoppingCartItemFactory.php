<?php

namespace Database\Factories;

use App\Models\Part;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingCartItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShoppingCartItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'unit_price' => $this->faker->numberBetween(-10000, 10000),
            'shopping_cart_id' => ShoppingCart::factory(),
            'part_id' => Part::factory(),
        ];
    }
}
