<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Models\Location;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $example = User::factory()->example()->make();
        $user = User::where('name', $example->name)->firstOr(function () use ($example) {
            $example->save();
            return $example;
        });

        $count = Location::count();
        if (!$count) {
            Location::factory()->count(10)->state(fn ($attributes) => ['submitter_id' => $user->id])->create();
        }
    }
}
