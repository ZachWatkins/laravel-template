<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Locale;
use App\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->numberBetween(-10000, 10000),
            'payment_state' => $this->faker->randomElement(["pending","paid","partially_refunded","refunded"]),
            'shipping_state' => $this->faker->randomElement(["pending","shipped","delivered","returned"]),
            'items_total' => $this->faker->numberBetween(-10000, 10000),
            'total' => $this->faker->randomFloat(2, 0, 99999999.99),
            'token_value' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'customer_ip' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'created_by_guest' => $this->faker->boolean(),
            'notes' => $this->faker->text(),
            'customer_id' => Customer::factory(),
            'currency_id' => Currency::factory(),
            'locale_id' => Locale::factory(),
        ];
    }
}
