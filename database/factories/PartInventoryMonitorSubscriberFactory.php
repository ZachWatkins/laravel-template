<?php

namespace Database\Factories;

use App\Models\PartInventoryMonitor;
use App\Models\PartInventoryMonitorSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartInventoryMonitorSubscriberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PartInventoryMonitorSubscriber::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'part_inventory_monitor_id' => PartInventoryMonitor::factory(),
        ];
    }
}
