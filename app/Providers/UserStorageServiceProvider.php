<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class UserStorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->scoped('auth', function ($app) {
            $userId = auth()->id();
            $rootPath = "users/{$userId}";
            return Storage::createLocalDriver(['root' => $rootPath]);
        });

        $this->app->scoped('auth-public', function ($app) {
            $userId = auth()->id();
            $config = config('filesystems.disks.public');
            $config['root'] .= "/users/{$userId}";
            $config['url'] .= "/users/{$userId}";
            return Storage::createLocalDriver($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Storage::extend('user', function ($app, $config) {
            $config['root'] = str_replace('{user_id}', auth()->id(), $config['root']);
            return Storage::createLocalDriver($config);
        });
        Storage::extend('user-public', function ($app, $config) {
            $user_id = auth()->id();
            $config['root'] = str_replace('{user_id}', $user_id, $config['root']);
            $config['url'] = str_replace('{user_id}', $user_id, $config['url']);
            return Storage::createLocalDriver($config);
        });
    }
}
