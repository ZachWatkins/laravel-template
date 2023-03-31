<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Filesystem\Filesystem;

class UserStorageServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        fwrite(STDERR, 'UserStorageServiceProvider::boot()' . PHP_EOL);
        Storage::extend('user', function ($app, $config) {
            fwrite(STDERR, Auth::user()->id . PHP_EOL);
            fwrite(STDERR, Auth::id() . PHP_EOL);
            $config = config('filesystems.disks.user');
            $config['root'] = str_replace('{user_id}', Auth::user()->id, $config['root']);
            return Storage::createLocalDriver($config);
        });
        Storage::extend('user-public', function ($app, $config) {
            fwrite(STDERR, Auth::user()->id . PHP_EOL);
            fwrite(STDERR, Auth::id() . PHP_EOL);
            $user_id = Auth::user()->id;
            $config = config('filesystems.disks.user-public');
            $config['root'] = str_replace('{user_id}', $user_id, $config['root']);
            $config['url'] = str_replace('{user_id}', $user_id, $config['url']);
            return Storage::createLocalDriver($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
       return [Filesystem::class];
    }
}
