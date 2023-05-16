<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Analytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:analytics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report analytics for the application.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Get the number of users who logged in today.
        $users = \App\Models\User::whereDate('last_login_at', today())->count();
    }
}
