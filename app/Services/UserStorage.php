<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserStorage
{
    private function id(): int
    {
        return Auth::check() ? Auth::id() : 0;
    }

    public function dir(): int
    {
        return 'user/' . $this->id() . '/';
    }

    public function path(string $path): string
    {
        return $this->dir() . $path;
    }

    public function trimPath(string $path): string
    {
        return str_replace($this->dir(), '', $path);
    }

    public function appPath(string $path): string
    {
        return storage_path('app/' . $this->path($path));
    }

    /**
     * Create directories recursively if missing from the destination.
     */
    public function makeDirectoriesRecursively(string $path): void
    {
        $directories = explode('/', $this->id() . "/{$path}");

        // Remove the file name from the directory list.
        array_pop($directories);

        $current = 'user/';
        foreach ($directories as $directory) {
            $current .= $directory . '/';
            if (!Storage::exists($current)) {
                Storage::makeDirectory($current);
            }
        }
    }

    /**
     * Delete the destination file if it exists.
     *
     * @return void
     */
    public function deleteIfExists(string $path): void
    {
        $path = $this->path($path);
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
