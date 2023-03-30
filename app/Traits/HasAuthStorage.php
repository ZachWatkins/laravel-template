<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

trait HasAuthStorage
{
    protected Filesystem $userStorage;
    protected Filesystem $publicUserStorage;

    /**
     * Get the authenticated user's private storage driver.
     */
    public function storage(): Filesystem
    {
        if (isset($this->userStore)) {
            return $this->userStore;
        }

        if (!($user = Auth::user())) {
            return;
        }

        $user_id = $user->id;

        $disk = config('filesystems.disks.user', [
            'driver' => 'local',
            'root' => storage_path('app/user/{user_id}'),
            'throw' => false,
        ]);

        if ($user_id) {
            $disk['root'] = false !== strpos('{user_id}', $disk['root'])
                ? str_replace('{user_id}', $user_id, $disk['root'])
                : rtrim($disk['root'], '/') . '/' . $user_id;
        } else {
            $disk['root'] = str_replace('/\/?{user_id}\/?/', '/', $disk['root']);
        }

        return $this->userStore = Storage::build($disk);
    }

    /**
     * Get the authenticated user's public storage driver.
     */
    public function publicStorage(): Filesystem
    {
        if (isset($this->publicUserStore)) {
            return $this->publicUserStore;
        }

        if (!($user = Auth::user())) {
            return;
        }

        $user_id = $user->id;

        $disk = config('filesystems.disks.user-public', [
            'driver' => 'local',
            'root' => storage_path('app/public/user/{user_id}'),
            'url' => env('APP_URL').'/storage/user/{user_id}',
            'visibility' => 'public',
            'throw' => false,
        ]);

        if ($user_id) {
            $disk['root'] = false !== strpos('{user_id}', $disk['root'])
                ? str_replace('{user_id}', $user_id, $disk['root'])
                : rtrim($disk['root'], '/') . '/' . $user_id;
            $disk['url'] = false !== strpos('{user_id}', $disk['url'])
                ? str_replace('{user_id}', $user_id, $disk['url'])
                : rtrim($disk['url'], '/') . '/' . $user_id;
        } else {
            $disk['root'] = str_replace('/\/?{user_id}\/?/', '/', $disk['root']);
            $disk['url'] = str_replace('/\/?{user_id}\/?/', '/', $disk['url']);
        }

        return $this->publicUserStore = Storage::build($disk);
    }
}
