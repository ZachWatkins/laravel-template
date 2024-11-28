<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Part;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'unit_price' => $this->faker->numberBetween(-10000, 10000),
            'total' => $this->faker->numberBetween(-10000, 10000),
            'part_name' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'order_id' => Order::factory(),
            'part_id' => Part::factory(),
        ];
    }
}
