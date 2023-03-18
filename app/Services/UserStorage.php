<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserStorage
{
    /**
     * Attributes that can be accessed via __get().
     *
     * @var array
     */
    private array $attributes = ['id', 'dir', 'fullDir'];

    public function __get($name)
    {
        return in_array($name, $this->attributes, true) ? $this->$name() : null;
    }

    /**
     * Get the user ID or an empty string if not logged in.
     */
    private function id(): string
    {
        return Auth::check() ? (string) Auth::id() : '0';
    }

    /**
     * Get the user storage directory.
     *
     * @return string
     */
    public function dir(): string
    {
        return "user/{$this->id}/";
    }

    /**
     * Get the user storage path from the application directory.
     *
     * @return string
     */
    public function fullDir(): string
    {
        return storage_path('app/' . $this->dir());
    }

    /**
     * Get the user storage path for the given file path.
     *
     * @param string $path File path within a user's folder.
     *
     * @return string
     */
    public function path(string $path): string
    {
        return $this->dir() . $path;
    }

    /**
     * Get the full path for the given user storage file path.
     *
     * @param string $path File path within a user's folder.
     *
     * @return string
     */
    public function fullPath(string $path): string
    {
        return storage_path('app/' . $this->path($path));
    }

    /**
     * Remove the user storage path from the given file path.
     *
     * @param string $path File path to a user's file from some place within the app directory.
     *
     * @return string
     */
    public function trimDir(string $path): string
    {
        $id = (string) $this->id();
        $id_length = strlen($id);
        $pos = strpos($path, $id . '/');
        if (false === $pos) {
            return $path;
        }
        if (0 === $pos) {
            return substr($path, $id_length + 1);
        }
        return substr($path, strpos($path, "/{$id}/") + $id_length + 2);
    }

    /**
     * Make user directory if it doesn't exist.
     *
     * @return void
     */
    public function makeDir(): void
    {
        if (!Storage::exists($this->dir())) {
            Storage::makeDirectory($this->dir());
        }
    }

    /**
     * Create directories recursively if missing from the destination.
     * We assume the directory `storage/app/user/` exists.
     *
     * @param string $path File path within a user's folder.
     *
     * @return void
     */
    public function makeMissingDirectories(string $path): void
    {
        // Make the user's root directory if it does not exist.
        $this->makeDir();

        // Remove the file name from the path if it exists.
        $basename = basename($path);
        if (\strpos($basename, '.') !== false) {
            $path = str_replace($basename, '', $path);
        }

        // Create the directories if they don't exist.
        $parts = array_filter(explode('/', $path));
        $current = 'user/' . $this->id() . '/';

        foreach ($parts as $directory) {
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
