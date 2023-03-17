<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use App\Services\UserStorage;

class UserStorageProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the user storage service provider.
     */
    public function register(): void
    {
        $this->app->singleton(UserStorage::class, function (Application $app) {
            return new UserStorage;
        });
    }

    /**
     * Get the services provided by the provider which allows this to be a deferred provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [UserStorage::class];
    }
}
