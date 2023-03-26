<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserStorageInterface;

class UserStorage implements UserStorageInterface
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
     * @return UserStorage The current instance.
     */
    public function setUser(int $id = -1): UserStorage
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
     * @param string $path      File or directory path within a user's directory.
     * @param bool   $recursive Whether to create directories recursively.
     *
     * @return UserStorage The current instance.
     */
    public function makeDirectory(string $path, bool $recursive = true): UserStorage
    {
        // Remove the file name from the path if it is present.
        if (!$this->isDirectory($path)) {
            $path = str_replace(basename($path), '', $path);
        }

        $current = $this->dir;

        // Recursive path creation by default.
        $parts = array_filter(explode('/', $path));
        if (!$recursive) {
            $parts = [rtrim($path, '/')];
        }

        foreach ($parts as $directory) {
            $current .= $directory . '/';
            if (!Storage::directoryExists($current)) {
                Storage::makeDirectory($current);
            }
        }

        return $this;
    }

    /**
     * Detect whether the file or directory exists.
     * Assumes a path without a trailing slash or file extension is a directory.
     *
     * @param string $path File path within a user's directory.
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return $this->isDirectory($path)
            ? Storage::directoryExists($this->dir . $path)
            : Storage::exists($this->dir . $path);
    }

    /**
     * Delete the file or directory within the user's directory.
     * Assumes a path without a trailing slash or file extension is a directory.
     *
     * @param string $path  File or directory within a user's directory.
     * @param bool   $quiet Whether to check if the path exists.
     *
     * @return bool
     */
    public function delete(string $path, bool $quiet = false): bool
    {
        if (!$quiet) {
            return $this->isDirectory($path)
                ? Storage::deleteDirectory($this->dir . $path)
                : Storage::delete($this->dir . $path);
        }

        if (!$this->exists($path)) {
            return false;
        }

        return $this->isDirectory($path)
            ? Storage::deleteDirectory($this->dir . $path)
            : Storage::delete($this->dir . $path);
    }

    /**
     * Detect whether a file exists which was modified before a given timestamp.
     *
     * @param int|string|object $date Date to search before.
     *
     * @return UserStorage The current instance.
     */
    public function hasFileBefore(int|string|object $date): bool
    {
        $date = $this->getDateSeconds($date);

        foreach ($this->allFiles() as $file) {
            if (Storage::lastModified($file) < $date) {
                return true;
            }
        }

        return false;
    }

    /**
     * Delete all files in the user's storage directory last modified before the given date.
     *
     * @param int|string|\DateTime $date Expiration date.
     *
     * @return UserStorage The current instance.
     */
    public function deleteFilesBefore(int|string|object $date): UserStorage
    {
        $date = $this->getDateSeconds($date);

        foreach ($this->allFiles() as $file) {
            if (Storage::lastModified($file) < $date) {
                Storage::delete($file);
            }
        }

        return $this;
    }

    /**
     * Handle each of a user's files, being mindful of memory consumption for large directories.
     *
     * @return \Generator
     */
    public function files(string $path = ''): \Generator
    {
        foreach (Storage::files($this->dir . $path) as $file) {
            yield $file;
        }
    }

    /**
     * Handle each of a user's files, being mindful of memory consumption for large directories.
     *
     * @return \Generator
     */
    public function allFiles(string $path = ''): \Generator
    {
        foreach (Storage::allFiles($this->dir . $path) as $file) {
            yield $file;
        }
    }

    /**
     * Detect whether the path is a directory.
     * Assumes a path without a trailing slash or file extension is a directory.
     *
     * @param string $path File path.
     *
     * @return bool
     */
    private function isDirectory(string $path): bool
    {
        return strpos($path, '/', -1) !== false
            || strpos(basename($path), '.') === false;
    }

    /**
     * Get the number of seconds from a date.
     *
     * @param int|string|object $date Date to get seconds from.
     *
     * @return int Number of seconds.
    */
    private function getDateSeconds(int|string|object $date): int
    {
        if (is_int($date)) {
            return $date;
        }
        return is_string($date) ? strtotime($date) : $date->getTimestamp();
    }
}
