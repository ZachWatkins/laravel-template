<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

trait HasUserStorage
{
    protected Filesystem $userStore;
    protected Filesystem $publicUserStore;

    /**
     * Get the user's private storage driver.
     */
    public function userStorage(): Filesystem
    {
        if (isset($this->userStore)) {
            return $this->userStore;
        }

        if (!($user = Auth::user())) {
            return;
        }

        if (Storage::hasDriver('user')) {
            $config = Storage::getDriverConfig('user');
            $config['root'] = $config['root'] . $user->id . '/';
        } else {
            $config = [
                'driver' => 'local',
                'root' => storage_path('app/user/' . $user->id . '/'),
                'throw' => false,
            ];
        }
        return $this->userStore = Storage::build($config);
    }

    /**
     * Get the user's public storage driver.
     */
    public function publicUserStorage(): Filesystem
    {
        if (isset($this->publicUserStore)) {
            return $this->publicUserStore;
        }

        if (!($user = Auth::user())) {
            return;
        }

        if (Storage::hasDriver('user-public')) {
            $config = Storage::getDriverConfig('user-public');
            $config['root'] = $config['root'] . $user->id . '/';
            $config['url'] = $config['url'] . '/' . $user->id;
        } else {
            $config = [
                'driver' => 'local',
                'root' => storage_path('app/public/user/' . $user->id),
                'url' => env('APP_URL').'/storage/user/' . $user->id,
                'visibility' => 'public',
                'throw' => false,
            ];
        }
        return $this->publicUserStore = Storage::build($config);
    }
}