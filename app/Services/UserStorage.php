<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\HasUserStorage;
use Illuminate\Contracts\Filesystem\Filesystem;

class UserStorage
{
    use HasUserStorage {
        userStorage as disk;
        publicUserStorage as publicDisk;
    }

    public function __call(string $name, array $arguments)
    {
        return method_exists($this, $name)
            ? $this->$name(...$arguments)
            : $this->disk()->$name(...$arguments);
    }

    public function __get(string $name)
    {
        return $this->disk()->$name;
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
     * Detect whether a public file exists which was modified before a given timestamp.
     *
     * @param int|string|object $date Date to search before.
     *
     * @return UserStorage The current instance.
     */
    public function hasPublicFileBefore(int|string|object $date): bool
    {
        $date = $this->getDateSeconds($date);

        foreach ($this->publicDisk()->allFiles() as $file) {
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
        $disk = $this->disk();

        foreach ($this->allFiles() as $file) {
            if (Storage::lastModified($file) < $date) {
                Storage::delete($file);
            }
        }

        return $this;
    }

    /**
     * Delete all public files in the user's storage directory last modified before the given date.
     *
     * @param int|string|\DateTime $date Expiration date.
     *
     * @return UserStorage The current instance.
     */
    public function deletePublicFilesBefore(int|string|object $date): UserStorage
    {
        $date = $this->getDateSeconds($date);
        $disk = $this->publicDisk();

        foreach ($disk->allFiles() as $file) {
            if (Storage::lastModified($file) < $date) {
                $disk->delete($file);
            }
        }

        return $this;
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
