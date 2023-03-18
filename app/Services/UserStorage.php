<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserStorage
{
    /**
     * User ID.
     *
     * @var int
     */
    private int $id;

    /**
     * User's storage directory.
     *
     * @var string
     */
    private string $dir;

    /**
     * User's storage directory with the full path.
     *
     * @var string
     */
    private string $fullDir;

    /**
     * Allow this class to check the user's ID on every call or cache it with a setter.
     *
     * @param string $name The name of the property being accessed.
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if ('id' === $name) {
            return $this->id ?? (Auth::check() ? Auth::id() : 0);
        }
        if ('dir' === $name) {
            return $this->dir ?? 'user/' . (Auth::check() ? Auth::id() : 0) . '/';
        }
        if ('fullDir' === $name) {
            return $this->fullDir ?? storage_path('app/user/' . (Auth::check() ? Auth::id() : 0) . '/');
        }
        return null;
    }

    /**
     * Set the user ID.
     *
     * @param int $id User ID.
     *
     * @return UserStorage
     */
    public function forUser(int $id = -1): UserStorage
    {
        if ($id >= 0) {
            $this->id = $id;
            $this->dir = "user/{$id}/";
            $this->fullDir = storage_path("app/user/{$id}/");
        } else {
            unset($this->id);
            unset($this->dir);
            unset($this->fullDir);
        }
        return $this;
    }

    /**
     * Create directories recursively if missing from the destination.
     * We assume the directory `storage/app/user/` exists.
     *
     * @param string $path File path within a user's folder.
     *
     * @return UserStorage
     */
    public function createMissingDirectories(string $path): UserStorage
    {
        // Remove the file name from the path if it is present.
        $basename = basename($path);
        if (\strpos($basename, '.') !== false) {
            $path = str_replace($basename, '', $path);
        }

        // Split each directory into an array and create it if it doesn't exist.
        $parts = array_filter(explode('/', $path));
        $current = $this->dir;

        foreach ($parts as $directory) {
            $current .= $directory . '/';
            if (!Storage::exists($current)) {
                Storage::makeDirectory($current);
            }
        }

        return $this;
    }

    /**
     * Delete the destination file.
     *
     * @return bool
     */
    public function delete(string $path, bool $quiet = false): bool
    {
        if (!$quiet) {
            return Storage::delete($this->dir . $path);
        }
        if (Storage::exists($this->dir . $path)) {
            return Storage::delete($this->dir . $path);
        }
        return false;
    }

    /**
     * Delete the destination directory within the user's folder.
     *
     * @return bool
     */
    public function deleteDirectory(string $path)
    {
        return Storage::deleteDirectory($this->dir . $path);
    }
}
