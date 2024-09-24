<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->isLocal()) {
            $user = new \App\Models\User();
            $user->name = 'Local User';
            $user->email = 'user@local.dev';
            $user->password = bcrypt('password');
            $user->save();
        }
    }
}
